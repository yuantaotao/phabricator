<?php

final class PhabricatorSearchIndexer {

  public function queueDocumentForIndexing($phid) {
    PhabricatorWorker::scheduleTask(
      'PhabricatorSearchWorker',
      array(
        'documentPHID' => $phid,
      ),
      array(
        'priority' => PhabricatorWorker::PRIORITY_IMPORT,
      ));
  }

  public function indexDocumentByPHID($phid) {
    $indexers = id(new PhutilSymbolLoader())
      ->setAncestorClass('PhabricatorSearchDocumentIndexer')
      ->loadObjects();

    foreach ($indexers as $indexer) {
      if ($indexer->shouldIndexDocumentByPHID($phid)) {
        $indexer->indexDocumentByPHID($phid);
        break;
      }
    }

    return $this;
  }

}
