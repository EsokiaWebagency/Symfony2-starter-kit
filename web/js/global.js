/*
    Put the global js in this file
    The CSS of this file will be apply on all the application
    Please, create a js file for each specific area
*/
/* 
    Created on : Nov 27, 2014, 1:47:29 PM
    Author     : esokia
*/

/**
 * Create embedded form from their prototypes
 * Feel free to modify this code to meet your needs
 * 
 * @param {obj} $container objet Jquery de la div du prototype du formulaire
 */


function createMultiFormWidgets($container){
    

    // Add link to add a new item
    var $addLink = $('<a href="#" class="btn btn-default add-option">'+$container.attr('data-add-text')+'</a>');
    $container.append($addLink);

    // add field when click on the add link
    $addLink.click(function(e) {
      addForm($container);
      e.preventDefault(); // prevent # in URL
      return false;
    });

    // iterator
    var index = $container.find(':input').length;

    // Auto add first field (if no field exist).
    if (index == 0) {
      addForm($container);
    } else {
      // for each already existing embedded form, add a delete Link
      $container.children('div').each(function() {
        addDeleteLink($(this));
      });
    }

    // function to add the form
    function addForm($container) {
      // in the content of « data-prototype », we replace :
      // -  "__name__label__" by the field label
      // -  "__name__" by the field number
      var $prototype = $($container.attr('data-prototype').replace(/__name__label__/g, $container.attr('data-increment-text') + (index+1))
          .replace(/__name__/g, index));

      // add delete linkk to prototype
      addDeleteLink($prototype);

      // add the modified prototype at the end of the <div>
      $container.find('.add-option:eq(0)').before($prototype);

      // increment
      index++;
    }

    // Add delete link to prototype
    function addDeleteLink($prototype) {
      // link creation
      $deleteLink = $('<a href="#" class="btn btn-danger delete-option">'+$container.attr('data-delete-text')+'</a>');

      // add the link
      $prototype.append($deleteLink);

      // add event listener on click
      $deleteLink.click(function(e) {
        $prototype.remove();
        e.preventDefault(); // prevent # in URL
        return false;
      });
    }

}







//execute code after the dom loading
$(document).ready(function(){
    //init tooltips
    $(function () {
      $('[data-toggle="tooltip"]').tooltip();
    });
    
    //bind embedded forms widget
    $('div[data-prototype]').each(function(){
            createMultiFormWidgets($(this));
    });
    
    
    
    //add a default alert on all the button with class btn-danger
    //message is define in app/Resources/Views/Base.html.twig
   $('.btn-danger').on('click', function(e){
       if(!confirm(globalVars.defaultAlertMessage)){
            e.preventDefault();
       }
    });

    
});