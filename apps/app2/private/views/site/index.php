<!-- Homepage Only Scripts -->
<script type="text/javascript" src="<?php echo $TQApp->getWebpath(); ?>js/jquery.cycle.min.js"></script>
<script type="text/javascript" src="<?php echo $TQApp->getWebpath(); ?>js/jquery.cycle.setup.js"></script>
<script type="text/javascript" src="<?php echo $TQApp->getWebpath(); ?>js/piecemaker/swfobject/swfobject.js"></script>
<script type="text/javascript">
var flashvars = {};
flashvars.cssSource = "<?php echo $TQApp->getWebpath(); ?>js/piecemaker/piecemaker.css";
flashvars.xmlSource = "<?php echo $TQApp->getWebpath(); ?>js/piecemaker/piecemaker.xml";
var params = {};
params.play = "false";
params.menu = "false";
params.scale = "showall";
params.wmode = "transparent";
params.allowfullscreen = "true";
params.allowscriptaccess = "sameDomain";
params.allownetworking = "all";
swfobject.embedSWF('<?php echo $TQApp->getWebpath(); ?>js/piecemaker/piecemaker.swf', 'piecemaker', '960', '430', '10', null, flashvars, params, null);
</script>
<!-- End Homepage Only Scripts -->
<div class="wrapper col3">
  <div id="featured_slide">
    <!-- ####################################################################################################### -->
    <div id="piecemaker"> <img src="<?php echo $TQApp->getWebpath(); ?>images/demo/piecemaker/960x360.gif" alt="" /> </div>
    <!-- ####################################################################################################### -->
  </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper col4">
  <div id="container" class="clear">
    <!-- ####################################################################################################### -->
    <div id="shout" class="clear">
      <div class="fl_left">
        <h2>Need A Professional Photographer ?</h2>
        <p>Why not try our services today, you won't regret your choice !</p>
      </div>
      <p class="fl_right"><a href="#contact">Contact Us Today</a></p>
    </div>
    <!-- ####################################################################################################### -->
    <div id="homepage" class="clear">
      <div class="fl_left">
        <h2>Latest Featured Project</h2>
        <div id="hpage_slider">
          <div class="item"><img src="<?php echo $TQApp->getWebpath(); ?>images/demo/featured-project/1.gif" alt="" /></div>
          <div class="item"><img src="<?php echo $TQApp->getWebpath(); ?>images/demo/featured-project/2.gif" alt="" /></div>
          <div class="item"><img src="<?php echo $TQApp->getWebpath(); ?>images/demo/featured-project/3.gif" alt="" /></div>
          <div class="item"><img src="<?php echo $TQApp->getWebpath(); ?>images/demo/featured-project/4.gif" alt="" /></div>
          <div class="item"><img src="<?php echo $TQApp->getWebpath(); ?>images/demo/featured-project/5.gif" alt="" /></div>
        </div>
        <h2>Project Name Goes Here</h2>
        <p><strong>Project Type:</strong> <a href="#">Wedding Photography</a></p>
        <p>This is a W3C standards compliant free website template from <a href="http://www.os-templates.com/">OS Templates</a>. This template is distributed using a <a href="http://www.os-templates.com/template-terms">Website Template Licence</a>. You can use and modify the template for both personal and commercial use. You must keep all copyright information and credit links in the template and associated files. For more CSS templates visit <a href="http://www.os-templates.com/">Free Website Templates</a>.</p>
        <p class="readmore"><a href="#"><strong>View The Full Project &raquo;</strong></a></p>
      </div>
      <div class="fl_right">
        <h2>What People Say About Us</h2>
        <ul class="testimonials">
          <li>
            <p class="name"><strong>Client Name</strong> - CEO</p>
            <blockquote>Justoid nonummy laoreet phasellent penatoque in antesque pellus elis eget tincidunt. Nequatdui laorem justo a non tellus laoreet tincidunt ut vel velit. Idenim semper pellente velis felit ac nullam pretium morbi lacus.</blockquote>
            <p class="readmore"><a href="#"><strong>View This Project</strong></a></p>
          </li>
          <li class="last">
            <p class="name"><strong>Client Name</strong> - CEO</p>
            <blockquote>Justoid nonummy laoreet phasellent penatoque in antesque pellus elis eget tincidunt. Nequatdui laorem justo a non tellus laoreet tincidunt ut vel velit. Idenim semper pellente velis felit ac nullam pretium morbi lacus.</blockquote>
            <p class="readmore"><a href="#"><strong>View This Project</strong></a></p>
          </li>
        </ul>
        <h2>Subscribe To Our Newsletter</h2>
        <p>Justoid nonummy laoreet phasellent penatoque in antesque pellus elis eget tincidunt. Nequatdui laorem justo a non tellus laoreet tincidunt ut vel velit.</p>
        <form action="#" method="post">
          <fieldset>
            <legend>Newsletter Signup:</legend>
            <input type="text" id="newsletter" value="Enter Email Here&hellip;" onfocus="this.value=(this.value=='Enter Email Here&hellip;')? '' : this.value ;" />
            <input type="image" id="subscribe" src="<?php echo $TQApp->getWebpath(); ?>images/sign-up.gif" alt="Submit" />
          </fieldset>
        </form>
        <p class="form_hint">* Please add name@domain.com to your trusted email list</p>
      </div>
    </div>
    <!-- ####################################################################################################### -->
  </div>
</div>