'use strict';

{
  const set_btn = document.getElementById('set_btn');
  const set_text = document.getElementById('set_text');
  const ul = document.querySelector('ul');


  ul.addEventListener('click', e => {
    const t = e.target;
    const p = t.parentNode;

    // ゴミ箱ボタンを押した時に削除画面が表示される
    if (t.classList.contains('fa-trash-alt')) {
      const de = p.nextElementSibling;
      de.classList.remove('done');

      //ペンボタンを押した時に編集画面が表示される
    } else if (t.classList.contains('fa-pen')) {
      const ed = p.nextElementSibling.nextElementSibling;
      ed.classList.remove('done');

      //削除画面でキャンセルを押すと削除画面が閉じる
    } else if (t.classList.contains('d__btn')) {
      const section = t.closest('.d_block');
      section.classList.add('done');

      //編集画面でキャンセルが押されるを編集画面が閉じる
    } else if (t.classList.contains('e__btn')) {
      const section = t.closest('.e_block');
      section.classList.add('done');
    }
  });
  ;




}
