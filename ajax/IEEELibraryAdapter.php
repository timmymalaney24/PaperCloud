<?php

require_once 'LibraryAdapter.php';
require_once 'HTTPRequestManager.php';

class IEEELibraryAdapter extends LibraryAdapter {

	function searchPapers($field, $value, $exact, $limit)
	{
		$papers = array();
		
		$querystring = 'hc=' . $limit;
		switch ($field) {
			case 'name':
				$querystring .= '&au=' . urlencode(str_replace(",", " ", $value));
				break;
			case 'publication':
				$querystring .= '&jn=' . urlencode($value);
				break;
			case 'keyword':
				$querystring .= '&ab=' . urlencode($value);
				break;
		}

		$ieeeURL = 'http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?' . $querystring;
		$ieeeXML = $this->requestManager->request($ieeeURL); // this request is a bottleneck

		$doc = new DOMDocument();
		$doc->loadXML($ieeeXML); // the response is well-formed XML

		$xpath = new DOMXPath($doc);
		$documents = $xpath->query("//document"); // query for each paper
		foreach ($documents as $document) {
			$paper = array();
			
			$paper["source"] = "ieee";
			
			// Query the DOI
			$dois = $xpath->query("./doi", $document);
			if ($dois->length > 0)
				$paper["id"] = $dois[0]->textContent;
			else
			// @codeCoverageIgnoreStart
				continue;
			// @codeCoverageIgnoreEnd

			// Query the paper title
			$titles = $xpath->query("./title", $document);
			if ($titles->length > 0)
				$paper["title"] = $titles[0]->textContent;
			else
			// @codeCoverageIgnoreStart
				continue;
			// @codeCoverageIgnoreEnd
			
			// Query the paper authors
			$authorss = $xpath->query("./authors", $document);
			if ($authorss->length > 0) {
				$paper["authors"] = $authorss[0]->textContent;
				
				if ($exact && $field == 'name' && (stripos($paper["authors"], $value) === false))
					continue; // This entry doesn't contain the full author name.
			} else
			// @codeCoverageIgnoreStart
				continue;
			// @codeCoverageIgnoreEnd
			
			// Query the paper publication name
			$publications = $xpath->query("./pubtitle", $document);
			if ($publications->length > 0) {
				$paper["publication"] = $publications[0]->textContent;
				
				if ($exact && $field == "publication" && (stripos($paper["publication"], $value) === false))
					continue;
				
			} else
			// @codeCoverageIgnoreStart
				continue;
			// @codeCoverageIgnoreEnd
	
			// Query the full text URL name
			$fullTextURLs = $xpath->query("./pdf", $document);
			if ($fullTextURLs->length > 0)
				$paper["fullTextURL"] = $fullTextURLs[0]->textContent;
			else
			// @codeCoverageIgnoreStart
				continue;
			// @codeCoverageIgnoreEnd
			
			// Query the paper abstract
			$abstracts = $xpath->query("./abstract", $document);
			if ($abstracts->length > 0)
				$paper["abstract"] = $abstracts[0]->textContent;
			else
			// @codeCoverageIgnoreStart
				continue;
			// @codeCoverageIgnoreEnd
			
			// Query the keyword terms
			$terms = $xpath->query("./*/term", $document);
			$paper["keywords"] = $paper["title"];
			foreach ($terms as $term) {
				$paper["keywords"] .= " " . $term->textContent;
			}
			
			$papers[] = $paper;
		}
		
		return $papers;
	}
	
	function getBibtexForPaper($paper) {
		// Get Bibtex
		$url = 'http://www.doi2bib.org/doi2bib?id=' . urlencode($paper["id"]);
		$bibtex = @$this->requestManager->request($url);
		
		return $bibtex;
	}

	function getFullTextForPaper($paper) {
		$driver = new \Behat\Mink\Driver\Selenium2Driver();
		$session = new \Behat\Mink\Session($driver);

		$session->start();
		$session->visit($paper['fullTextURL']);

		$session->wait(
			10000,
			'$("frame:eq(1)").length > 0'
		);
		$realURL = $session->evaluateScript('return $("frame:eq(1)").attr("src");');

		$session->visit($realURL);

		$session->wait(
			10000,
			"PDFViewerApplication != null && PDFViewerApplication.pdfDocument != null"
		);

		$session->executeScript('
			PDFViewerApplication.pdfDocument.getData().then(function(d) {
				window.pdfBlob = PDFJS.createBlob(d);
		
				var freader = new FileReader();
				freader.addEventListener("loadend", function() {
					window.pdf64 = freader.result;
				});
				freader.readAsDataURL(window.pdfBlob);
			});');

		$session->wait(
			10000,
			"window.pdf64 != undefined"
		);

		$blob = $session->evaluateScript('return window.pdf64;');

		$session->stop();

		//return substr(strstr($blob, ","), 1);
		return base64_decode(substr(strstr($blob, ","), 1));
	}
	
	function getAbstractForPaper($paper) {
		return $paper["abstract"];
	}
}
LibraryAdapter::registerLibrary("ieee", new IEEELibraryAdapter());
