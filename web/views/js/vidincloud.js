$(document).ready(function(){
  //button in unique code form, click for render modal with dotation linked
  $('.valid-com').click(function(e){
      e.preventDefault();
      var serialize = $('#form-commentaire').serializeArray();
      $.ajax({
        url: '/add_com',
        type: 'POST',
        data: serialize,
        dataType: 'json',
        success: function( response ){
          $('.error').empty();
          if (response.access == 'not valid') {
            $('.error').html('<p>'+ response.errors +'</p>');
          }
          else{
            $('#js').prepend(
              `<div class="commentaire">
                <p>${response.listCom.content}</p>
                <b>Posté le : ${response.listCom.created_at}</b>
                <button class="delete-com" data-id="${response.listCom.id}">suppr</button>
              </div>
            `);
          }
        },
        error: function( response ){
          $('.error').html('<p>Erreur serveur : rééssayé plus tard</p>');
        }
      });
  });
  var page = 1;

  $('.plus').click(function(e){
    page++;
    var id = $(this).data('id');
    $.post('video'+id, {page: page}, function(data){
      var coms = JSON.parse(data);

      $.each(coms, function(com, l){
          for (var i = 0; i <= l.length ; i++) {
             var href = "";
            if(coms.session == l[i].user_id)
            {
              var href = `<button class="delete-com" data-id="${l[i].id}">suppr</button>`;
            }
            $('#js').append(`
              <div class="commentaire">
                <p>${l[i].content}</p>
                <b>Posté le : ${l[i].created_at}</b>`+
                href+
              `</div>
            `);
          }
      });
    });
  });

  $('.delete-com').click(function(e){
    e.preventDefault();
    var id = $(this).data('id');
    var com = $(this).parent();
  
    $.post('delete_com'+id, function(data){
      com.hide();
    });
  });
});