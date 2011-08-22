<?php
include_once "phpQuery.php";
phpQuery::newDocumentFileXHTML('temp')->find('p');
$ul = pq('ul');

// Import into selected document:
// doesn't accept text nodes at beginning of input string
pq('<div/>');
// Import into document with ID from $pq->getDocumentID():
pq('<div/>', $pq->getDocumentID());
// Import into same document as DOMNode belongs to:
pq('<div/>', DOMNode);
// Import into document from phpQuery object:
pq('<div/>', $pq);
