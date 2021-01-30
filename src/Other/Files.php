<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder\Other;

use Ebcms\FormBuilder\ItemInterface;
use Ebcms\Template;

class Files implements ItemInterface
{

    public function __construct(
        string $label,
        string $name,
        $value = '',
        $upload_url = ''
    ) {
        $this->label = $label;
        $this->name = $name;
        $this->value = $value;
        $this->upload_url = $upload_url;
    }

    public function set(string $name, $value): self
    {
        $this->$name = $value;
        return $this;
    }

    private function getTpl(): string
    {
        return <<<'str'
{if !isset($GLOBALS['files_loader'])}
{php $GLOBALS['files_loader']=1}
<script>
function files_render(key){
    var val = $('#field_'+key).val();
    if (val) {
        var arr = JSON.parse(val);
        $.each(arr, function(k, v){
            var html = "";
            html += '<div class="position-relative overflow-hidden my-1 bg-light px-2">';
            var size = v.size;
            if(v.size > 1024*1024){
                size = parseInt(v.size/(1024*1024)) + 'MB';
            } else if (v.size > 1024){
                size = parseInt(v.size/1024) + 'KB';
            } else {
                size = size + 'B';
            }
            // html += '<img style="cursor:pointer;height:100px;width:100px;" class="img-thumbnail img-fluid" alt="'+v.filename+'(大小:'+size+')" title="'+v.filename+'(大小:'+size+')" src="'+v.src+'" >';
            html += '<span class="close" style="cursor:pointer;padding: 0 6px 5px 6px;" onclick="files_del(\''+key+'\','+k+')">×</span>';
            if(k!=0){
                html += '<span class="close" style="cursor:pointer;padding: 0 4px 5px 4px;" onclick="files_move(\''+key+'\','+k+', -1)">↑</span>';
            }
            if(k<arr.length-1){
                html += '<span class="close" style="cursor:pointer;padding: 0 4px 5px 4px;" onclick="files_move(\''+key+'\','+k+', 1)">↓</span>';
            }
            if(v.type.substring(0, 6) == 'audio/'){
                html += '<svg t="1603090062197" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="64160" width="20" height="20"><path d="M213.333333 85.333333h409.002667a42.666667 42.666667 0 0 1 30.165333 12.501334l230.997334 230.997333a42.666667 42.666667 0 0 1 12.501333 30.165333V853.333333a85.333333 85.333333 0 0 1-85.333333 85.333334H213.333333a85.333333 85.333333 0 0 1-85.333333-85.333334V170.666667a85.333333 85.333333 0 0 1 85.333333-85.333334z" fill="#00C853" p-id="64161"></path><path d="M512 608.682667V362.666667a21.333333 21.333333 0 0 1 38.272-12.928c20.821333 27.221333 40.533333 46.933333 58.88 59.178666 17.28 11.52 34.730667 17.322667 52.693333 17.749334a21.333333 21.333333 0 0 1-1.024 42.666666c-26.282667-0.64-51.498667-9.045333-75.306666-24.917333-10.24-6.826667-20.48-15.189333-30.848-25.173333V661.333333a21.589333 21.589333 0 0 1-0.128 2.346667c1.706667 33.066667-22.613333 70.229333-64.128 92.501333-54.016 29.013333-116.736 21.973333-140.117334-15.658666-23.381333-37.674667 1.450667-91.648 55.466667-120.661334 36.309333-19.498667 76.629333-22.698667 106.24-11.178666z" fill="#FFFFFF" p-id="64162"></path><path d="M896 341.333333h-213.333333a42.666667 42.666667 0 0 1-42.666667-42.666666V85.333333l256 256z" fill="#43A047" p-id="64163"></path></svg>';
            } else if (v.type.substring(0, 6) == 'video/'){
                html += '<svg t="1603090324751" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="70696" width="20" height="20"><path d="M953.670516 201.69697v775.043878A46.917818 46.917818 0 0 1 907.125062 1024H115.852335a46.902303 46.902303 0 0 1-46.545455-47.259152V47.259152A46.902303 46.902303 0 0 1 115.852335 0h651.636363z" fill="#7986CB" p-id="70697"></path><path d="M674.52191 512L379.765062 682.666667V341.178182z" fill="#FFFFFF" p-id="70698"></path><path d="M953.670516 201.69697H798.519001l155.151515 170.666666V201.69697z" fill="#6D78B6" p-id="70699"></path><path d="M224.458395 946.424242H146.882638a15.515152 15.515152 0 0 1-15.515152-15.515151v-77.575758a15.515152 15.515152 0 0 1 15.515152-15.515151h77.575757a15.515152 15.515152 0 0 1 15.515152 15.515151v77.575758a15.515152 15.515152 0 0 1-15.515152 15.515151z m0-155.151515H146.882638a15.515152 15.515152 0 0 1-15.515152-15.515151v-77.575758a15.515152 15.515152 0 0 1 15.515152-15.515151h77.575757a15.515152 15.515152 0 0 1 15.515152 15.515151v77.575758a15.515152 15.515152 0 0 1-15.515152 15.515151z m0-155.151515H146.882638a15.515152 15.515152 0 0 1-15.515152-15.515151v-77.575758a15.515152 15.515152 0 0 1 15.515152-15.515151h77.575757a15.515152 15.515152 0 0 1 15.515152 15.515151v77.575758a15.515152 15.515152 0 0 1-15.515152 15.515151z m0-155.151515H146.882638a15.515152 15.515152 0 0 1-15.515152-15.515152v-77.575757a15.515152 15.515152 0 0 1 15.515152-15.515152h77.575757a15.515152 15.515152 0 0 1 15.515152 15.515152v77.575757a15.515152 15.515152 0 0 1-15.515152 15.515152z m0-155.151515H146.882638a15.515152 15.515152 0 0 1-15.515152-15.515152v-77.575757a15.515152 15.515152 0 0 1 15.515152-15.515152h77.575757a15.515152 15.515152 0 0 1 15.515152 15.515152v77.575757a15.515152 15.515152 0 0 1-15.515152 15.515152z m0-155.151515H146.882638a15.515152 15.515152 0 0 1-15.515152-15.515152V77.575758a15.515152 15.515152 0 0 1 15.515152-15.515152h77.575757a15.515152 15.515152 0 0 1 15.515152 15.515152v77.575757a15.515152 15.515152 0 0 1-15.515152 15.515152zM798.519001 217.212121h77.575758a15.515152 15.515152 0 0 1 15.515151 15.515152v77.575757a15.515152 15.515152 0 0 1-15.515151 15.515152h-77.575758a15.515152 15.515152 0 0 1-15.515151-15.515152v-77.575757a15.515152 15.515152 0 0 1 15.515151-15.515152z m0 155.151515h77.575758a15.515152 15.515152 0 0 1 15.515151 15.515152v77.575757a15.515152 15.515152 0 0 1-15.515151 15.515152h-77.575758a15.515152 15.515152 0 0 1-15.515151-15.515152v-77.575757a15.515152 15.515152 0 0 1 15.515151-15.515152z m0 155.151516h77.575758a15.515152 15.515152 0 0 1 15.515151 15.515151v77.575758a15.515152 15.515152 0 0 1-15.515151 15.515151h-77.575758a15.515152 15.515152 0 0 1-15.515151-15.515151v-77.575758a15.515152 15.515152 0 0 1 15.515151-15.515151z m0 155.151515h77.575758a15.515152 15.515152 0 0 1 15.515151 15.515151v77.575758a15.515152 15.515152 0 0 1-15.515151 15.515151h-77.575758a15.515152 15.515152 0 0 1-15.515151-15.515151v-77.575758a15.515152 15.515152 0 0 1 15.515151-15.515151z m0 155.151515h77.575758a15.515152 15.515152 0 0 1 15.515151 15.515151v77.575758a15.515152 15.515152 0 0 1-15.515151 15.515151h-77.575758a15.515152 15.515152 0 0 1-15.515151-15.515151v-77.575758a15.515152 15.515152 0 0 1 15.515151-15.515151z" fill="#FFFFFF" p-id="70700"></path><path d="M954.694516 201.69697H814.034153a46.545455 46.545455 0 0 1-46.545455-46.545455V0z" fill="#9FA8DA" p-id="70701"></path></svg>';
            } else if (v.type.substring(0, 6) == 'image/'){
                html += '<svg t="1602825819135" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="31699" width="20" height="20"><path d="M829.64898 849.502041H194.35102c-43.885714 0-79.412245-35.526531-79.412244-79.412245V253.910204c0-43.885714 35.526531-79.412245 79.412244-79.412245h635.29796c43.885714 0 79.412245 35.526531 79.412244 79.412245v516.179592c0 43.885714-35.526531 79.412245-79.412244 79.412245z" fill="#D2F4FF" p-id="31700"></path><path d="M909.061224 656.195918l-39.706122-48.065306L626.416327 365.714286c-19.330612-19.330612-50.677551-19.330612-70.008164 0L419.526531 502.073469c-2.612245 2.612245-5.22449 3.134694-6.791837 3.134694-1.567347 0-4.702041-0.522449-6.791837-3.134694L368.326531 464.979592c-19.330612-19.330612-50.677551-19.330612-70.008164 0l-143.673469 143.673469-39.706122 48.065306v113.893878c0 43.885714 35.526531 79.412245 79.412244 79.412245h635.29796c43.885714 0 79.412245-35.526531 79.412244-79.412245v-114.416327" fill="#16C4AF" p-id="31701"></path><path d="M273.763265 313.469388m-49.632653 0a49.632653 49.632653 0 1 0 99.265306 0 49.632653 49.632653 0 1 0-99.265306 0Z" fill="#E5404F" p-id="31702"></path><path d="M644.179592 768h-365.714286c-11.493878 0-20.897959-9.404082-20.897959-20.897959s9.404082-20.897959 20.897959-20.897959h365.714286c11.493878 0 20.897959 9.404082 20.897959 20.897959s-9.404082 20.897959-20.897959 20.897959zM461.322449 670.82449h-182.857143c-11.493878 0-20.897959-9.404082-20.897959-20.897959s9.404082-20.897959 20.897959-20.89796h182.857143c11.493878 0 20.897959 9.404082 20.897959 20.89796s-9.404082 20.897959-20.897959 20.897959z" fill="#0B9682" p-id="31703"></path></svg>';
            } else if (v.type.substring(0, 5) == 'text/'){
                html += '<svg t="1603090877375" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="80171" width="20" height="20"><path d="M772.8 158.4L633.6 11.2H403.2C145.6 11.2 142.4 11.2 124.8 48c-9.6 19.2-9.6 62.4-9.6 473.6 0 484.8 0 470.4 28.8 488 11.2 6.4 81.6 8 374.4 6.4l360-1.6 17.6-19.2 17.6-19.2V304l-140.8-145.6z m-124.8 41.6l1.6-80 88 92.8 88 92.8h-88c-100.8 0-92.8 11.2-89.6-105.6z m204.8 459.2l-1.6 296-337.6 1.6c-267.2 1.6-337.6 0-339.2-6.4-1.6-3.2-1.6-203.2-1.6-443.2L176 72l206.4-1.6 206.4-1.6v118.4c0 123.2 3.2 148.8 24 166.4 9.6 9.6 25.6 9.6 126.4 9.6h115.2l-1.6 296z" fill="#313A48" p-id="80172"></path><path d="M496 334.4H265.6v57.6H496zM265.6 635.2v28.8l249.6-1.6 249.6-1.6 1.6-27.2 3.2-27.2H265.6zM496 456H265.6v57.6H496zM265.6 750.4v28.8H768v-57.6H265.6z" fill="#313A48" p-id="80173"></path></svg>';
            } else if (v.type == 'application/vnd.android.package-archive'){
                html += '<svg t="1603091506727" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="87117" width="20" height="20"><path d="M751.801 11.57H210.932a74.804 74.804 0 0 0-74.572 74.573v851.714a74.804 74.804 0 0 0 74.572 74.572h708.701a74.804 74.804 0 0 0 74.573-74.572V254.033z" fill="#E9FFE9" p-id="87118"></path><path d="M751.801 179.345a74.862 74.862 0 0 0 74.573 74.63h167.774L751.8 11.571z" fill="#97DD97" p-id="87119"></path><path d="M87.647 624.814h569.854q69.423 0 69.423 69.423v185.13q0 69.424-69.423 69.424H87.647q-69.423 0-69.423-69.424v-185.13q0-69.423 69.423-69.423z" fill="#65CE65" p-id="87120"></path><path d="M147.815 864.557a49.638 49.638 0 0 1-16.951-40.497 48.712 48.712 0 0 1 15.562-38.704q15.505-13.711 43.91-13.711h58.722l2.372 22.794h-60.92a39.976 39.976 0 0 0-26.265 7.752 29.62 29.62 0 0 0 2.372 45.068 56.522 56.522 0 0 0 32.918 8.215 107.318 107.318 0 0 0 35.406-4.57c8.39-3.067 12.555-7.348 12.555-12.902l3.586 23.142a47.324 47.324 0 0 1-13.537 9.487 77.581 77.581 0 0 1-18.86 5.786 124.558 124.558 0 0 1-23.141 2.025c-20.365 0.405-36.39-4.34-47.73-13.885z m99.68-100.144a45.82 45.82 0 0 0-11.57-33.265 43.448 43.448 0 0 0-32.571-11.571 76.424 76.424 0 0 0-24.646 4.05 70.928 70.928 0 0 0-21.463 11.57l-17.53-12.438a66.068 66.068 0 0 1 26.497-18.802 94.648 94.648 0 0 1 36.447-6.711 83.54 83.54 0 0 1 37.605 7.752 53.514 53.514 0 0 1 23.778 22.447 71.796 71.796 0 0 1 8.157 35.406v113.45h-24.703z m59.994-64.39h24.299v248.305h-24.299z m43.159 171.997a42.696 42.696 0 0 1-21.059-23.141l2.372-36.91a48.076 48.076 0 0 0 5.438 22.215 44.72 44.72 0 0 0 15.62 17.356 43.043 43.043 0 0 0 23.894 6.248 43.968 43.968 0 0 0 33.96-13.133 52.82 52.82 0 0 0 12.322-37.026v-38.183a54.266 54.266 0 0 0-12.09-37.662 48.886 48.886 0 0 0-57.854-6.827 44.778 44.778 0 0 0-15.62 16.951 46.282 46.282 0 0 0-5.843 21.753l-3.587-34.018a56.002 56.002 0 0 1 24.414-24.703 78.102 78.102 0 0 1 35.464-7.579 58.779 58.779 0 0 1 32.05 8.447 52.704 52.704 0 0 1 20.422 24.414 97.367 97.367 0 0 1 6.885 39.224v38.473a93.144 93.144 0 0 1-6.885 38.125 52.936 52.936 0 0 1-20.885 24.414 62.076 62.076 0 0 1-32.976 8.389 80.994 80.994 0 0 1-36.216-7.059z m128.665-242.983h24.472V876.3h-24.472z m17.356 187.039l100.086-116.053h28.926L492.1 850.44z m50.159-35.06l14.29-23.14L634.59 876.59h-29.737z" fill="#FFFFFF" p-id="87121"></path><path d="M454.783 109.92A178.072 178.072 0 0 0 276.77 287.994h356.086A178.072 178.072 0 0 0 454.783 109.92z m-174.08 192.825h348.16V532.25h-348.16z" fill="#97DD97" p-id="87122"></path><path d="M367.078 206.651a19.323 19.323 0 1 0 38.646 0 19.323 19.323 0 1 0-38.646 0zM496.033 206.651a19.323 19.323 0 1 0 38.645 0 19.323 19.323 0 1 0-38.645 0z" fill="#DFF7DF" p-id="87123"></path></svg>';
            } else if (v.type == 'application/x-silverlight-app'){
                html += '<svg t="1603091587684" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="90324" width="20" height="20"><path d="M836.855172 0v217.158621h132.413794zM444.910345 176.551724c7.062069-7.062069 10.593103-15.889655 10.593103-28.248276 0-8.827586-1.765517-15.889655-7.062069-21.186207-3.531034-5.296552-10.593103-10.593103-17.655172-12.35862-5.296552-1.765517-14.124138-1.765517-26.482759-1.765518h-52.965517v72.386207h52.965517c21.186207 0 33.544828-3.531034 40.606897-8.827586zM612.634483 153.6c-5.296552-14.124138-10.593103-28.248276-14.124138-38.841379-1.765517 12.358621-5.296552 22.951724-10.593104 35.310345L564.965517 210.096552h67.089655l-19.420689-56.496552z" fill="#5E89C1" p-id="90325"></path><path d="M969.268966 296.606897V247.172414h-167.724138V0H54.731034v1024H971.034483l-1.765517-727.393103zM582.62069 82.97931h33.544827v1.765518l88.275862 217.15862h-37.075862l-24.717241-65.324138h-86.510345l-22.951724 65.324138h-33.544828L582.62069 82.97931z m-261.296552 0h82.97931c14.124138 0 24.717241 0 33.544828 1.765518 10.593103 1.765517 19.42069 5.296552 26.482758 10.593103 7.062069 5.296552 14.124138 12.358621 17.655173 21.186207 5.296552 8.827586 7.062069 19.42069 7.062069 30.013793 0 19.42069-5.296552 35.310345-17.655173 47.668966-12.358621 12.358621-33.544828 19.42069-63.55862 19.420689h-52.965517V300.137931h-31.779311V82.97931z m-102.4 0h31.77931v218.924138H218.924138V82.97931z m732.689655 921.6H72.386207V388.413793h879.227586v616.165517z" fill="#5E89C1" p-id="90326"></path><path d="M538.482759 554.372414c12.358621-15.889655 21.186207-37.075862 19.420689-58.262069-19.42069 0-40.606897 12.358621-54.731034 28.248276-12.358621 14.124138-22.951724 35.310345-19.42069 56.496551 21.186207 0 42.372414-12.358621 54.731035-26.482758zM430.786207 865.103448c22.951724-1.765517 31.77931-15.889655 60.027586-15.889655s37.075862 15.889655 61.793104 14.124138c24.717241 0 40.606897-22.951724 56.496551-45.903448 17.655172-26.482759 24.717241-51.2 26.482759-52.965517 0 0-49.434483-19.42069-49.434483-74.151725 0-47.668966 38.841379-68.855172 40.606897-70.620689-21.186207-31.77931-54.731034-35.310345-67.089655-37.075862-28.248276-3.531034-56.496552 17.655172-70.62069 17.655172-14.124138 0-37.075862-15.889655-61.793104-15.889655-31.77931 0-60.027586 17.655172-75.917241 45.903448-31.77931 56.496552-8.827586 141.241379 22.951724 187.144828 14.124138 22.951724 33.544828 47.668966 56.496552 47.668965z" fill="#5E89C1" p-id="90327"></path></svg>';
            } else if (-1 != $.inArray(v.type, ['application/vnd.ms-powerpoint', 'application/x-ppt', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'])){
                html += '<svg t="1603091731283" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="91985" width="20" height="20"><path d="M536.3 70.5h57.4v91.12c108.22 0.61 216.55-1.12 324.67 0.5 23.28-2.22 41.51 15.9 39.18 39.18 1.72 189.12-0.41 378.34 1 567.56-1 20.45 2 43.13-9.72 61.25-14.78 10.73-34 9.31-51.32 10.12-101.24-0.5-202.48-0.31-303.82-0.31v101.25h-63C376.44 913 221.84 887.3 67.45 860.18q-0.15-354.29 0-708.48c156.22-27.04 312.43-54.58 468.85-81.2z" fill="#D24625" p-id="91986"></path><path d="M593.7 192h334.1v617.55H593.7v-81h243v-40.5h-243v-50.6h243v-40.5H593.8c-0.1-19.84-0.1-39.69-0.21-59.53 40.1 12.45 85.76 12.15 121.6-12 38.78-23 59-66.82 62.37-110.55-44.45-0.3-88.89-0.2-133.23-0.2-0.11-44 0.5-88.08-0.91-132q-24.92 4.86-49.71 10.22z" fill="#FFFFFF" p-id="91987"></path><path d="M664.67 261.54C735 264.78 794.16 324 797.91 394.16c-44.45 0.51-88.89 0.31-133.33 0.31-0.01-44.34-0.12-88.69 0.09-132.93zM290 392.75c19.94-0.91 44.65-4.56 58.1 14.17 11.55 19.84 10.93 46 1.32 66.41-11.55 20.86-37.67 18.83-57.81 21.26-2.13-33.91-1.94-67.83-1.61-101.84z" fill="#D24625" p-id="91988"></path><path d="M114.33 544.84V421.11h37.12q21.1 0 27.5 1.86 9.85 2.77 16.49 12.11t6.64 24.09q0 11.39-3.83 19.16a33.28 33.28 0 0 1-9.73 12.2 32.5 32.5 0 0 1-12 5.86q-8.28 1.76-24 1.77h-15.06v46.68zM137.46 442v35.11h12.66q13.67 0 18.28-1.94a15.42 15.42 0 0 0 7.23-6.08 17.58 17.58 0 0 0 2.62-9.62 16.79 16.79 0 0 0-3.67-11.14 15.7 15.7 0 0 0-9.3-5.49q-4.15-0.83-16.64-0.84z m83.6 102.84V421.11h37.12q21.1 0 27.5 1.86 9.85 2.77 16.49 12.11t6.64 24.09q0 11.39-3.83 19.16a33.28 33.28 0 0 1-9.73 12.2 32.5 32.5 0 0 1-12 5.86q-8.28 1.76-24 1.77h-15.07v46.68zM244.18 442v35.11h12.66q13.67 0 18.28-1.94a15.42 15.42 0 0 0 7.23-6.08 17.58 17.58 0 0 0 2.62-9.62 16.79 16.79 0 0 0-3.67-11.14 15.7 15.7 0 0 0-9.3-5.49q-4.15-0.83-16.64-0.84z m109.39 102.84V442h-34v-20.89h91V442H376.7v102.8z" fill="#FFFFFF" p-id="91989"></path></svg>';
            } else if (-1 != $.inArray(v.type, ['application/vnd.ms-excel', 'application/x-xls', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])){
                html += '<svg t="1603091941225" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="92920" width="20" height="20"><path d="M974.336621 754.669899c-0.387879-182.354747-0.323232-364.8 0.206868-547.28404-0.775758-17.868283 0.491313-37.688889-10.343434-53.204041-15.424646-10.627879-34.973737-9.360808-52.751515-10.097778-105.231515 0.530101-210.46303 0.323232-315.694546 0.323233V60.302222h-62.500202C371.999651 88.759596 210.629146 116.389495 49.245712 144.471919v735.741414c160.426667 28.069495 320.956768 54.988283 481.176565 84.105051h65.241212v-94.577778c109.019798-0.206869 218.026667 0.323232 326.943031 0 17.583838-0.73697 43.88202-1.267071 48.161616-23.091717 6.4-30.16404 3.038384-61.388283 3.568485-91.97899zM164.885307 552.830707l-26.362828-44.476768-26.440404 44.476768-28.858182-0.012929L123.87359 485.766465l-36.848485-61.427071h28.082425l23.841616 41.283232 23.414949-41.283232H190.200863l-37.003636 62.409697 40.649697 66.081616h-28.961617z m125.621011-0.012929H206.776217v-127.392323h24.009697v105.748686h59.720404v21.643637z m101.999191-16.743435c-3.697778 6.348283-9.309091 11.377778-16.032323 14.325657-6.904242 3.128889-15.515152 4.693333-25.858586 4.693333-15.036768 0-26.595556-3.749495-34.637575-11.261414-8.054949-7.511919-12.864646-18.450101-14.429091-32.814545l23.363232-2.456566c1.383434 8.468687 4.227879 14.674747 8.559192 18.618182 4.318384 3.943434 10.123636 5.934545 17.402828 5.960404 7.744646 0 13.562828-1.771313 17.480404-5.30101 3.917576-3.542626 5.882828-7.68 5.882829-12.412121 0.077576-2.792727-0.788687-5.520808-2.469495-7.744647-1.654949-2.133333-4.525253-3.995152-8.636768-5.559596-2.818586-1.060202-9.231515-2.92202-19.238788-5.611313-12.877576-3.439192-21.902222-7.68-27.099798-12.709495a34.607838 34.607838 0 0 1-10.951111-25.858586 35.038384 35.038384 0 0 1 5.171717-18.372525 32.905051 32.905051 0 0 1 14.778182-12.955152c6.464646-2.986667 14.26101-4.486465 23.40202-4.486464 14.933333 0 26.168889 3.529697 33.706667 10.60202 7.550707 7.072323 11.507071 16.497778 11.894949 28.315152l-24.022626 1.137777c-1.021414-6.593939-3.232323-11.351919-6.606869-14.24808-3.374545-2.896162-8.442828-4.344242-15.204848-4.344243-6.981818 0-12.450909 1.551515-16.394344 4.641616a9.606465 9.606465 0 0 0-3.814141 7.977374c-0.025859 2.999596 1.292929 5.85697 3.568485 7.796364 3.025455 2.766869 10.382222 5.624242 22.057374 8.597979 11.688081 2.973737 20.324848 6.050909 25.923232 9.231516 5.48202 3.090101 10.020202 7.602424 13.136162 13.071515 3.167677 5.520808 4.745051 12.334545 4.74505 20.46707 0.025859 7.292121-1.939394 14.44202-5.67596 20.699798zM942.672782 838.206061H595.753994v-63.09495h84.105051v-73.567677h-84.105051v-42.05899h84.105051v-73.580606h-84.105051v-42.05899h84.105051v-73.580606h-84.105051v-42.058989h84.105051v-73.580607h-84.105051v-42.058989h84.105051v-73.593536h-84.105051v-63.08202h346.918788V838.206061z" fill="#207245" p-id="92921"></path><path d="M721.853388 238.972121h147.174142v73.593536H721.853388v-73.593536z m0 115.652525h147.174142v73.593536H721.853388v-73.593536z m0 115.639596h147.174142v73.593536H721.853388v-73.593536z m0 115.639596h147.174142v73.593536H721.853388v-73.593536z m0 115.639596h147.174142v73.593536H721.853388V701.543434z m0 0" fill="#207245" p-id="92922"></path></svg>';
            } else if (-1 != $.inArray(v.type, ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])){
                html += '<svg t="1603092044910" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="93817" width="20" height="20"><path d="M534.3232 0.4096h69.4272v95.232c124.3136 1.024 248.6272-1.2288 372.736 1.024 12.4928-1.4336 24.7808 2.8672 33.5872 11.6736 8.8064 8.8064 12.9024 21.2992 11.264 33.792 2.048 234.2912 0 468.992 1.2288 703.0784-1.2288 23.9616 2.2528 50.5856-11.264 72.0896-16.9984 12.0832-38.912 10.8544-58.7776 12.0832H603.9552v93.3888h-72.0896C354.5088 990.208 177.3568 959.8976 0 928.1536V95.8464C177.9712 63.8976 356.1472 32.768 534.3232 0.4096z m0 0" fill="#2A528F" p-id="93818"></path><path d="M603.9552 131.2768h383.3856v761.0368H603.9552v-95.232h302.08v-48.128H603.9552V690.176h302.08v-48.128H603.9552v-58.9824h302.08v-48.128H603.9552v-60.2112h302.08v-46.2848H603.9552v-60.2112h302.08V321.536H603.9552v-59.5968h302.08v-47.5136H603.9552V131.2768zM240.4352 341.4016c22.1184-1.2288 44.2368-2.2528 66.3552-3.4816 15.5648 80.2816 31.3344 160.3584 48.128 240.64 13.1072-82.5344 27.648-164.864 41.7792-247.1936 23.1424-0.8192 46.4896-2.2528 69.632-3.6864-26.4192 115.3024-49.3568 231.6288-78.0288 346.112-19.456 10.4448-48.128 0-71.4752 1.2288-16.1792-78.848-33.9968-157.2864-47.9232-236.1344-13.5168 76.8-31.3344 152.9856-46.6944 229.1712-22.3232-1.2288-44.6464-2.4576-67.1744-4.096-19.456-104.8576-42.1888-208.896-60.416-313.7536 19.8656-1.024 39.7312-1.8432 60.2112-2.4576 12.0832 75.776 25.6 151.1424 36.0448 227.1232 16.1792-78.0288 32.768-155.648 49.5616-233.472z m0 0" fill="#FFFFFF" p-id="93819"></path></svg>';
            } else if (-1 != $.inArray(v.type, ['application/pdf'])){
                html += '<svg t="1603092113662" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="94736" width="20" height="20"><path d="M128.000015 31.487999C128.000015 14.079999 142.338014 0 159.618014 0h526.565979L895.999985 218.751991v773.68297A31.487999 31.487999 0 0 1 864.459986 1023.99996H159.540014A31.487999 31.487999 0 0 1 128.000015 992.511961V31.487999z" fill="#E9E9E0" opacity=".511" p-id="94737"></path><path d="M497.280001 174.565993c-10.7 2.51-17.611999 7.91-21.708 16.922-12.850999 28.261999 4.633 82.892997 19.686 118.604995 29.209999-91.212996 18.534999-120.703995 11.954999-129.484995a13.925999 13.925999 0 0 0-9.931999-6.042m2.098999 194.227993c-10.803 23.039999-32.204999 67.249997-57.958997 112.485995 20.531999-4.89 42.751998-9.549 66.687997-13.925999a716.543972 716.543972 0 0 1 56.191998-10.112 1280.89595 1280.89595 0 0 1-64.920998-88.447996m115.557996 108.799995c12.109 13.055999 23.730999 24.395999 34.866999 34.021999 35.276999 30.463999 58.239998 37.093999 71.500997 37.093999h0.947c11.725-0.307 17.740999-5.888 19.711999-8.14 6.733-26.189999-3.123-36.991999-6.4-40.601999-16.895999-18.508999-59.647998-26.419999-120.626995-22.399999m-330.419987 126.642995c5.12 4.915 10.446 6.554 17.152999 5.274 12.953999-2.458 39.781998-17.791999 85.196997-86.629996-20.786999 6.758-38.732998 13.874999-53.759998 21.349999-34.559999 17.151999-46.130998 32.306999-49.766998 41.983998-3.584 9.625 0 16.204999 1.178 18.021999m13.029999 31.231999c-11.52 0-22.117999-4.608-31.000998-13.618999l-0.384-0.41-0.359-0.41c-5.452-6.604-12.849999-23.039999-5.99-42.827999 12.032-34.508999 61.516998-63.999998 147.583994-88.114996a1299.802949 1299.802949 0 0 0 10.24-17.433999 1636.095936 1636.095936 0 0 0 65.765997-128.639995 467.557982 467.557982 0 0 1-23.627999-57.804998c-15.206999-46.232998-17.433999-81.739997-6.656-105.522996 7.475-16.459999 21.323999-27.289999 40.011999-31.308999l0.615-0.128 0.64-0.076c1.945-0.205 19.404999-1.562 32.434998 15.846 9.856 13.157999 13.951999 33.330999 12.211 59.979997-1.971 30.003999-11.392 69.043997-28.031999 116.224996 29.388999 44.338998 57.087998 81.919997 82.866997 112.485995 4.66-0.46 9.421-0.87 14.207999-1.229 73.343997-5.555 122.316995 4.557 145.509995 30.003999 7.604 8.32 12.442 18.226999 14.439999 29.439999 2.022 11.34 1.023 24.114999-2.868 37.963999l-0.46 1.638999-0.846 1.433c-4.53 7.68-18.277999 20.735999-41.035998 21.3h-1.536c-24.909999 0-54.297998-14.463999-87.398997-43.033999a512.25598 512.25598 0 0 1-50.099998-50.713998c-39.243998 4.634-70.142997 11.776-70.526997 11.853l-0.563 0.103a1165.259954 1165.259954 0 0 0-89.471997 19.711999c-16.869999 27.569999-32.895999 50.687998-47.820998 69.042997-26.085999 32.024999-48.741998 49.509998-69.247997 53.400998a45.925998 45.925998 0 0 1-8.602 0.845M128.001015 716.799972h767.99997v275.481989c0 17.509999-13.977999 31.717999-31.539999 31.717999H159.540014A31.487999 31.487999 0 0 1 128.000015 992.281961V716.799972z" fill="#C64A48" p-id="94738"></path><path d="M320.513007 871.679966h33.151999c11.468 0 19.249999-3.635 23.397999-10.906 2.227-3.968 3.328-9.6 3.328-16.895999 0-8.78-2.458-15.051999-7.398-18.866999-4.916-3.814-12.953999-5.709-24.063999-5.709h-28.415999v52.377998z m0 30.949999v64.614997h-35.789998V788.351969h78.285997c16.536999 0 29.541999 5.12 38.987998 15.309 9.472 10.24 14.207999 24.191999 14.208 41.983998 0 12.595-3.328 24.139999-10.01 34.559999-9.548 14.949999-24.421999 22.424999-44.619998 22.424999h-41.061999zM478.925001 819.301968V936.319963h34.815999c13.363999 0 23.475999-6.298 30.309999-18.917999 5.735-10.624 8.577-24.319999 8.576999-41.087998 0-23.219999-4.608-39.321998-13.823999-48.307998-6.042-5.785-14.566999-8.704-25.522999-8.704h-34.355999zM443.853003 967.269962V788.377969h75.622997c25.778999 0 44.619998 9.958 56.549997 29.874999 8.73 14.642999 13.106999 33.074999 13.107 55.320998 0 24.089999-4.761 44.441998-14.309999 61.055997-12.39 21.759999-31.333999 32.639999-56.779998 32.639999h-74.189997zM651.878995 891.903965v75.339997H616.089996V788.352969h121.906995v31.692999H651.879995v40.191998h75.365997v31.666999z" fill="#FFFFFF" p-id="94739"></path><path d="M686.029993 0l209.662992 216.908992H686.029993z" fill="#D9D7C9" p-id="94740"></path></svg>';
            } else if (-1 != $.inArray(v.type, ['application/zip', 'application/x-zip-compressed'])){
                html += '<svg t="1603092568491" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="99383" width="20" height="20"><path d="M0.108 332.962h1023.191v336.465H0.108z" fill="#F95F5D" p-id="99384"></path><path d="M1018.718 332.962V64.62c0-36.38-27.27-63.65-63.595-63.65H63.703C27.38 0.97 0.109 28.24 0.109 64.62v268.342h1018.61z" fill="#55C7F7" p-id="99385"></path><path d="M0.108 669.427v268.341c0 36.38 27.27 63.65 63.65 63.65H959.65c36.379 0 63.595-27.27 63.595-63.65V669.373H0.162z" fill="#7ECF3B" p-id="99386"></path><path d="M400.276 0.97h227.436v1000.448H400.276z" fill="#FDAF42" p-id="99387"></path><path d="M627.658 432.99V573.98h-236.49V432.99h236.49z m40.96-54.595h-318.41c-4.527 0-13.635 4.581-13.635 13.635v222.855c0 4.527 4.527 13.635 13.635 13.635H668.51c4.581 0 13.69-4.527 13.69-13.635V392.03c-4.581-9.054-9.163-13.635-13.69-13.635z" fill="#FFFFFF" p-id="99388"></path></svg>';
            }else{
                html += '<svg t="1603090726122" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="77838" width="20" height="20"><path d="M153.7 64.1c-17.9 0-32.6 14.7-32.6 32.6v830.7c0 17.9 14.7 32.6 32.6 32.6h716.7c17.9 0 32.6-14.7 32.6-32.6V259.5L707.5 64.1H153.7z" fill="#EBC81E" p-id="77839"></path><path d="M707.5 64.1V227c0 17.9 14.7 32.6 32.6 32.6H903L707.5 64.1z" fill="#FFF3AE" p-id="77840"></path><path d="M532.8 636.9c0.4-21.4 1.9-36.9 4.5-46.4s6.5-17.9 11.9-25.3c5.4-7.3 16.7-18.7 34.2-34.2 26-23 43.4-42.5 52.3-58.6 8.9-16.1 13.4-33.6 13.4-52.6 0-32.9-12.7-60.9-38.1-84.1-25.4-23.2-59.4-34.8-102-34.8-40.2 0-72.6 10.8-97.1 32.4-24.4 21.6-39.2 52.7-44.1 93.3l55 6.5c4.8-30.3 14.8-52.6 30.1-66.9 15.3-14.3 34.3-21.4 57.1-21.4 23.5 0 43.5 7.9 59.8 23.6 16.2 15.7 24.4 34 24.4 54.9 0 11.5-2.8 22.1-8.3 31.8-5.5 9.7-17.6 22.8-36.1 39.1s-31.4 28.8-38.5 37.3c-9.7 11.7-16.8 23.3-21.1 34.8-5.9 15.3-8.9 33.4-8.9 54.4 0 3.6 0.1 8.9 0.3 16.1l51.2 0.1zM478.2 683.3h60.9v61h-60.9z" fill="#FFFFFF" p-id="77841"></path></svg>';
            }
            html += "&nbsp;" + v.filename + "<small class=\"text-muted ml-2\">("+size+")</small>";
            html += '&nbsp;<a href="'+v.src+'" target="_blank"><svg t="1603093603145" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="102685" width="20" height="20"><path d="M832 128H640v64h147.2L358.4 614.4l51.2 51.2L832 236.8V384h64V128z" fill="#bfbfbf" p-id="102686"></path><path d="M768 832H192V256h320v-64H192c-38.4 0-64 25.6-64 64v576c0 38.4 25.6 64 64 64h576c38.4 0 64-25.6 64-64V512h-64v320z" fill="#bfbfbf" p-id="102687"></path></svg></a>'
            html += '</div>';
            $("#files_container_"+key).append(html);
        });
    }
}
function files_move(key, index, pos){
    var val = $('#field_'+key).val();
    if(val){
        arr = JSON.parse(val);
        arr[index] = arr.splice(index+pos, 1, arr[index])[0];
        $('#field_'+key).val(JSON.stringify(arr));
        $("#files_container_"+key).html('');
        files_render(key);
    }
}
function files_del(key, index){
    var val = $('#field_'+key).val();
    arr = JSON.parse(val);
    arr.splice(index, 1);
    $('#field_'+key).val(JSON.stringify(arr));
    $("#files_container_"+key).html('');
    files_render(key);
}
function files_upload(key){
    var upload_by_form=function(url, file, callback) {
        var data = new FormData();
        data.append('file', file);
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.code) {
                    callback(response);
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Error');
            }
        });
    }
    var fileinput = document.createElement("input");
    fileinput.type = "file";
    fileinput.multiple = "multiple";
    fileinput.onchange=function () {
        $.each(event.target.files, function(indexInArray, valueOfElement) {
            upload_by_form("{$upload_url}", valueOfElement, function(response) {
                if (response.code) {
                    var val = $('#field_'+key).val();
                    if(val){
                        arr = JSON.parse(val);
                    }else{
                        arr = [];
                    }
                    arr.push(response.data);
                    $('#field_'+key).val(JSON.stringify(arr));
                    $("#files_container_"+key).html('');
                    files_render(key);
                } else {
                    alert(response.message);
                }
            });
        });
    }
    fileinput.click();
}
</script>
{/if}
<div class="form-group">
    <label for="field_{:md5($name)}">{$label}</label>
    <input type="text" class="form-control d-none" name="{$name}" value="{$value}" id="field_{:md5($name)}">
    <div class="overflow-hidden" id="files_container_{:md5($name)}"></div>
    <div class="py-3">
        <button type="button" class="btn btn-secondary btn-sm" onclick="files_upload('{:md5($name)}')">{$upload_text??'上传'}</button>
    </div>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                files_render('{:md5($name)}');
            }, 100);
        });
    </script>
    {if isset($help) && $help}
    <small id="help_{:md5($name)}" class="form-text text-muted">{$help}</small>
    {/if}
</div>
str;
    }

    public function __toString()
    {
        return (new Template())->renderFromString($this->getTpl(), get_object_vars($this));
    }
}