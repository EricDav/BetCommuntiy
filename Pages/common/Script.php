<script src="/bet_community/Public/js/jquery-3.1.1.min.js"></script>
<script src="/bet_community/Public/js/bootstrap.min.js"></script>
<script src="/bet_community/Public/js/jquery.sticky-kit.min.js"></script>
<script src="/bet_community/Public/js/jquery.scrollbar.min.js"></script>
<script src="/bet_community/Public/js/script.js"></script>
<script src = '/bet_community/Public/js/contact.js'></script>
<?php if (isLogin()): ?>
    <script type="text/javascript">var token=<?=json_encode($data['token']);?>;</script>
    <script type="text/javascript">var $$id=<?=json_encode($request->session['userInfo']['specialId'] . $request->session['userInfo']['id']);?>;</script>
    <script> localStorage.setItem('$$token', token);</script>
<?php endif; ?>
<?php if (!isLogin()): ?>
    <script type="text/javascript">var $$id=<?=json_encode(-1);?>;</script>
<?php endif; ?>

