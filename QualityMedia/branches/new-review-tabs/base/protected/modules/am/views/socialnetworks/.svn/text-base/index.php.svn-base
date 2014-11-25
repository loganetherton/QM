<link href="<?php echo $this->resourceUrl('s3/css/token-input.css', 'local'); ?>" rel="stylesheet" />
<link href="<?php echo $this->resourceUrl('s3/css/token-input-facebook.css','local'); ?>" rel="stylesheet" />
<div ui-view></div>
<script type="text/javascript">
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

  if($('.fb-list').length > 0){
    $('.fb-list').hover(function(){
      $(this).find('.fbpop').show()
    },function(){
      $(this).find('.fbpop').hide()
    })
  }
  if($('.tw-list').length > 0){
    $('.tw-list').hover(function(){
      $(this).find('.fbpop').show()
    },function(){
      $(this).find('.fbpop').hide()
    })
  }

  if($('.post-btn').length > 0){
    $('.post-btn').click(function(){
      $('.loading').show();
      setTimeout(function(){
        $('.loading').hide();
        $('.myalert').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> Your Alert message here</div>');
        //if error 
        //$('.myalert').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Error!</strong> Your Alert message here</div>');
        $('.myalert .alert').show();
      },1000);
      return false;
    })
  }


  if($('.rtw').length > 0){
    $('.rtw').click(function(){
      confirm_tw();
      return false;
    })
  }

  $("[data-toggle=tooltip]").tooltip();
  $("[rel=tooltip]").tooltip();

   $("#client-sample").tokenInput('<?php echo $this->createUrl('socialNetworks/searchClient'); ?>',{theme: "facebook",
                tokenLimit: 1,
                onAdd: function(addedObject){ 
                    var scope = angular.element(document.getElementById('tools')).scope();
                    scope.selectedClient = addedObject;
                    scope.$apply();
                    window.scope=scope;
                },
                onDelete: function(){
                    var scope = angular.element(document.getElementById('tools')).scope();
                    scope.selectedClient = {};
                    scope.$apply();
                }
            });

  function confirm_tw(){
    bootbox.dialog({
      message: "Do you want to retweet this to your followers?",
      title: "Confirm",
      buttons: {
        success: {
          label: "Retweet",
          className: "btn-success",
          callback: function() {
            $('.loading').show();
            setTimeout(function(){
              $('.loading').hide();
              $('.myalert').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> Your Alert message here</div>');
              //if error 
              //$('.myalert').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Error!</strong> Your Alert message here</div>');
              $('.myalert .alert').show();
            },1000);
          }
        },
        main: {
          label: "Cancel",
          className: "btn-primary",
          callback: function() {
            return true;
          }
        }
      }
    });
  }
  
  
  var socialNetworks = angular.module('socialNetworks', ['ui.router']);
  
  socialNetworks.config(function($stateProvider, $urlRouterProvider) {
    $stateProvider
        .state('twitter',{
            url: 'twitter/:twitterUserId',
            templateUrl: function() {
                    return '<?php echo $this->createUrl('socialNetworks/partial/name/twitterDash'); ?>';
            },
            controller: 'twitterController'
        })
        .state('facebook',{
            url: 'facebook',
            templateUrl: '<?php echo $this->createUrl('socialNetworks/partial/name/facebookDash'); ?>',
            controller: 'facebookController'
        })
  });
  
  socialNetworks.controller('twitterController', function($stateParams, $scope, $http){
        var twitterUserId = $stateParams.twitterUserId;
        if(twitterUserId){
            var tweetsResource = $http.get('<?php echo $this->createUrl('socialNetworks/getTwitterUpdates') ?>');
            tweetsResource.success(function(data){
                $scope.tweets = data;
            });
            window.scope = $scope;
        }
  });
  
  socialNetworks.controller('facebookController', function($scope){
  });
  
  socialNetworks.controller('parentController', function($scope){
      window.$scope = $scope;
  });
  
  socialNetworks.filter('parseDate', function() {
        return function(dateString) {
            return new Date(dateString);
        };
    });
</script>