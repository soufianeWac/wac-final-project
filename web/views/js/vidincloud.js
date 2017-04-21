$(document).ready(function(){
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
                <p class="content">${response.listCom.content}</p>
                <b>Posté le : ${response.listCom.created_at}</b>
                <button class="delete-com" data-id="${response.listCom.id}" onclick="deleteCom(${response.listCom.id}); $(this).parent().hide();">suppr</button>
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
          for (var i = 0; i <= l.length; i++) {
            console.log(l[1]);

            var href = "";
            if(coms.session == l[i].user_id)
            {              
              var href = `<button class="delete-com" data-id="${l[i].id}" onclick="deleteCom(${l[i].id}); $(this).parent().hide();">suppr</button>`;
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
    var page = 1;

    if($('.video').length > 10){
      $('#plus-video').css('display','block');
    }else{
      $('#plus-video').css('display','none');
    }

    $('#plus-video').click(function(e){
      page++;
      var id = $(this).data('id');
      $.post('category'+id, {page: page}, function(data){
        var vids = JSON.parse(data);
        $.each(vids, function(com, l){
          for (var i = 0; i < l.length; i++) {
            $('#ajax-content').append(`
              <div class="video">
                <a href="/video${l[i].id}">${l[i].name}</a>
               </div>
            `);
          }
        }); 
      });
    });
});

function deleteCom(idCom)
{
  var id = idCom;
  $.post('delete_com'+id, function(data){
  });
}

function deleteAccount(idUser)
{
  var id = idUser;
  if(window.confirm("Attention : en supprimant votre compte vous n'aurez plus accès ni à votre espace video ni à votre espace cloud. Vos vidéos ne seront plus accessible sur le site."))
  {
   document.location.href = '/delete_usr'+id;
  }
}