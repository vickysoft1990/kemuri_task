<!DOCTYPE html>
<html lang="en">
   <head>
      <title>Joe Trading</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
      <script type = "text/javascript" src = "https://www.gstatic.com/charts/loader.js"></script>
      <script type = "text/javascript">
         google.charts.load('current', {packages: ['corechart']});     
      </script>
      <style>
         .modal-full-height .modal-bottom {
         width: 90% !important;
         height: 90% !important;
         margin: 10;
         padding: 10;
         }
         #modCon {
         height: 650px;
         min-height: 70%;
         border-radius: 0;
         }
         iframe{
         height: 500px;
         min-height: 80%;
         }
         /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
         .row.content {height: 640px}
         /* Set gray background color and 100% height */
         .sidenav {
         background-color: #f1f1f1;
         height: 100%;
         }
         /* On small screens, set height to 'auto' for the grid */
         @media screen and (max-width: 767px) {
         .row.content {height: auto;} 
         }
         .list-group{
         max-height: 300px;
         margin-bottom: 10px;
         overflow:scroll;
         -webkit-overflow-scrolling: touch;
         }
         .dropdown-menu{
         max-height: 10px;
         overflow-y: auto;
         }  
         .error{
          color:red;
         }
      #loader{
        position: fixed;
        top: 50%;
        left: 50%;
        width: 100%;
        height: 100%;
        z-index: 9999;
      }
      </style>
   </head>
