<?php session_start();
/*
當畫面載入時，要能偵測games資料夾中，有多少的遊戲
並將遊戲資料.json的資訊，放入到$gmaes的陣列中
*/

$games=[];
// 定義games這個陣列
if($handle=opendir('games')){
  //  打開名為 games 的目錄（資料夾） 
  while(false!==($entry=readdir($handle))){
    //  while 迴圈讀取目錄中的每一個檔案或資料夾名稱
    if($entry !="." && $entry !=".."){
      //  排除特殊目錄
      $json_path="games/".$entry."/game.json";
      //  拼湊出該遊戲設定檔的完整路徑
      if(file_exists($json_path)){
        //  檢查該路徑下是否存在 game.json 這個檔案
        $data=json_decode(file_get_contents($json_path),true);
        // file_get_contents 讀取檔案文字
        // json_decode(..., true) 將 JSON 字串轉換成 PHP 的關聯陣列
        $data['path']="games/".$entry."/index.html";
        // 動態新增一個鍵值 path 到資料陣列中
        $games[]=$data;
        // 整理好的單一遊戲資料 $data 推入（Push）一開始建立的大陣列 $games 中
      }
    }
  }  
  closedir($handle);
  //  結束迴圈後，關閉尋找
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FunTech 社群網站 | 遊戲平台</title>
    <link rel="stylesheet" href="by/css/index.css">
    <link rel="stylesheet" href="by/css/bootstrap.css">
    <script src="by/js/jquery-3.7.1.min.js"></script>
    <script>
      // 顯示區塊script
      function receiveGameResult(data){
        $("#gameTitle").text(data.game)
        $("#gameStatus").text(data.data.result)
        $("#gameTime").text(data.data.time)
        $("#resultBlock").removeClass("d-none")
        $("#resultBlock").addClass("d-block")
        console.log(data) 
      }
      function openGame(url,title){
        window.open(url,title,'GameWindow','width=800,height=600')
      }
    </script>
</head>
<body>
  
<?php include_once "header.php";?>

<div id="main-content" class='container' style='height:800px'>
    <h1 class="text-center my-4">
      遊戲平台
    </h1>
  <!-- 對應上方script
           function receiveGameResult(data)
  -->
    <div id="resultBlock" class="border rounded bg-success text-white p-3 d-none">
        <div>遊戲名稱:<span id="gameTitle"></span></div>
        <div>遊戲狀態:<span id="gameStatus"></span></div>
        <div>遊戲時間:<span id="gameTime"></span></div>
    </div>
    <?php
      // $games=[
      //   ["title"=>"數字挑戰","description"=>"依序點擊數字，按升序完成挑戰"],
      //   ["title"=>"記憶挑戰","description"=>"找出相同圖案的卡牌"],
      //   ["title"=>"絕地反攻","description"=>"在時間內擊落最多的敵機"],
      //   ];
        foreach($games as $games):
    ?>
    <div class="row flex-wrap">
      <div class="w-50 p-5">
        <div class="border rounded p-2">
          <div id="game_name">遊戲名稱: <?=$games['title']?></div>
          <div id="game_description">遊戲說明:<?=$games['description']?></div>
          <button class="btn btn-info"
          onclick="openGame('<?=$games['path'];?>')"
          >PLAY</button>
          <!-- 當使用者點擊這個按鈕時，會呼叫一個名為 openGame 的JS函式 -->
        </div>
        </div>
      </div>
      <?php
        endforeach;
        // 結束的語法
      ?>
    </div>
</div>
<?php include_once "footer.php";?>
<script src="css/bootstrap.js"></script>
</body>

</html>
