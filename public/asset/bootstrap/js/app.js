$(function() {

  //gestion de la vues des tricks
  var j = 4 ;
  var vignettes = $('#tricks-title div.trick').length;
  $(document).ready(function() {
    let progress = ((4 * 100) / vignettes);
    $("#tricksBarr").css({'width': progress +'%'});
    $('.hidden-tricks').slice(0,4).css({
      display:'inherit',
    });
    barrHiddenTricks(vignettes,4);
  });

  $('#loadMoreTrick').click(function(){
      j += 4 ;
     $('.hidden-tricks').slice(4,j).css({
        display:'inherit',
      });
      const progress = ((j * 100) / vignettes);
      $("#tricksBarr").css({'width': progress +'%'});
    if (j >= vignettes){
      $("#loadMoreTrick").hide('slow');
      $("#loadLessTrick").show('slow');
    }else{
      $("#loadLessTrick").show('slow');
    }
    barrHiddenTricks(vignettes,4);
  })

  $('#loadLessTrick').click(function() {
    j -= 4 ;
    $('.hidden-tricks').slice(j,vignettes).css({
      display:'none',
    })
    const progress = ((j * 100) / vignettes);
    $("#tricksBarr").css({'width': progress +'%'});

    if (4 < j) {
      $("#loadLessTrick").show('slow');
      $("#loadMoreTrick").show('slow');
    }else{
      $("#loadMoreTrick").show('slow');
      $("#loadLessTrick").hide('slow');
    }
    barrHiddenTricks(vignettes,4);
  })

  //gestion de la vues des commantaires
  var c = 10 ;
  var comments = $('#comments-title div.comment').length;
  console.log(comments);
  $(document).ready(function() {
    let progress = ((10 * 100) / comments);
    $("#tricksBarr").css({'width': progress +'%'});
    $('.hidden-comments').slice(0,10).css({
      display:'inherit',
    });
    barrHidden(comments,10);
  });

  $('#loadMoreC').click(function(){
      c += 10 ;
     $('.hidden-comments').slice(10,c).css({
        display:'inherit',
      });
      const progress = ((c * 100) / comments);
      $("#tricksBarr").css({'width': progress +'%'});
    if (c >= comments){
      $("#loadMoreC").hide('slow');
      $("#loadLessC").show('slow');
    }else{
      $("#loadLessC").show('slow');
    }
    barrHidden(comments,10);
  })

  $('#loadLessC').click(function() {
    c -= 10 ;
    $('.hidden-comments').slice(c,comments).css({
      display:'none',
    })
    const progress = ((c * 100) / comments);
    $("#tricksBarr").css({'width': progress +'%'});

    if (10 < c) {
      $("#loadLessC").show('slow');
      $("#loadMoreC").show('slow');
    }else{
      $("#loadMoreC").show('slow');
      $("#loadLessC").hide('slow');
    }
    barrHidden(comments,10);
  })

  function barrHidden(min,max){
    if(min <= max){
      $(".module_progress_comments").css({'display': 'none',});
    }
  }
function barrHiddenTricks(min,max){
    if(min <= max){
      $(".module_progress_tricks").css({'display': 'none',});
    }
  }

  //Gestion de la vue des comments dans la vue SHOW
  $('#loadMoreComment').click(function(){
    $('.hidden-tricks').css({
      display:'flex',

    })
    $("#loadMoreComment").hide('slow');
    $("#loadLessComment").show('slow');
  })

  $('#loadLessComment').click(function() {
    $('.hidden-tricks').css({
      display:'none'
    })
    $("#loadMoreComment").show();
    $("#loadLessComment").hide();
  })
  //Gestion de la vue des medias format mobile
  $('#seeMedia').click(function(){
    $('#carMedia').toggle('slow');
  })

  $('#carousel2').carousel()
  /**
   * Smooth scrolling to a specific element
   **/
  function scrollTo( target ) {
    if( target.length ) {
      $("html, body").stop().animate( { scrollTop: target.offset().top }, 700);
    }
  }

  $("#downC").click(function(){
    scrollTo( $('#comments-title') );
  });
  $("#upC").click(function(){
    scrollTo( $('#comments-title') );
  });
  $("#down").click(function(){
    scrollTo( $('#tricks-title') );
  });
  $("#up").click(function(){
    scrollTo( $('#tricks-title') );
  });
  $("#encreComments").click(function(){
    scrollTo( $('#divComment') );
  });
  /* --------------------------------------------------------------------------------- */

  /*  /* Get More Comments*/

  /* --------------------------------------------------------------------------------- */


  $("#more").click(function(){
    var toto =  $("#ulComment:nth-child(3)");
     $("html, body").stop().animate( { scrollTop: toto.offset().top }, 700);
  });


  $(function () {
    $('.apoper').popover({
      container: '.row'
    });
  })
  /* --------------------------------------------------------------------------------- */

  /*  /* Get path File and replace placeholder input with it */

  /* --------------------------------------------------------------------------------- */
  $(document).on('load', '#tricks_file', function(event){
    $(this).value(event.target.files[0].name);
  })
  $(document).on('change', '.custom-file-input', function(event){
    $(this).next('.custom-file-label').html(event.target.files[0].name);
  })
  $(document).on('change', '#tricks_file', function(event){
    $('#mainImage').hide();
    scrollTo( $('#titre'));
  })

  /* --------------------------------------------------------------------------------- */

  /*  /* Get alt and replace placeholder with it */

  /* --------------------------------------------------------------------------------- */
  var i = 0;
  $(".img-trick").each(function () {
    var alt = $(this).attr("alt");
    $("label[for=trick_images_" + i + "_image]").text(alt);
    i++;
  });

  /* --------------------------------------------------------------------------------- */

  /*  /* Trick collection */

  /* --------------------------------------------------------------------------------- */

  function displayCounter() {
    const countCategory = +$("#tricks_category option:selected").length;
    if (countCategory >= 3) {
      $("#tricks_category ").find('option:not(:selected)').hide();
    } else {
      $("#tricks_category").find('option:not(:selected)').show();
    }
    if (countCategory) {
      const progress = countCategory * 33.33
      $("#barCategory").css({'width': progress +'%',
      })
      if(progress > 99){
        $("#barCategory").addClass('bg-success');
        $("#barCategory").removeClass('bg-warning');
      }else{
        $("#barCategory").addClass('bg-warning');
      }
    }


    const countImage = +$("#tricks_media div.form-group").length;
    if (countImage >= 4) {
      $("#add-image").hide();
    } else {
      $("#add-image").show();
    }
    if (countImage) {
        const progress = countImage * 25
        $("#barImage").css({'width': progress +'%',
        })
        if(progress > 99){
          $("#barImage").addClass('bg-success');
          $("#barImage").removeClass('bg-warning');
        }else{
            $("#barImage").addClass('bg-warning');
        }
    }else{
      $("#barImage").css({'width': 0,})
    }

    const countVideo = +$("#tricks_videos div.form-group").length;
    if (countVideo >= 4) {
      $("#add-video").hide();
    } else {
      $("#add-video").show();
    }

    if (countVideo) {
      const progress = countVideo * 25
      $("#barVideo").css({'width': progress +'%',
      })
      if(progress > 99){
        $("#barVideo").addClass('bg-success');
        $("#barVideo").removeClass('bg-warning');
      }else{
        $("#barVideo").addClass('bg-warning');
      }
    }else{
      $("#barVideo").css({'width': 0,})
    }
  }


  $("#tricks_category").click(function () {
    const index = +$("#category-counter").val();
    $("#category-counter").val(index + 1);
    displayCounter();
  });

  function updateCounterCategory() {
    const count = +$("#tricks_category option:selected").length;
    $("#category-counter").val(count);
  }
  function updateCounterImage() {
    const count = +$("#tricks_media div.form-group").length;
    $("#image-counter").val(count);
  }

  function updateCounterVideo() {
    const count = +$("#tricks_videos div.form-group").length;
    $("#video-counter").val(count);
  }

  function handleDeleteButtons() {
    $("button[data-action='delete']").click(function () {
      const target = this.dataset.target;
      $(target).remove();
      updateCounterImage();
      updateCounterVideo();
      displayCounter();
    });
  }

  $("#add-image").click(function () {
    const index = +$("#image-counter").val();
    const tmpl = $("#tricks_media").data("prototype").replace(/__name__/g, index);
    $("#tricks_media").append(tmpl);
    $("#image-counter").val(index + 1);
    handleDeleteButtons();
    displayCounter();
  });
  $("#add-video").click(function () {
    const index = +$("#video-counter").val();
    const tmpl = $("#tricks_videos").data("prototype").replace(/__name__/g, index);
    $("#tricks_videos").append(tmpl);
    $("#video-counter").val(index + 1);
    handleDeleteButtons();
    displayCounter();
  });

  displayCounter();
  updateCounterCategory();
  updateCounterVideo();
  updateCounterImage();
  handleDeleteButtons();
  //--------------------------------------------------------------------
  // CONFIRMATION DE SUPRESSION D'UN COMMENTAIRE
  //-------------------------------------------------------------------
  $('.confSupCom').on('click', function(event){
    const titre = $(this).attr('data-value');
    return(confirm('"'+titre+'", Voulez-vous réellement supprimer ce commentaire ?'));
  });
  //--------------------------------------------------------------------
  // CONFIRMATION DE SUPRESSION D'UN TRICKS
  //-------------------------------------------------------------------
  $('.confSupTrick').on('click', function(event){
    const titre = $(this).attr('data-value');
    return(confirm('"'+titre+'", Voulez-vous réellement supprimer ce Tricks?'));
  });
//--------------------------------------------------------------------
  // CONFIRMATION DE SUPRESSION D'UN UTILISATEUR
  //-------------------------------------------------------------------
  $('.confSupUser').on('click', function(event){
    const titre = $(this).attr('data-value');
    return(confirm('"'+titre+'", Voulez-vous réellement supprimer ce profil utilisateur?'));
  });

});
