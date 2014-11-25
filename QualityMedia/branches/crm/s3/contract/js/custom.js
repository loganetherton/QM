$('document').ready(function(){

	if($('.have-sub').length > 0){
    $('.have-sub ul').hide();
    $('.have-sub .parent').click(function(){
      $(this).parent().children('ul').slideToggle(500,function(){
        if($(this).css('display') == 'none'){
        
          $(this).parent().children('.parent').children('.arr').removeClass('icon3-caret-up').addClass('icon3-caret-down');
        }else{
            $(this).parent().children('.parent').children('.arr').removeClass('icon3-caret-down').addClass('icon3-caret-up');
          
        }
      });

      return false
    });
  }

  if($('.hidecv').length > 0){
    $('.hidecv').click(function(){
      $(this).parent().children('.tw-list').toggle('500',function(){
        if($(this).css('display') == 'none'){
        
          $(this).parent().children('.hidecv').html('Show Conversation');
        }else{
            $(this).parent().children('.hidecv').html('Hide Conversation');
          
        }
      });
      return false;
    })

  }

  if($('.tw-list').length > 0){
    $('.tw-list').hover(function(){
      $(this).find('.rtw').show();
    },function(){
      $(this).find('.rtw').hide();
    })
  }

  if($('.show-more').length > 0){
    $('.show-more').each(function() {
        var $this = $(this);
        $this.attr('title',$this.parent().find('.showsub').html());
        $this.tooltip({
          trigger: 'hover',                    
          html: true,    
          placement:'left',      
          container: $this 
        });
    });       
  }

})