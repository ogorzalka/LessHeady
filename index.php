<?php
include('php-lib/docparser.inc.php');
$docs = new Doc();
$documentation = $docs->get_content();
?>
<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>LessHeady : Easier styling with lessjs</title>
        <link rel="stylesheet/less" type="text/css" href="css/global.less">
        <script src="js/less-1.1.0.min.js"></script>
        <!--[if IE]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    
    <body>
    	
    	<section id="page"> <!-- Defining the #page section with the section tag -->
            <header> <!-- Defining the header section of the page with the appropriate tag -->
                <hgroup>
                    <h1>LessHeady</h1>
                    <h3>Easier styling with lessjs</h3>
                </hgroup>
            </header>
            <nav class="mainnav"> <!-- The nav link semantically marks your main site navigation -->
                <ul>
                    <?php foreach($documentation as $key=>$contents): ?>
                    <li><a href="#<?php echo $key; ?>"><?php echo $contents['title']; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </nav>
            <div id="articles"> <!-- A new section with the articles -->
                <?php foreach($documentation as $key=>$contents): ?>
                    <section id="<?php echo $key; ?>">
                        <header class="mainheader">
                        <h2><?php echo $contents['title']; ?></h2>
                        <?php echo $contents['content']; ?>
                        <p class="location"><span>Location : </span> <?php echo $contents['location']; ?></p>
                        </header>
                        <?php foreach($contents['items'] as $data): ?>
                            <section class="items">
                            <?php $i = 0; ?>
                            <?php foreach($data['content'] as $content): ?>
                            <?php echo '<'.(($i == 0) ? 'header' : 'article').'>'; ?>
                            <?php echo $content; ?>
                            <p class="location"><span>Location : </span> <?php echo $data['location']; ?></p>
                            <?php echo '</'.(($i == 0) ? 'header' : 'article').'>'; ?>
                            <?php $i++; ?>
                            <?php endforeach; ?>
                            </section>
                        <?php endforeach; ?>
                    </section>
                <?php endforeach; ?>
                <!--
                <article id="article1">
                    <h2>Border radius</h2>
                    
                    <ul>
                        <li>
                            <p>The border-radius mixin automatically outputs the correct vendor specific syntax for each browser. See CSS3 spec: <a href="http://www.w3.org/TR/css3-background/#the-border-radius">border-radius</a>.<p>
                            <code>
                            <h3 id="mixin-border-radius" class="mixin">
                                <a href="#mixin-border-radius" class="permalink">border-radius(<span data-default-value="$default-border-radius" class="arg" title="Defaults to: $default-border-radius">$radius</span>, <span data-default-value="false" class="arg" title="Defaults to: false">$vertical-radius</span>)</a>
                            </h3>
                        </li>
                    </ul>
                    <div>
                        <p>In this tutorial, we are creating a photo shoot effect with our just-released PhotoShoot jQuery plug-in. With it you can convert a regular div on the page into a photo shooting stage simulating a camera-like feel.</p>
                        <p></p>
                    </div>
                </article>

                -->

            </div>

        <footer> <!-- Marking the footer section -->

           <div class="line"></div>
           
           <p>Copyright 2010 - YourSite.com</p> <!-- Change the copyright notice -->
           
           <a href="#" class="up">Go UP</a>
           <a href="http://tutorialzine.com/2010/02/html5-css3-website-template/" class="by">Template by Tutorialzine</a>
        </footer>
            
		</section> <!-- Closing the #page section -->

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js"></script>
        <script src="js/jquery.scrollTo-min.js"></script>
        <script src="js/global.js"></script>
    </body>
</html>
