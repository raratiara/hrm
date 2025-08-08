
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- jQuery + Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>





<style type="text/css">
	html, body {
      margin: 0; padding: 0; height: 100%; font-family: Arial, sans-serif;
      background-color: #1f2d3d; overflow: hidden;
    }


	.video-toggle {
      position: fixed;
      top: 50px;
      right: 20px;
      width: 25px;
      height: 25px;
      background-color: #34495e;
      color: #ecf0f1;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      z-index: 1300;
      font-size: 14px;
      box-shadow: 0 0 8px rgba(0,0,0,0.3);
      transition: background-color 0.2s;
    }
    .video-toggle:hover {
      background-color: #3b5998;
    }

    .video-container {
      position: fixed;
      top: 70px;
      right: 10px;
      display: flex;
      justify-content: space-around;
      background-color: #f8fafb;
      padding: 5px 10px;
      box-sizing: border-box;
      border-radius: 10px;
      box-shadow: 0 0 6px rgba(0,0,0,0.15);
      z-index: 1000;
      transition: left 0.3s ease;
      width: 250px;
      height: 120px;
    }

    .video-container.collapsed {
      left: 10px;
    }

    .video-container.hidden {
      display: none !important;
    }


    .info-box {
      position: fixed;
      top: 70px;
      right: 10px;
      background: white;
      padding: 10px;
      border: 2px solid #444;
      border-radius: 8px;
      z-index: 1000;
      width: 220px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }

    .info-box img {
      width: 20%;
      border-radius: 4px;
    }

    .info-box h4 { margin: 10px 0 5px; }
    .info-box p { margin: 0; font-size: 14px; }


    .box-title {
      /*font-weight: bold;*/
      font-size: 10px;
      color: #888;
      position: absolute;
      top: 6px;
      left: 8px;
      /*background: white;*/
      padding: 2px 8px;
      border-radius: 4px;
      margin-bottom: 14px;
      /*font-size: 12px;
      color: #888;
      margin-bottom: 8px;*/
    }

    .box-value {
      margin-top: 20px;
      font-size: 10px;
      font-weight: bold;
      color: #333;
      width: 200px;
    }

    .box-title2 {
     
      font-size: 10px;
      color: #888;
      position: absolute;
      top: 60px;
      left: 8px;
      
      padding: 2px 8px;
      border-radius: 4px;
      margin-bottom: 14px;
      
    }

    
    .boxInputPeriod {
      margin-top: 70px;
    }


    .video-container h4 { margin: 10px 0 5px; }
    .video-container p { margin: 0; font-size: 14px; }


    .select2-container--default .select2-selection--single {
      height: 35px;
      padding: 8px 12px;
      border-radius: 10px;
      border: 1px solid #ccc;
      font-size: 10px;
      background-color: #f9f9f9;
      transition: all 0.3s;
    }

    .select2-container--default .select2-selection--single:hover {
      border-color: #3b82f6;
      box-shadow: 0 0 5px rgba(59, 130, 246, 0.3);
    }

    .select2-container--default .select2-results__option--highlighted {
      background-color: #3b82f6 !important;
      color: white;
    }

</style>



<div class="video-toggle" onclick="toggleVideos()" title="Toggle Videos">
  <i class="fa-solid fa-sort"></i>
</div>


<div class="video-container" id="videoContainer">
    <div class="box-title">Select Employee</div>
    <div class="box-value">
      <!-- <?=$selemp?> -->
     
      <select multiple style="width: 100%;" id="fldashemp" name="fldashemp[]">
        <!-- <option value="all">-- ALL --</option> -->
        <?php
        foreach($master_emp as $row){
          ?>
          <option value="<?=$row->id?>"><?=$row->full_name?></option>
          <?php
        }
        ?>
      </select>
    </div>
    <div class="box-title2">Select Period
      <input type="text" class="form-control" id="fldashperiod" name="fldashperiod">
    </div>
    

    <div class="info-footer"></div>
</div>



<!-- Peta -->
<div id="map" style="height: 80vh; width: 100%;"></div> 




<script type="text/javascript">
  $(document).ready(function() {
    
      $('#fldashemp').select2({
        placeholder: "",
        allowClear: true,
        theme: "default"
      });

  });

	 // Video toggle (show/hide video container)
  function toggleVideos() {
  	//document.getElementById("videoContainer").style.display = "block";
    var container = document.getElementById('videoContainer');
    if (container.classList.contains('hidden')) { 
      container.classList.remove('hidden');
    } else { 
      container.classList.add('hidden');
    }
  }

</script>






