<?php
use CRM_Multieventmap_ExtensionUtil as E;

class CRM_Multieventmap_Page_MultiEventMap extends CRM_Core_Page {

  public function run() {

    $action = CRM_Utils_Request::retrieve('action', 'string', $this, false, 'all');
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
   CRM_Utils_System::setTitle(E::ts('MultiEventMap'));

    CRM_Core_Resources::singleton()->addScriptUrl('https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/build/ol.js');
    CRM_Core_Resources::singleton()->addStyleUrl('https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/css/ol.css');
    CRM_Core_Resources::singleton()->addStyleFile('be.chiro.civi.multieventmap', 'css/multieventmap.css');
    CRM_Core_Resources::singleton()->addStyleUrl('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
    CRM_Core_Resources::singleton()->addScriptUrl('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js', 10, 'html-header');


    $form = new CRM_Core_Controller_Simple('CRM_Event_Form_SearchEvent', ts('Search Events'), CRM_Core_Action::ADD);
    $form->setEmbedded(TRUE);
    $form->setParent($this);
    $form->process();
    $form->run();

    $params = $this->get_params();
    $events = civicrm_api3('Event', 'get', $params)['values'];

    $events_no_location = [];
    foreach ($events as $event){
      if (!array_key_exists("loc_block_id.address_id.geo_code_1", $event)){
        $events_no_location[] = $event;
      }
    }
    //trucje om arraydif met geneste arrays te kunnen doen
    $events_location = array_diff(array_map('json_encode', $events), array_map('json_encode', $events_no_location));
    $func = function ($value){
      return json_decode($value, $assoc=true);
    };
    $events_location = array_map($func, $events_location);

    $this->assign('events_location', $events_location);
    $this->assign('events_no_location', $events_no_location);

    parent::run();
  }

  private function get_params(){
    $params = [
      'sequential' => 1,
      'options' => ['limit' => 0],
      'return' => ["loc_block_id.address_id.geo_code_1","loc_block_id.address_id.geo_code_2","title","id"],
    ];

    $title = $this ->get('title');
    if ($title){
      $params['title'] = ['LIKE' => "%$title%"];
    }

    $type_id = $this->get('event_type_id');
    if ($type_id){
      $params['event_type_id'] = ['IN' => $type_id];
    }

    $campaigns = $this->get('campaign_id');
    if ($campaigns){
      $params['campaign_id'] = ['IN' => $campaigns];
    }

    $eventsByDates = $this->get('eventsByDates');
    if ($eventsByDates) {

      $from = $this->get('start_date');
      $to = $this->get('end_date');


      if (!CRM_Utils_System::isNull($from)) {
        $params['end_date'] = ['>=' => $from];
      }

      if (!CRM_Utils_System::isNull($to)) {
        $params['start_date'] = ['<=' => $to];
      }
    }
    else {
      $curDate = date('YmdHis');
      $params['end_date'] = ['>=' => $curDate ];
    }
    return $params;
  }

}
