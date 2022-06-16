$('#add-image').click(function(){
    //je récupère le numéro des futurs champs que je vais créer;
    const index = + $('#widgets-counter').val() ;
    console.log(index);
    //je récupère le prototype des entrées
    const tmpl = $('#ad_images').data('prototype').replace(/__name__/g,index);
    $("#ad_images").append(tmpl);
    $('#widgets-counter').val(index + 1);
    //je gère le bouton supprimer
    handleDeeleteButtons();
 });



 function handleDeeleteButtons(){
   $('button[data-action="delete"]').click(function(){
     const target = this.dataset.target;
     $(target).remove();
   });
 }

 function updateCounter(){
   const count = +$('#ad_images div.form-group').length;
   $('#widgets-counter').val(count);
 }
     
      updateCounter();
      handleDeeleteButtons();