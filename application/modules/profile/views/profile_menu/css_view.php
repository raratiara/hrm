<style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f3f5fc;
      margin: 0;
      padding: 30px;
    }

    .dashboard-container {
      max-width: 1000px;
      margin: auto;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .profile-box,
    .summary-box,
    .chart-box {
      background-color: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    /*.profile-box {
      display: flex;
      gap: 20px;
      align-items: center;
    }*/

    .profile-box {
  display: flex;
  gap: 20px;
  align-items: flex-start;
  position: relative; /* penting! */
  min-height: 160px;
}

.app-download {
  position: absolute;
  right: 20px;
  bottom: 10px;
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 13px;
  flex-wrap: wrap;
}

.download-icon {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  font-weight: 500;
  text-decoration: none;
  font-size: 13px;
  color: #2e3267;
  padding: 4px 8px;
  border-radius: 6px;
  background-color: #f3f4f8;
}

.download-icon img {
  width: 16px;
  height: 16px;
}


    .profile-img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #ddd;
    }

    .profile-details {
      display: grid;
      grid-template-columns: repeat(2, auto);
      gap: 5px 30px;
      font-size: 12px;
    }

    .summary-box {
      display: flex;
      justify-content: space-between;
      text-align: center;
      gap: 10px;
    }

    .summary-item {
      flex: 1;
      padding: 10px 15px;
      margin: 0;
      border-radius: 8px;
      background-color: #f7f8fd;
      border: 1px solid #e0e0e0;
      font-size: 13px;
      line-height: 1.2;
      height: 40px;
    }
    

    .summary-item .number {
      font-size: 10px;
      font-weight: bold;
      margin-top: 5px;
    }

    .summary-item .title {
      font-size: 10px;
      margin-top: -5px;
      /*font-weight: bold;
      margin-top: 5px;*/
    }

    .summary-item.highlight {
      background-color: #2e3267;
      color: white;
    }

    .summary-item.yellow {
      background-color: #fddb5c;
      color: #333;
    }


    .tasklist-line {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 5px;
      font-size: 10px;
      gap: 10px;
    }

    .tasklist-line span {
      display: flex;
      gap: 4px;
      align-items: center;
    }

    .divider {
      width: 1px;
      height: 16px;
      background-color: #aaa;
      margin: 0 5px;
    }


    .chart-box {
      height: 200px;
    }



  </style>