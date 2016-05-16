<?php
add_action( 'wp_ajax_motopress_tutorials', 'motopress_tutorials_callback' );
function motopress_tutorials_callback() {
	
	if ( false === ($jsonData = get_transient('mpce_stored_youtube_results_for_help_modal' ) ) ) {
		$playlistId = apply_filters('mpce_wl_tutorials_playlist_id','PLbDImkyrISyLl3bdLk4nOLZtS7EqxK646');
		$api_key = 'AIzaSyBWW-_rbHO-99soLE49jWAc8GmR0LofDyg';
		$url = 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=50&playlistId=' . $playlistId . '&key='. $api_key;
		$requirements = new MPCERequirements();
		if ($requirements->getCurl()) {
			$ch = curl_init();
			$options = array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
			);
			curl_setopt_array($ch, $options);
			$jsonData = curl_exec($ch);
			curl_close($ch);
		} else {
			$jsonData = file_get_contents($url);
		}
		set_transient( 'mpce_stored_youtube_results_for_help_modal', $jsonData, 5 * DAY_IN_SECONDS );
	}
    
    $firstFrame = null;
    $response = 'An internal error occurred. Try again later.';
    $scriptus = '';
    $feedCounter = 0;
	
    $data = @json_decode($jsonData);
    if ( $data !== null && isset($data->pageInfo) && isset($data->items) ) {
        $feedCounter = $data->pageInfo->totalResults;
		$feed = $data->items;
    }
    if ( $feedCounter ) {
        $response = "<div class=\"motopress-tutorials-wrapper\">";

        for ($i=0; $i < $feedCounter; $i++) {
            if ($i == 0) {
				$firstFrame = $feed[$i]->{'snippet'}->{'resourceId'}->{'videoId'};
                $response .= "<div id=\"motopress-framewrapper\">
                                <iframe id=\"motopress-tutorials-iframe\" width=\"100%\" height=\"100%\" src=\"//www.youtube.com/embed/". $firstFrame ."?version=3&enablejsapi=1&theme=light&rel=0&hd=1&showinfo=0\" frameborder=\"0\" allowfullscreen></iframe>
                              </div>";
                $response .= '<div class="tutorials-thumbnails-wrapper">';
                $response .= '<div class="tutorials-thumbnails-container">';
                $response .= '<dl class="motopress-tutorials-thumbnail active-thumbnail" data-video-id="'. $firstFrame .'">';
				$response .= "<dt data-src=\"". $feed[$i]->{'snippet'}->{'thumbnails'}->{'medium'}->url ."\"></dt>";
				$response .= '<dd>'. $feed[$i]->{'snippet'}->{'title'}.'</dd>';
                $response .= '</dl>';
            } else {
				$response .= '<dl class="motopress-tutorials-thumbnail" data-video-id="'. $feed[$i]->{'snippet'}->{'resourceId'}->{'videoId'} .'">';
                $response .= "<dt data-src=\"". $feed[$i]->{'snippet'}->{'thumbnails'}->{'medium'}->url ."\"></dt>";
                $response .= '<dd>'. $feed[$i]->{'snippet'}->{'title'}.'</dd>';
                $response .= '</dl>';
            }
        }

        $response .= '</div>';
        $response .= '</div>';
        $response .= '</div>';

        $scriptus = "
            <script>
                (function() {
                    var timer = null,
                        player = null,
                        frameForOpen = '<iframe id=\"motopress-tutorials-iframe\" width=\"100%\" height=\"100%\" src=\"//www.youtube.com/embed/". $firstFrame ."?version=3&enablejsapi=1&theme=light&rel=0hd=1&showinfo=0\" frameborder=\"0\" allowfullscreen></iframe>',
                        modalBlock = jQuery('#motopress-tutorials-modal'),
                        isShown = function() {
                            if ( modalBlock.is(':visible') ) {
                                return true;
                            }
                            return false;
                        },
                        setSize = function() {
                            var modalHeight = modalBlock.height(),
                                modalBodyHeight = modalHeight - 78,
                                vidWidth = modalBlock.find('.modal-body').outerWidth(),
                                thumbsWrapper = modalBlock.find('.tutorials-thumbnails-wrapper'),
                                tumbsWidth = thumbsWrapper.outerWidth(),
                                calculatedWidth = vidWidth - ( tumbsWidth + 45 ),
                                iframeElement = modalBlock.find('iframe');

                            iframeElement.height( modalBodyHeight );
                            thumbsWrapper.height( modalBodyHeight );
                            iframeElement.width( calculatedWidth );
                        };

                    jQuery(document).on('keyup', function onEscHandler(e) {
                        if ( isShown() ) {
                            if (e.which === 27) {
                                jQuery('.massive-modal-close').click();
                            }
                        }
                    });

                    jQuery(window).resize(function() {
                        if ( isShown() ) {
                            timer && clearTimeout( timer );
                            timer = setTimeout(function() {
                                setSize();
                            }, 30);
                        }
                    });

                    setSize();

                    jQuery('#motopress-tutorials-modal').on('click', 'dl', function() {
                        var thumbURL = jQuery(this).attr('data-video-id'),
                        allThumbs = jQuery('.motopress-tutorials-thumbnail'),
                        frameToReplace = '<iframe id=\"motopress-tutorials-iframe\" width=\"100%\" height=\"100%\" src=\"//www.youtube.com/embed/' + thumbURL + '?version=3&enablejsapi=1=1&theme=light&rel=0&autoplay=1&hd=1&showinfo=0\" frameborder=\"0\" allowfullscreen></iframe>';

                        allThumbs.removeClass('active-thumbnail');
                        jQuery(this).addClass('active-thumbnail');

                        jQuery('#motopress-tutorials-modal').find('#motopress-framewrapper').html( frameToReplace );
							setSize();
                    });

                    jQuery('.tutorials-thumbnails-container').find('dt').each(function() {
                        var bgImg = jQuery(this).attr('data-src');
                        jQuery(this).css('background-image', 'url(\"' + bgImg + '\")');
                    });

                    modalBlock.on('show.bs.modal', function () {
                        var allThumbs = jQuery('.motopress-tutorials-thumbnail'),
                            iFrame = jQuery('#motopress-framewrapper');

                        iFrame.html( frameForOpen );

                        allThumbs.removeClass('active-thumbnail');
                        allThumbs.filter( ':first' ).addClass('active-thumbnail');

//                        timer && clearTimeout( timer );
//                        timer = setTimeout(function() {
//                            setSize();
//                        }, 200);
                    });

					modalBlock.on('shown.bs.modal', function () {
						setSize();
					});

                })();
            </script>";
        $response = $response . $scriptus;
    }

    echo $response;
    die();
}
