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


body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: #f4f4f4;
  padding: 50px;
}


.accordion {
  /*background: linear-gradient(135deg, #6a11cb, #2575fc);*/
  background: linear-gradient(135deg, #07171f, #309dcd);
  color: white;
  cursor: pointer;
  padding: 16px 24px;
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
  max-height: 800px;
  padding: 16px 24px;
}


</style>