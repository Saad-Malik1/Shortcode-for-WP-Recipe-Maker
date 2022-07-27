
// ==============================Recipe Custom List Craousel

function recipe_custom_list_craousel($atts) {
	$es = strpos($_SERVER['REQUEST_URI'], "/es/") !== false ? true : false;
	
	   $args = array(  
		   'post_type' => 'wprm_recipe',
		   'tax_query' => array(
				  array(
				   'taxonomy' => 'wprm_language',
				   'field'    => 'term_id',
				   'terms'    =>   $es ? 7403  : 7402,
			   ),
   
		   ),
		   'post_status' => 'publish',
		   'posts_per_page' => 10, 
		   'orderby' => 'title', 
		   'order' => 'ASC', 
		   'post__in' => explode(',', $atts['id'])
	   );   
   
	   $related = new WP_Query( $args ); 
	   $slider_html = "<ul class='owl-carousel owl-theme home_recipe related_recipe'>";
	   $posts = $related->posts;
	   foreach ($posts as $post) :
		   $recipe = WPRM_Recipe_Manager::get_recipe( $post->ID );

				  
		   $cat = wp_list_pluck( get_the_terms( $post->ID, 'wprm_course' ), 'name');
   
	
   
			 $taxonomies = get_taxonomies(['object_type' => ['wprm_recipe']]);
			
   
		   $meta = get_post_meta($post->ID);   
		   $prep_time =  $meta['wprm_prep_time'];   
				  
		   $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
   
				
		   $slider_html .= "<li class='item' >";
		   $slider_html .=  "<a href='".get_post_permalink($post->ID)."'>";
		   $category_detail = wp_list_pluck( get_the_terms( $post->ID, 'wprm_course' ), 'name');
		   $slider_html .=  "<div class='product-carousel-slider-img-cont'>"; 
		   $slider_html .=  "<img src='$image[0]' />";
		   $slider_html .=  "</div>";
	   $slider_html .=  "<ul class='category'>"; 
		   foreach($category_detail as $cd){
	   $slider_html .= "<li>$cd <span>,</span></li>";
		   }
	   $slider_html .=  "</ul>"; 
		   $slider_html .=  "<div class='product-carousel-slider-title'>"; 
		   $slider_html .=  $post->post_title; 
		   $slider_html .=  "</div>";
		   $slider_html .=  "</a>"; 
		   $slider_html .=  "<div class='prep_time'> Cook Time: ".$recipe->prep_time_formatted() ." </div>";  
		   $slider_html .= "</li>";
				  
		endforeach; 
		$slider_html .= "</ul>";
		return $slider_html;
   }
   
   add_shortcode('desire-recipe-carousel', 'recipe_custom_list_craousel');
