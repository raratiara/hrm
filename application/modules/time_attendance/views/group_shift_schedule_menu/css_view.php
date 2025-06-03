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

</style>