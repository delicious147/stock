<?php
use yii\helpers\Html;

?>

        <div id="app">
            <ol>
                <li v-for="site in sites">
                    {{ site }}
                </li>
            </ol>
        </div>



<?php
$this->registerJsFile("https://cdn.staticfile.org/vue/2.2.2/vue.min.js");
$js = <<<JS
var arr=JSON.parse('$arr'); 
new Vue({
  el: '#app',
  data: {
    sites: arr
  }
})
JS;
$this->registerJs($js);

?>