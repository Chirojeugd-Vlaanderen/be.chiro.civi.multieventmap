{include file="CRM/Event/Form/SearchEvent.tpl"}
<div class="flex-container">
  <div id="map" clas="map"></div>
  <div id="popup" title="event location"></div>
  <div class="events_no_location">
    <h2>Events without location</h2>
    <ul>
      {foreach from=$events_no_location item=event}
        <li><a href=/civicrm/event/info?id={$event.id}>{$event.title}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

{literal}
  <script type="text/javascript" src="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/build/ol.js"></script>

  <script type="text/javascript">
    var pos = ol.proj.fromLonLat([0,0]);
    var map = new ol.Map({
      target: 'map',
      layers: [
        new ol.layer.Tile({
          source: new ol.source.OSM()
        })
      ],
      view: new ol.View({
        center: pos,
        zoom: 4
      })
    });

    map.on('click', function (evt) {
      var element = popup.getElement();
      CRM.$(element).popover('destroy');
    });

    var popup = new ol.Overlay({
      element: document.getElementById("popup")
    });
    map.addOverlay(popup);

    //dit is een hack om te zorgen dat de map zijn formaat opnieuw berekend na het renderen van de pagina, zonder dit bewegen de kaart zelf en markers aan een verschillend tempo
    document.addEventListener("DOMContentLoaded", function (evt) {
      map.updateSize();
    });

    function addMarker(markername, position, content){
      var marker = new ol.Overlay({
        position: ol.proj.fromLonLat(position),
        element: document.getElementById(markername)
      });
      map.addOverlay(marker);

      CRM.$('#'+markername).click(function (evt) {
        var element = popup.getElement();
        CRM.$(element).popover('destroy');

        var coordinate = map.getEventCoordinate(evt);
        popup.setPosition(coordinate);
        CRM.$(element).popover({
          placement: 'top',
          html: true,
          animation: false,
          content: content
        });
        CRM.$(element).popover('show');
      });
    }
  </script>
{/literal}

{assign var=latname value="loc_block_id.address_id.geo_code_1"}
{assign var=lonname value="loc_block_id.address_id.geo_code_2"}
{foreach from=$events_location key=counter item=event}
  <div id="marker{$counter}" class="marker"></div>
  <script type="text/javascript">
    var content = '<a href=/civicrm/event/info?id={$event.id}>{$event.title}</a>'
    addMarker('marker{$counter}', [{$event.$lonname}, {$event.$latname}], content);
  </script>
{/foreach}
