/*
    Put the js related to the admin inteface in this file
    Please, create a js file for each specific area
*/
/* 
    Created on : Dec 1, 2014, 1:47:29 PM
    Author     : esokia
*/

//execute code after the dom loading
$(document).ready(function(){
    

  
    
     //add a confirm to the reset action on the user list 
    //message is define in app/Resources/Views/Base.html.twig
   $('.reset-user-password').on('click', function(e){
       var message = $(this).attr('data-confirm');
       var form = $(this).closest('form');
       if(confirm()){
            form.submit();
       }
    });
    
    
    
    
});