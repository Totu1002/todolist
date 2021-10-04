function confirmFunction(){
  //確認ダイアログ表示
  var without_res = confirm("I will cancel the membership, is that really okay?");
  if (without_res === true){
    return true;
  } else {
    return false;
  }
}