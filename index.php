<?php
include "Repository/SQL_Connection.php";
require_once ("Repository/CrimeRepository.php");
require_once ("Repository/CategoryRepository.php");
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Crime Report</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <style type="text/css">
            .popup {
                width:200px;
                height:100px;
                position:absolute;
                top:50%;
                left:50%;
                margin:-50px 0 0 -100px;
                display:none;
                z-index: 9999;
            }
        </style>

        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/redmond/jquery-ui.css" />
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
        <link rel="stylesheet" type="text/css" media="screen" href="http://tarruda.github.com/bootstrap-datetimepicker/assets/css/bootstrap-datetimepicker.min.css">

        <script>
            var geocoder;
            var map;

            var customIcons =
            {
                restaurant:
                {
                    icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png',
                    shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
                },
                bar:
                {
                    icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png',
                    shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
                }
            };

            function initialize()
            {
                geocoder = new google.maps.Geocoder();

                map = new google.maps.Map(document.getElementById("map-canvas"), {
                    center: new google.maps.LatLng(24.89338, 67.02806),
                    zoom: 13,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });

                var infoWindow = new google.maps.InfoWindow;
                var CrimesDateRange = document.getElementById("drpTimeFilter").value;
                var CrimesCategories = "";
                //alert(CrimesCategories);

                for(f = 0; f < document.forms.length ; f++)
                {
                    frm = document.forms[f];
                    for(e = 0; e < frm.elements.length; e++)
                    {
                        elm = frm.elements[e];
                        if(elm.type == "checkbox")
                        {
                            if(elm.checked)
                            {
                                CrimesCategories = CrimesCategories + elm.value + ",";
                            }
                        }
                    }
                }

                if(CrimesCategories == "")
                {
                    CrimesCategories = "All";
                }
                else
                {
                    CrimesCategories = CrimesCategories.substring(0, CrimesCategories.length - 1);
                }

                downloadUrl("CrimesRetriever.php?CrimesDateRange=" + CrimesDateRange + "&CrimesCategories=" + CrimesCategories, function(data)
                {
                    var xml = data.responseXML;

                    var markers = xml.documentElement.getElementsByTagName("marker");
                    for (var i = 0; i < markers.length; i++)
                    {
                        var name = markers[i].getAttribute("Description");
                        var otherDetail = markers[i].getAttribute("OtherDetail");
                        var categoryName = markers[i].getAttribute("CategoryName");
                        var locationName = markers[i].getAttribute("LocationName");
                        var crimeDate = markers[i].getAttribute("CrimeDate");
                        var crimeTime = markers[i].getAttribute("CrimeTime");

                        var type = "restaurant";
                        var point = new google.maps.LatLng(
                                parseFloat(markers[i].getAttribute("lat")),
                                parseFloat(markers[i].getAttribute("lng")));
                        //var html = "<b>Description: " + name + "</b> <br>Category: " + categoryName + "<br>Date: " + crimeDate + "<br>Time: " + crimeTime + "<br>Location: " + locationName + "<br>Details: " + otherDetail; // + "</b> <br/>" + address;
                        var html = "<h4 class='lbl-map-dsc'>" + name + "</h4>";
                        html = html + "<p><b class='lbl-map'>Category:</b>" + categoryName + "</p>";
                        html = html + "<p><b class='lbl-map'>Date:</b>" + crimeDate + "</p>";
                        html = html + "<p><b class='lbl-map'>Time:</b>" + crimeTime + "</p>";
                        html = html + "<p><b class='lbl-map'>Location:</b>" + locationName + "</p>";
                        html = html + "<p><b class='lbl-map'>Details:</b>" + otherDetail + "</p>";
                        var icon = customIcons[type] || {};
                        var marker = new google.maps.Marker({
                            map: map,
                            position: point,
                            icon: icon.icon,
                            shadow: icon.shadow
                        });

                        bindInfoWindow(marker, map, infoWindow, html);
                        bindEvents(map);

                        var strictBounds = new google.maps.LatLngBounds(
                                new google.maps.LatLng(24.768031, 66.901245),
                                new google.maps.LatLng(25.097414, 67.350998)
                        );

                        restrictCity(map, strictBounds);
                        setZoomLevel(map);
                        addressAutoComplete();
                    }
                });

                $.getJSON('http://jsonip.appspot.com/?callback=?',function(data)
                {
                    document.getElementById('clientIP').value=data.ip;
                });

            }

            function setZoomLevel(map)
            {
                var opt = { minZoom: 12, maxZoom: 20};
                map.setOptions(opt);
            }

            function addressAutoComplete()
            {
                var input = document.getElementById('address');
                var autocomplete = new google.maps.places.Autocomplete(input);
                google.maps.event.addListener(autocomplete, 'place_changed', function ()
                {
                    var place = autocomplete.getPlace();
                    codeAddress();
                });
            }

            function restrictCity(map, strictBounds)
            {
                google.maps.event.addListener(map, 'dragend', function ()
                {
                    if (strictBounds.contains(map.getCenter())) return;

                    var c = map.getCenter(),
                            x = c.lng(),
                            y = c.lat(),
                            maxX = strictBounds.getNorthEast().lng(),
                            maxY = strictBounds.getNorthEast().lat(),
                            minX = strictBounds.getSouthWest().lng(),
                            minY = strictBounds.getSouthWest().lat();

                    if (x < minX) x = minX;
                    if (x > maxX) x = maxX;
                    if (y < minY) y = minY;
                    if (y > maxY) y = maxY;

                    map.setCenter(new google.maps.LatLng(y, x));
                });
            }

            function bindEvents(map)
            {
                google.maps.event.addListener(map, 'click', function(event)
                {
                    setCoordinates(event.latLng);
                    setLocationTags(event.latLng);
                    showPopup('popup');
                });
            }

            function bindInfoWindow(marker, map, infoWindow, html)
            {
                google.maps.event.addListener(marker, 'click', function()
                {
                    infoWindow.setContent(html);
                    infoWindow.open(map, marker);
                });
            }

            function downloadUrl(url, callback)
            {
                var request = window.ActiveXObject ?
                        new ActiveXObject('Microsoft.XMLHTTP') :
                        new XMLHttpRequest;

                request.onreadystatechange = function()
                {
                    if (request.readyState == 4)
                    {
                        request.onreadystatechange = doNothing;
                        callback(request, request.status);
                    }
                };

                request.open('GET', url, true);
                request.send(null);
            }

            function doNothing()
            {

            }

            function showPopup(id)
            {
                var popup = document.getElementById(id);
                popup.style.display = 'block';
            }

            function hidePopup(id)
            {
                var popup = document.getElementById(id);
                popup.style.display = 'none';
            }

            function placeMarker(location)
            {
                var marker = new google.maps.Marker({
                    position: location,
                    map: map
                });
            }

            function setCoordinates(location)
            {
                document.getElementById('longitude').value= location.lng();
                document.getElementById('latitude').value= location.lat();
            }

            function setLocationTags(location)
            {
                var lat = location.lat();
                var lng = location.lng();
                var latlng = new google.maps.LatLng(lat, lng);

                geocoder.geocode({'latLng': latlng}, function(results, status)
                {
                    if (status == google.maps.GeocoderStatus.OK)
                    {
                        if (results[1]) {
                            var allplaces = results[0].formatted_address;
                            var split = allplaces.split(",");
                            var desireplace = split[0];
                            document.getElementById('location').innerHTML = desireplace + ', ' + results[1].formatted_address;
                            document.getElementById('locationName').value = desireplace + ', ' + results[1].formatted_address;
                        }
                    }
                    else
                    {
                    }
                });
            }

            function codeAddress()
            {
                var address = document.getElementById('address').value;
                geocoder.geocode( { 'address': address}, function(results, status)
                {
                    if (status == google.maps.GeocoderStatus.OK)
                    {
                        map.setCenter(results[0].geometry.location);
                        var marker = new google.maps.Marker({
                            map: map,
                            position: results[0].geometry.location
                        });
                    }
                    else
                    {
                        //alert('Geocode was not successful for the following reason: ' + status);
                    }
                });
            }
        </script>
    </head>
    <body onload="initialize()">

        <div id="popup" class="popup modal">
            <form action="Controller/CrimeController.php" method="POST" id="frmReportCrime" >

                <input type="hidden" id="locationTags" name="locationTags">
                <input type="hidden" id="longitude" name="longitude">
                <input type="hidden" id="latitude" name="latitude">
                <input type="hidden" id="clientIP" name="clientIP" value="">

            <div class="modal-header">
                <h3 id="myModalLabel">REPORT THE CRIME</h3>
                <p style="margin-top: 0;">Please fill the fields below to report the crime.</p>
            </div>
            <div class="modal-body" style="background-color: white">
                <form>
                        <label style="position:relative; top:20px;" class="cr-label-text">Crime Date & time</label>
                        <div id="datetimepicker" class="input-append date">
                            <input id="txtCrimeDate" name="txtCrimeDate" type="text" class="cr-input-text-half">
                            <span class="add-on">
                                <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                            </span>
                        </div>
                        <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
                        <script type="text/javascript" src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
                        <script type="text/javascript" src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.min.js"></script>
                        <script type="text/javascript" src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.pt-BR.js"></script>

                        <script type="text/javascript">
                            $('#datetimepicker').datetimepicker({
                                pickTime: false,
                                format: 'dd/MM/yyyy',
                                language: 'pt-EN'
                            });
                        </script>

                        <div id="datetimepicker2" class="input-append date">
                            <input id="txtCrimeTime" name="txtCrimeTime" type="text" class="cr-input-text-half" >
                                <span class="add-on">
                                    <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                                </span>
                        </div>

                        <script type="text/javascript">
                            $('#datetimepicker2').datetimepicker({
                                pickDate: false,
                                pick12HourFormat: true,
                                format: 'hh:mm:ss',
                                language: 'pt-EN'
                            });
                        </script>

                    <p style="margin-top:20px">
                        <label class="cr-label-text">Crime Type</label>
                        <select id="txtCategory" name="txtCategory" class="cr-input-text" >
                            <?php
                            $categoryRepository = new CategoryRepository();
                            $categories = $categoryRepository->GetAll();

                            foreach ($categories as $category)
                            {
                                echo "<option value='".$category["Category_ID"]."'>";
                                echo $category["Category_Name"];
                                echo "</option>";
                            }
                            ?>
                        </select>
                    </p>

                    <p>
                        <hr style="border-color: #EEE;" />
                        <label id="locationCaption" class="cr-label-text"><h3>You are going to report crime at:</h3></label>
                        
                        <span id="location" class="cr-label-text"></span>
                        <input type="hidden" id="locationName" name="locationName">
                    </p>

                    <p>
                        <label class="cr-label-text">Crime Description</label>
                        <textarea id="txtDescription" name="txtDescription" maxlength="10000" class="cr-input-text"></textarea>
                    </p>

                    <p>
                        <label class="cr-label-text">Other Detail</label>
                        <textarea id="txtOtherDetail" name="txtOtherDetail" maxlength="10000" class="cr-input-text"></textarea>
                    </p>

                    <div class="modal-footer">
                        <input id="btnSubmit" name="btnSubmit" type="submit"  value="Report Crime" class="cr-btn-search-report">
                        <input id="btnCancel" name="btnCancel" type="button" onclick="hidePopup('popup');" value="Cancel" class="cr-btn-search-report" style="background-color:#162024; color: #FFF;">
                    </div>

                </form>
            </div>

            </form>
        </div>
        <div class="top-bar">
            <h1 style="margin-top:0; float:left;"><a href="/ReportCrime/"><img src="img/Logo_CrimeBusters.png" height="80" /></a></h1>
            <a href="#" data-toggle="modal" data-target="#instructions" class="cr-inst">How to report?</a>
            <a href="about-contribute.html" class="cr-inst">About Project</a>

            <!--<form action="" method="post" class="cr-controls" style="float:right;">-->
            <div class="cr-controls" style="float:right;">
                <label style="display:block;"><strong>Search Map</strong> (to see crime reports or report a crime)</label>
                <input id="address" type="text" value=""  class="cr-location-search">
                <input type="button" value="Search & Report a Crime" onclick="codeAddress()" class="cr-btn-search-report">
            </div>
             <!--</form>-->
         </div>
        <div id="instructions" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <h2>HOW TO REPORT</h2>
        <hr />
        <p>
            <ol>
                <li>Zoom In/Out of the map using your mouse wheel or using the slider on the top left corner of the map.</li>
                <li>Click on the location of the crime</li>
                <li>Fill out the form:
                    <ol style="list-style-type:lower-alpha;">
                        <li>Select the date.</li>
                        <li>Select the time.</li>
                        <li>Select the category.</li>
                        <li>Enter a brief description e.g. Phone Snatched in Saddar more.</li>
                        <li>Enter details to describe what happened.</li>
                        <li>Click “Report Crime” to report the crime.</li>
                    </ol>
                </li>
            </ol>
            <p><strong>NOTE:</strong> After you submit the report, our moderators will review it and it will be publically accessible after is has been approved.<br /><br />
Send us any questions or feed back at <a href="mailto:info@crimebusterspk.com">info@crimebusterspk.com</a><br />
</p>
                </p>
    </div>
        <div style="clear:both;">
                <div id="map-canvas" width="700" height="540" frameborder="0" scrolling="no" marginheight="0"></div>
                <div class="cr-panel">
                    <p style="margin-top: 0px;"><strong>Search Criteria</strong></p>

                        <select id='drpTimeFilter' name='drpTimeFilter' class='cr-predefined-filters'>
                            <option value='0' selected>Today Reported Crimes</option>
                            <option value='1' > This Week Reported Crimes</option>
                            <option value='2' selected>This Month Reported Crimes</option>
                        </select>

                    <form>
                         <?php
                            $categoryRepository = new CategoryRepository();
                            $categories = $categoryRepository->GetAll();
                            foreach ($categories as $category)
                            {
                                $checkBoxID = "chkCategory_".$category["Category_ID"];
                                $postedCategoryID= isset($_POST[$checkBoxID]);
                                $checkBoxElement = "<input type='checkbox' name='".$checkBoxID ."' id='".$checkBoxID."' value='".$category["Category_ID"]. "' checked=checked/> ";

                                //if($category["Category_ID"]==$postedCategoryID)
                                //{
                                //    $checkBoxElement .= " checked=checked";
                                //}

                                $checkBoxElement .= "". $category['Category_Name']."<br>";
                                echo $checkBoxElement;
                            }
                        ?>

                    <input id="btnubmit" value="Apply" name="btnubmit" type="submit" onclick="initialize(); return false;"  class="cr-btn-search-report" />

                    </form>

                    <div class="cr-spur-social">

                    <a href="about-contribute.html" class="com">About the project</a>&nbsp;|&nbsp;
                    <a href="about-contribute.html" class="com">Contribute</a>

                    <hr />

                    <a href="https://twitter.com/CrimeBustersPK"><img src="img/icn-cb-twitter.png" /></a>
                    <a href="https://www.facebook.com/crimebusterspk"><span><img src="img/icn-cb-facebook.png" /></a>
                    <a href="https://github.com/crimebusters/crimebusterspk"><span><img src="img/icn-cb-github.png" /></a>
                    </div>
                </div>
            </div>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.0.min.js"><\/script>')</script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
        <script src="js/bootstrap.js"></script>

    </body>
</html>
