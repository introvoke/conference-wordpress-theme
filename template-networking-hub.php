<?php /*
Template Name: Networking Hub
*/ ?>

<?php get_header(); ?>
<section class="session-title">
    <div class="container">
        <h1 class="title"><?php the_title(); ?></h1>
    </div>
</section>
<section class="session-embed">
    <div class="embed-container">
        <script type="text/javascript">
            const e="<?php echo esc_js(get_theme_mod('networking_hub_id')); ?>",u=new URLSearchParams(window.location.search),i=document.createElement('iframe');i.className="sequel-iframe",i.title="Sequel event",i.width="100%",i.height="90vh",i.src="https://embed.sequel.io/networkingHub/"+e+(u.toString()? '?'+u.toString():''),i.frameBorder="0",i.allow="camera *; microphone *; autoplay; display-capture *; picture-in-picture",i.allowFullscreen=!0,i.style.cssText="height: 90vh; border-radius: 12px; border: 1px solid #dbdfec; box-shadow: 3px 3px 10px 0 rgb(20 20 43 / 4%); width:100%;",document.currentScript.parentNode.insertBefore(i,document.currentScript);
        </script>
    </div>
</section>

<?php get_footer(); ?>


