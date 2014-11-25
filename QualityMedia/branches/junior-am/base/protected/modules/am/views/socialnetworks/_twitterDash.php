<ul class="nav nav-tabs parent-tb">
    <li class="active">
        <a href="#">Main</a>
    </li>					  
    <li>
        <a href="#">Scheduled Posts</a>
    </li>					  
    <li>
        <a href="#">Brand Monitoring</a>
    </li>					  
    <li>
        <a href="#">Friends</a>
    </li>					  
    <li>
        <a href="#">Analytics</a>
    </li>					  
</ul>
<div class="row-fluid parent-mc" id="main-content">
    <div class="span12" id="ch-content">
        <div class="top-section-cc">
            <div class="span8">
                <div class="pull-left img-user">
                    <img src="img/dummy-user2.png" class="img-rounded">	
                </div>
                <div class="pull-left">
                    <h2>{{ tweet.user.name }}</h2>
                    <a href="#">@WestcoastTires</a>
                </div>
            </div>
            <div class="span4">
                <span class="loading pull-left"><img src="img/loading.gif"> Please Wait...</span>
                <div class="btn-group pull-right">
                    <a class="btn large post-btn"><img src="img/ico-tweet.png" style="width:12px;margin-right:5px;position:relative;top:-2px">Post Tweet</a>
                    <a class="btn large"><img src="img/ico-create2.png" style="width:12px;margin-right:5px;position:relative;top:-2px">Scheduled</a>

                </div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="child-content">
            <div class="sort-cc pull-right">
                <span class="labelcc">Sort By </span>
                <div class="btn-group">
                    <a class="btn small active">Post Date</a>
                    <a class="btn small">New Comments</a>

                </div>
            </div>
            <ul class="nav nav-tabs parent-tb2">
                <li class="active">
                    <a href="#">Wall Posts &nbsp; <img src="img/icon-shout.png"> &nbsp;  <span class="badge badge-important">12</span> </a>
                </li>					  
                <li>
                    <a href="#">Direct Messages</a>
                </li>					  

            </ul>

            <div class="row-fluid" id="main-content2" ng-repeat="tweet in tweets">
                <div class="container-padding">
                    <div class="datecc">
                        <div class="month">{{ tweet.created_at | parseDate | date:'MMM' }}</div>
                        <div class="day">{{ tweet.created_at | parseDate | date:'dd' }}</div>
                    </div>
                    <div class="content span10">
                        <div class="infocc">
                            <span class="name">{{ tweet.user.name }}</span> &nbsp; &nbsp; 
                            <span class="date">{{ tweet.created_at | parseDate | date:'MMM dd, yyyy - hh:mm a'}}</span>
                        </div>
                        <div class="details">
                            {{ tweet.text }}
                        </div>
                        <div class="infocc2">
                            <a href="#">(12)</a> <span class="gray">Favorited This</span> &nbsp; &nbsp; 
                            <a href="#">View Conversation (3)</a>
                        </div>
                    </div>
                    <div class="span2">
                        <div class="btn-group pull-right" style="padding:10px 0px;">												  
                            <a class="btn fav fav-on" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Favorite"><i class="icon3-star"></i></a>
                            <a class="btn rtw retw-on" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Retweet"><i class="icon3-retweet"></i></a>												  
                            <a class="btn" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Delete"><i class="icon3-trash"></i></a>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="container-padding child-comment">
                    <div class="users span3">
                        <img src="img/sep.png" class="sep visible-desktop">
                        <div class="img pull-left">
                            <img src="img/dummy-user3.png">
                        </div>
                        <div class="details pull-left">
                            <div class="name">@ericyoung</div>
                            <div class="date">Aug 29, 2013 - 8:30 pm</div>
                        </div>

                    </div>
                    <div class="span9">
                        <div class="message span7">
                            Cras non rutrum justo, sed hendrerit felis. Quisque bibendum placerat feugiat. Pellentesque rutrum leo non diam pretium henhendrerit drerit?
                        </div>
                        <div class="span5">
                            <div class="pull-right" style="padding:10px 0px;">
                                <div class="btn-group pull-left" style="margin-right:5px">
                                    <a class="btn rtw retw-on" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Retweet"><i class="icon3-retweet"></i></a>
                                    <a class="btn fav fav-on" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Favorite"><i class="icon3-star"></i></a>
                                    <a class="btn" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Mention"><img src="img/ico-exc.png" style="position:relative;top:-2px"></a>
                                    <a class="btn" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Delete"><i class="icon3-trash"></i></a>
                                </div> 
                                <a href="#" class="btn pull-left btn-primary reply"><i class="icon3-reply"></i> Reply</a>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="container-padding child-comment">
                    <div class="users span3">
                        <img src="img/sep.png" class="sep visible-desktop">
                        <div class="img pull-left">
                            <img src="img/dummy-user3.png">
                        </div>
                        <div class="details pull-left">
                            <div class="name">@ericyoung</div>
                            <div class="date">Aug 29, 2013 - 8:30 pm</div>
                        </div>

                    </div>
                    <div class="span9">
                        <div class="message span7">
                            Cras non rutrum justo, sed hendrerit felis. Quisque bibendum placerat feugiat. Pellentesque rutrum leo non diam pretium henhendrerit drerit?
                        </div>
                        <div class="span5">
                            <div class="pull-right" style="padding:10px 0px;">
                                <div class="btn-group pull-left" style="margin-right:5px">
                                    <a class="btn rtw" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Retweet"><i class="icon3-retweet"></i></a>
                                    <a class="btn fav" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Favorite"><i class="icon3-star"></i></a>
                                    <a class="btn" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Mention"><img src="img/ico-exc.png" style="position:relative;top:-2px"></a>
                                    <a class="btn" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Delete"><i class="icon3-trash"></i></a>
                                </div> 
                                <a href="#" class="btn pull-left btn-primary reply"><i class="icon3-reply"></i> Reply</a>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="container-padding child-comment">
                    <div class="users span3">
                        <img src="img/sep.png" class="sep visible-desktop">
                        <div class="img pull-left">
                            <img src="img/dummy-user3.png">
                        </div>
                        <div class="details pull-left">
                            <div class="name">@ericyoung</div>
                            <div class="date">Aug 29, 2013 - 8:30 pm</div>
                        </div>

                    </div>
                    <div class="span9">
                        <div class="message span7">
                            Cras non rutrum justo, sed hendrerit felis. Quisque bibendum placerat feugiat. Pellentesque rutrum leo non diam pretium henhendrerit drerit?
                        </div>
                        <div class="span5">
                            <div class="pull-right" style="padding:10px 0px;">
                                <div class="btn-group pull-left" style="margin-right:5px">
                                    <a class="btn rtw" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Retweet"><i class="icon3-retweet"></i></a>
                                    <a class="btn fav" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Favorite"><i class="icon3-star"></i></a>
                                    <a class="btn" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Mention"><img src="img/ico-exc.png" style="position:relative;top:-2px"></a>
                                    <a class="btn" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Delete"><i class="icon3-trash"></i></a>
                                </div> 
                                <a href="#" class="btn pull-left btn-primary reply"><i class="icon3-reply"></i> Reply</a>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>	
    <div class="row-fluid parent-mc" id="main-content3">
        <div class="container-padding">
            <div class="datecc">
                <div class="month">Aug</div>
                <div class="day">19</div>
            </div>
            <div class="content span10">
                <div class="infocc">
                    <span class="name">Westcoast Tires &amp; Services</span> &nbsp; &nbsp; 
                    <span class="date">Aug 29, 2013 - 8:30 pm</span>
                </div>
                <div class="details">First time customers get 40% off and a free carwash. This promo ends Nov 11. Come visit us today! Aenean condimentum at sapien a imperdiet. Cras non rutrum justo, sed hendrerit felis. Quisque bibendum placerat feugiat. 
                </div>
                <div class="infocc2">
                    <a href="#">(12)</a> <span class="gray">Favorited This</span> &nbsp; &nbsp; 
                    <a href="#">View Conversation (3)</a>
                </div>
            </div>
            <div class="span2">
                <div class="btn-group pull-right" style="padding:10px 0px;">												  
                    <a class="btn fav" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Favorite"><i class="icon3-star"></i></a>
                    <a class="btn rtw" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Retweet"><i class="icon3-retweet"></i></a>												  
                    <a class="btn" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Delete"><i class="icon3-trash"></i></a>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="sep-old">
        <div class="date pull-left">Yesteday - Aug 28, 2013</div>
        <div class="downn pull-right"><div class="circle"><i class="icon3-caret-down"></i></div></div>
        <div class="line"></div>
        <div class="clear"></div>
    </div>
    <div class="row-fluid parent-mc" id="main-content4">
        <div class="container-padding">
            <div class="datecc">
                <div class="month">Aug</div>
                <div class="day">19</div>
            </div>
            <div class="content span10">
                <div class="infocc">
                    <span class="name">Westcoast Tires &amp; Services</span> &nbsp; &nbsp; 
                    <span class="date">Aug 29, 2013 - 8:30 pm</span>
                </div>
                <div class="details">Integer quis auctor nibh. Aenean convallis quam nisi, vel placerat elit bibendum ac. Quisque fermentum in nisl vitae convallis. Fusce varius sodales diam at viverra. Pellentesque luctus nisi non suscipit volutpat. Vestibulum quis mauris auctor, fringilla velit ac, aliquam massa. Vestibulum eget purus commodo neque tristique tristique consequat vitae dolor. Nullam elementum nisi quis risus laoreet tempus. Integer adipiscing mi vitae magna varius, eu eleifend nibh iaculis.
                    <br><br>
                    Vivamus elementum tristique lorem. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
                </div>
                <div class="infocc2">
                    <a href="#">(12)</a> <span class="gray">Favorited This</span> &nbsp; &nbsp; 
                    <a href="#">View Conversation (3)</a>
                </div>
            </div>
            <div class="span2">
                <div class="btn-group pull-right" style="padding:10px 0px;">												  
                    <a class="btn fav" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Favorite"><i class="icon3-star"></i></a>
                    <a class="btn rtw" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Retweet"><i class="icon3-retweet"></i></a>												  
                    <a class="btn" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Delete"><i class="icon3-trash"></i></a>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>						
</div>