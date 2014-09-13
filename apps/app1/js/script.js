/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
	
	$('.btnDelete').click(function(e){
		if(!confirm('Bạn có muốn xóa không?')) {
			e.preventDefault();
		}
	});
	
});

