<!--It is footer for all the pages-->

</div><!--to close container -->
<div id="push"></div>
</div><!--to close wrap -->
<div id="footer" style="border-top:1px solid #e0e0e0">
<div class="container">
<center><p class="muted credit"><font color="#f5f5f5" > BMW | Sid </font>Copyrights &copy; OEP @ IIITD&amp;M Kancheepuram 2014<font color="#f5f5f5" > RK | SS | KD </font></p></center>
</div>
</div>


    <script src="js/widgets.js"></script>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap-transition.js"></script>
    <script src="js/bootstrap-alert.js"></script>
    <script src="js/bootstrap-modal.js"></script>
    <script src="js/bootstrap-dropdown.js"></script>
    <script src="js/bootstrap-scrollspy.js"></script>
    <script src="js/bootstrap-tab.js"></script>
    <script src="js/bootstrap-tooltip.js"></script>
    <script src="js/bootstrap-popover.js"></script>
    <script src="js/bootstrap-button.js"></script>
    <script src="js/bootstrap-collapse.js"></script>
    <script src="js/bootstrap-carousel.js"></script>
    <script src="js/bootstrap-typeahead.js"></script>
    <script src="js/bootstrap-affix.js"></script>
    <script src="js/holder.js"></script>
    <script src="js/prettify.js"></script>
    <script src="js/application.js"></script>
	<script type="text/javascript">
         $(document).ready(function () {
             $(".input").forceNumeric();
         });
         jQuery.fn.forceNumeric = function () {

             return this.each(function () {
                 $(this).keydown(function (e) {
                     var key = e.which || e.keyCode;

                     if (!e.shiftKey && !e.altKey && !e.ctrlKey &&
                     // numbers   
                         key >= 48 && key <= 57 ||
                     // Numeric keypad
                         key >= 96 && key <= 105 ||
                     //  minus, and colon
                         key == 59 || key == 173 ||
                     // Backspace and Tab and Enter
                        key == 8 || key == 9 || key == 13 ||
                     // Home and End
                        key == 35 || key == 36 ||
                     // left and right arrows
                        key == 37 || key == 39 ||
                     // Del and Ins
                        key == 46 || key == 45)
                         return true;

                     return false;
                 });
             });
         }
    </script>
	
	<script src="js/script.js"></script>
<!--to close the compositon -->
</body>
</html>