                    <div class="callout callout-info">
                        <h4>提示</h4>
                        <p><a href="http://<?php echo $server_ip;?>/api/configs/ios.json?build_id=1.2.0" target="_blank">http://<?php echo $server_ip;?>/api/configs/ios.json?build_id=1.2.0</a></p>
                        <p><a href="http://plugin-background.vidol.tv/api/configs/ios.json?build_id=1.2.0" target="_blank">http://plugin-background.vidol.tv/api/configs/ios.json?build_id=1.2.0</a></p>
                        <p>api有做兩天的cache,更新需<a href="#" class="cancel_cache">清除cache</a>重整.</p>
                    </div>
                    <script type="text/javascript">
                    $(document).ready(function(){
                        $('.cancel_cache').off('click').on('click', function() {
                            if (confirm('清除cache？')) {
                                $.ajax({
                                    url: 'http://plugin-background.vidol.tv/api/caches/cancel_configs_ios.json',
                                    type: 'GET',
                                    cache: false,
                                    headers: {
                                        'VIDOL-API-KEY' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8'
                                    },
                                    dataType: 'jsonp',
                                    error: function(xhr){
                                        alert('Ajax request error');
                                    },
                                    statusCode: {  
                                        200: function(data, statusText, xhr) {
                                            alert('清除cache!');
                                        } 
                                    } 
                                });
                            }
                        });
                    });
                    </script>
