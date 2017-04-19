            <div class="content-wrapper <?php echo $right_countent['class'];?>">
            	<section class="content-header">
            		<h1>
            			<?php echo $right_countent['tags']['tag_2']['title'];?> <small><?php echo $right_countent['tags']['tag_3']['title'];?></small>
            		</h1>
            		<ol class="breadcrumb">
<?php
if ($right_countent['tags'] > 0) {
    foreach ($right_countent['tags'] as $tagkey => $tagval) {
        if($tagval['title'] != ''){
            //echo sprintf('<li><a href="%s"><i class="fa %s"></i> %s</a></li>', $tagval['link'], $tagval['class'], $tagval['title']);
?>
            			<li><a href="<?php echo $tagval['link'];?>"><i class="fa <?php echo $tagval['class'];?>"></i> <?php echo $tagval['title'];?></a></li>
<?php
        }
    }
}
?>
            		</ol>
            	</section>
            	<section class="content">
<?php
if(!empty($view_path)){
    $this->load->view($view_path, array('view_data'=>$view_data));    
}
?>
            	</section>
            </div>
