<style type="text/css">
[class*="col-"] .chosen-container {
    width:98%!important;
}
[class*="col-"] .chosen-container .chosen-search input[type="text"] {
    padding:2px 4%!important;
    width:90%!important;
    margin:5px 2%;
}
[class*="col-"] .chosen-container .chosen-drop {
    width: 100%!important;
}



#fldashemp {
  width: 120px;
  height: 20px;
}


body {
  font-family: 'Segoe UI', sans-serif;
  /*background-color: #f5f7fa;*/
 /* padding: 40px;*/
}
.chart_monthly_att_summ {
  /*width: 50%;*/
  /*max-width: 400px;*/
  /*margin: auto;*/
  background: #fff;
  padding: 10px;
  border-radius: 12px;
  box-shadow: 0 10px 20px rgba(0,0,0,0.05);
  left: 20px;
  width: 300px;
  float: left; /* atau gunakan display: inline-block */
}
h2 {
  text-align: left;
  font-size: 16px;
  margin-bottom: 20px;
  color: #333;
}


.chart_att_statistic {
  /*width: 90%;*/
  max-width: 450px;
  margin: auto;
  background: #fff;
  padding: 15px;
  border-radius: 15px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  width: 400px;
  float: left; /* atau gunakan display: inline-block */
}


.dashboard {
  width: 1000px;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  /*gap: 10px;*/
  column-gap: 10px; /* jarak kanan kiri antar box */
  row-gap: 10px;    /* jarak atas bawah antar box */
  padding: 10px;
}

.info-box {
  background-color: #ffffff;
  border-radius: 16px;
  padding: 10px 14px;
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
  transition: transform 0.2s;
  /*width: 100px;*/
}

.info-box:hover {
  transform: translateY(-4px);
}

.info-title {
  font-size: 12px;
  color: #888;
  margin-bottom: 8px;
}

.info-value {
  font-size: 14px;
  font-weight: bold;
  color: #333;
}

.info-icon {
  float: right;
  font-size: 25px;
  color: #4e73df;
}

.info-footer {
  font-size: 10px;
  color: #999;
  margin-top: 10px;
}


.chart_empbydeptgender {
  /*width: 50%;*/
  max-width: 450px;
  margin: auto;
  background: #fff;
  padding: 15px;
  border-radius: 12px;
  box-shadow: 0 10px 20px rgba(0,0,0,0.05);
  left: 20px;
  width: 400px;
  float: left; /* atau gunakan display: inline-block */
}

.chart_empbygen {
  width: 300px;
  margin: auto;
  background: #fff;
  padding: 15px;
  border-radius: 16px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
  text-align: center;
}

.chart_attpercentage {
  /*width: 50%;*/
  max-width: 450px;
  margin: auto;
  background: #fff;
  padding: 15px;
  border-radius: 12px;
  box-shadow: 0 10px 20px rgba(0,0,0,0.05);
  left: 20px;
  width: 400px;
  float: left; /* atau gunakan display: inline-block */
}


#att_percentage {
  /*width: 150px;
  height: 100px;
  padding: 2px;*/
  /*max-width: 450px;
  margin: auto;
  background: #fff;
  padding: 15px;
  border-radius: 12px;
  box-shadow: 0 10px 20px rgba(0,0,0,0.05);
  left: 20px;
  width: 400px;
  float: left;*/ /* atau gunakan display: inline-block */
}


.dashboard-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr); 
  grid-template-rows: repeat(7, 1fr); 
  grid-auto-rows: auto;
  gap: 10px;
  padding: 20px;
  background-color: #f4f6f9;
}

.box {
  background-color: #ffffff;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  display: flex;
  justify-content: center;
  align-items: center;
  font-weight: bold;
  color: #333;
  text-align: center;
  padding: 16px;

  position: relative;         /* diperlukan jika pakai absolute di dalam */
 
}

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
  margin-bottom: 8px;
  /*font-size: 12px;
  color: #888;
  margin-bottom: 8px;*/
}

.box-value {
  margin-top: 12px;
  font-size: 14px;
  font-weight: bold;
  color: #333;
}



/* Ukuran tinggi dan lebar khusus */

.box-1 { height: 70px; }
.box-2 { height: 70px; }
.box-3 { height: 70px; }
.box-4 { height: 70px; }
.box-5 { /*height: 70px;*/ 
  grid-row: span 2;
}
.box-6 { /*height: 70px;*/
  /*height: 160px;*/
  grid-column: span 2; 
  grid-row: span 3; 
}
.box-7 { height: 70px; }
.box-8 { height: 70px; }
.box-9 { height: 70px; }
.box-10 { height: 70px; }
.box-11 { /*height: 70px; */
  grid-row: span 2;
}
.box-12 { height: 70px; 
  
}
.box-13 { height: 70px; }
.box-14 {/* height: 70px; */
  grid-column: span 2; 
  grid-row: span 3; 
}
.box-15 { /*height: 70px; */
  grid-column: span 2; 
  grid-row: span 3;
}
.box-16 { /*height: 70px; */
  grid-row: span 3;
}



/*.dashboard-flex {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  gap: 16px;
  height: 500px; 
  width: 1000px;
  flex: 0 0 calc((100% - 64px) / 5); 
}*/

/*.column {
  flex: 1;
  display: flex;
  flex-direction: column;
}*/

/*.box {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  margin-bottom: 10px;
  margin-top: 10px;
  padding: 16px;
  text-align: center;
  font-weight: bold;
}


.box-1 {
  height: 70px;
  width: 350px;
}
.box-2 {
  height: 70px;
  
}
.box-3 {
  height: 70px;
  width: 200px;
}
.box-4 {
  height: 70px;
  width: 200px;
}
.box-5 {
  height: 120px;
  width: 200px;
}

.box-6 {
  
  height: 170px;

  flex: 0 0 190.4px;
}
.box-7 {
  height: 70px;
 
}
.box-8 {
  height: 70px;
  
}
.box-9 {
  height: 70px;
 
}
.box-10 {
  height: 120px;
  
}
.box-11 {
  
  height: 170px;
 
}
.box-12 {
  height: 70px;
  
}
.box-13 {
  height: 70px;
 
}
.box-14 {
  height: 70px;
  
}
.box-15 {
  flex-grow: 1;

}
.box-16 {
  height: 170px;

  flex: 0 0 390.4px;
}*/


</style>