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

.modal-content{
    width:1100px;
    margin-left:-250px
}


body {
  font-family: sans-serif;
  padding: 20px;
  overflow-x: auto;
}


#btnGen {
  background-color: #cdd232; 
  border-radius: 0px;
  /*border-color: white;*/
  color: black;
  width: 100px;
  height: 30px;
  
}

.calendar-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

.calendar-grid {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 5px;
}

.day-name {
  font-weight: bold;
  text-align: center;
  background: #f0f0f0;
  padding: 8px 0;
}

.day-box {
  border: 1px solid #ccc;
  padding: 5px;
  min-height: 80px;
  position: relative;
  background-color: #fff;
}

.day-number {
  position: absolute;
  top: 5px;
  right: 5px;
  font-size: 12px;
  font-weight: bold;
  color: #666;
}

.shift-input {
  width: 100%;
  margin-top: 25px;
  font-size: 12px;
}


.accordion {
  /*background: linear-gradient(135deg, #6a11cb, #2575fc);*/
  background: linear-gradient(135deg, #0C0C0C, #575A5B);
  color: white;
  cursor: pointer;
  padding: 8px 6px;
  margin-top: 30px;
  width: 100%;
  border: none;
  outline: none;
  transition: background 0.3s ease, transform 0.2s ease;
  border-radius: 10px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
  font-size: 18px;
  text-align: left;
  display: flex;
  justify-content: space-between;
  align-items: center;
}


.accordion:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0,0,0,0.3);
}

.accordion:after {
  content: '\25BC'; /* Down arrow */
  font-size: 16px;
  transition: transform 0.3s ease;
}

.accordion.active:after {
  transform: rotate(180deg);
}


.panel {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.3s ease;
  background: white;
  padding: 0 24px;
  border-radius: 0 0 10px 10px;
}

.panel p {
  margin: 16px 0;
  color: #333;
}

.panel.show {
  max-height: 400px;
  padding: 16px 24px;
}




table { border-collapse: collapse; margin-top: 10px; min-width: 100%; }
th, td {
  border: 1px solid #ccc;
  padding: 6px;
  text-align: center;
  min-width: 80px;
}

.tblShift th {
  background-color: #e1e5e8;
}


.date-header { font-size: 12px; }
.drop-cell { min-height: 40px; position: relative; }
.shift-btn {
  padding: 4px 8px; margin: 2px; border-radius: 4px;
  font-size: 12px; cursor: grab; color: white;
}
/*.shift1 { background-color: #4CAF50; }
.shift2 { background-color: #2196F3; }
.shift3 { background-color: #cd4141; }*/
/*.assigned {
  background-color: #FF9800;
  color: white;
  padding: 3px 6px;
  border-radius: 4px;
  font-size: 11px;
  margin-top: 2px;
  display: inline-block;
}*/
button { margin: 5px; padding: 6px 10px; }


.btnweek {
  margin-top: 12px;
  padding: 8px 16px;
  /*background-color: #cccccc;*/
  color: black;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}

.btnpilihshift {

  margin-top: 12px;
  padding: 8px 16px;
  background-color: #4187cd;
  color: white;
  border: none;
  border-radius: 16px;
  cursor: pointer;
}


/*MODAL*/
.custom-modal {
  display: none;
  position: fixed;
  z-index: 999;
  /*padding-top: 90px;*/
  top: 10px; width: 300px; height: 20%;
  float: right;
  margin-left:750px;
  /*background-color: rgba(0,0,0,0.6);*/
}

.custom-modal-content {
  background-color: #fff;
  margin: auto;
  padding: 20px;
  border-radius: 12px;
  width: 300px;
  max-width: 400px;
  animation: fadeIn 0.3s ease;
  box-shadow: 0 5px 15px rgba(0,0,0,0.3);
  float: right;
}

.custom-close {
  float: right;
  font-size: 24px;
  font-weight: bold;
  color: #aaa;
  cursor: pointer;
}
#close-shift:hover {
  color: red;
}

.assigned {
  color: white;
  padding: 4px 6px;
  border-radius: 10px;
  font-size: 12px;
  text-align: center;
  cursor: pointer;
  font-weight: bold;
  
}

.shift1 { background-color: #4CAF50; }
.shift2 { background-color: #3F51B5; }
.shift3 { background-color: #cd4141; }

.shift-btn {
  display: inline-block;
  padding: 8px 14px;
  border-radius: 6px;
  font-weight: bold;
  font-size: 14px;
  cursor: grab;
  color: white;
  margin: 4px 6px 12px 0;
}

.shift-btn.shift1 {
  background-color: #4CAF50;
}

.shift-btn.shift2 {
  background-color: #3F51B5;
}

.shift-btn.shift3 {
  background-color: #cd4141;
}

.button-shift {
  margin-top: 12px;
  padding: 8px 16px;
  background-color: crimson;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}
.button-shift:hover {
  background-color: darkred;
}

@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.9); }
  to   { opacity: 1; transform: scale(1); }
}


/*.drop-cell {
  border: 1px dashed #aaa;
  background-color: #f9f9f9;
}*/



</style>