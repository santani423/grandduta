<ul id="">
<li id=""><?php echo anchor('home', 'Home','class="currentPage"');?></li>
<li id=""><?php echo anchor('nextapp', 'Info Next App','class="currentPage"');?></li>
<li id=""><?php echo anchor('login/process_logout', 'Logout', array('class'=>"currentPage",'onclick' => "return confirm('Anda yakin akan logout?')"));?></li>
</ul>