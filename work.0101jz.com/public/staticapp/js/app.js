$(function() {
document.getElementById("mya").innerHTML="<div id='footnav' ><a href='/app/work'><i class='fa fa-clock-o fa-fw' aria-hidden='true'></i><span>工单</span></a> "
+ "<a href='/app/lore'><i class='fa fa-clock-o fa-fw' aria-hidden='true'></i><span>学习</span</a>"
+ "<a href='/app/customer'><i class='fa fa-clock-o fa-fw' aria-hidden='true'></i><span>客户</span</a>"
+"<a href='/app/problem/add'><i class='fa fa-clock-o fa-fw' aria-hidden='true'></i><span>反馈</span></a></div>"

});


function myFunction()
{
var y=5;
var x="<a href='study.html'><i class='fa fa-clock-o fa-fw' aria-hidden='true'></i><span>学习</span</a>";
var demoP=document.getElementById("demo")
	demoP.innerHTML="x=" + x;
}

