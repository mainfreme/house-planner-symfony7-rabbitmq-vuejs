# Sklep domki drewniane


# Route

-------------------------- -------- -------- ------ ----------------------------------- 
Name                       Method   Scheme   Host   Path
 -------------------------- -------- -------- ------ ----------------------------------- 
_preview_error             ANY      ANY      ANY    /_error/{code}.{_format}           
_wdt_stylesheet            ANY      ANY      ANY    /_wdt/styles                       
_wdt                       ANY      ANY      ANY    /_wdt/{token}                      
_profiler_home             ANY      ANY      ANY    /_profiler/                        
_profiler_search           ANY      ANY      ANY    /_profiler/search                  
_profiler_search_bar       ANY      ANY      ANY    /_profiler/search_bar              
_profiler_phpinfo          ANY      ANY      ANY    /_profiler/phpinfo                 
_profiler_xdebug           ANY      ANY      ANY    /_profiler/xdebug                  
_profiler_font             ANY      ANY      ANY    /_profiler/font/{fontName}.woff2   
_profiler_search_results   ANY      ANY      ANY    /_profiler/{token}/search/results  
_profiler_open_file        ANY      ANY      ANY    /_profiler/open                    
_profiler                  ANY      ANY      ANY    /_profiler/{token}                 
_profiler_router           ANY      ANY      ANY    /_profiler/{token}/router          
_profiler_exception        ANY      ANY      ANY    /_profiler/{token}/exception       
_profiler_exception_css    ANY      ANY      ANY    /_profiler/{token}/exception.css   
file_upload                ANY      ANY      ANY    /file/upload/{uuid}                
csv_summary                GET      ANY      ANY    /file/summary/{uuid}               
file_status                GET      ANY      ANY    /file/status/{uuid}                
file_processed             GET      ANY      ANY    /file/processed/{uuid}             
menumenu_show              ANY      ANY      ANY    /menu/                             
document_index             ANY      ANY      ANY    /document/                         
template_index             ANY      ANY      ANY    /template/                         
dashboard_index            ANY      ANY      ANY    /                                  
product_type_add           POST     ANY      ANY    /api/product/type/add              
product_search             ANY      ANY      ANY    /product/{name}                    
product_type_list          ANY      ANY      ANY    /product/type/list                 
settings_action            GET      ANY      ANY    /settings/action
 -------------------------- -------- -------- ------ -----------------------------------
