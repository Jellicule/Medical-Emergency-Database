// Button initialisation

for (const button of document.getElementsByClassName("page-button")) {
	button.setAttribute("onclick", "openPage(this.id)");
}

// function openPage(buttonId) {
// 	const pageName = buttonId.split("-")[0];
// 	window.open(location.href + "/../" + pageName + ".php");
// }

function openPage(buttonId) {
	const pageName = buttonId.split("-")[0];
	
		window.open("https://www.students.cs.ubc.ca/~jettsl/" + pageName + ".php");
	

}