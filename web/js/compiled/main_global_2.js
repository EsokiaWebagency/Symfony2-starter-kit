/*
    Put the global js in this file
    The CSS of this file will be apply on all the application
    Please, create a js file for each specific area
*/
/* 
    Created on : Nov 27, 2014, 1:47:29 PM
    Author     : esokia
*/

//execute code after the dom loading
$(document).ready(function(){
    //init tooltips
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })    
    //add a default alert on all the button with class btn-danger
    //message is define in app/Resources/Views/Base.html.twig
   $('.btn-danger').on('click', function(e){
       if(!confirm(globalVars.defaultAlertMessage)){
            e.preventDefault();
       }
    });

    
});