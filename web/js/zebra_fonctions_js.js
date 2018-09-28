//
//
//   var coulCadre;
//   var coulInt;
//   var bgCase = new Array();
//   var bcCase = new Array();
//   var ongletSel;
//
//   var indexSuivant = 0;
//   var filtre = new Array();
//
//  function editionEtiquette(bl_scanne){
//
//     if ( document.getElementById('searchBLviaTracking').checked == true ) {
//         var asUrl = "chercheBLviaTracking.php?numTracking=" + document.getElementById('numBLscanne').value;
//         var xhr = new XMLHttpRequest();
//
//         xhr.open('GET', asUrl, true); // --- Asynchrone
//       	xhr.onreadystatechange = function()
//         {if(xhr.readyState == 4 && xhr.status == 200)
//         		{
//               var resultat = xhr.responseText;
//               resultat = reverseHTMLentities(resultat);
//               document.getElementById('numBLscanne').value = resultat;
//               printEtiqZpl(resultat + ".zpl");
//         		}};
//       	xhr.send(null);
//
//     } else {
//       printEtiqZpl(bl_scanne);
//
//     }
//  }
//
//
//
//
//   function printEtiqZpl(etiq)
//   {
// 	var gdhCour = new Date();
// 	var dossierRacine = "K:\\";
// 	var wShell = new ActiveXObject("WScript.Shell");
// 	var fso = new ActiveXObject("Scripting.FileSystemObject");
// 	var fichierZPL = dossierRacine + etiq;
// 	var txt_numBLscanne = document.getElementById('numBLscanne');
// 	var cde = "%comspec% /c DIR " + dossierRacine + txt_numBLscanne.value + "* > " + dossierRacine + "stdout" + txt_numBLscanne.value;
//
// 	//ex?cute la commande DIR ? la recherche de l'?tiquette
// 	wShell.Run(cde, 0, true);
// 	// ouvre le fichier stdout et recherche si le fichier portant le n? BL a ?t? trouv?
// 	ficStdOut = fso.GetFile(dossierRacine + "stdout" + txt_numBLscanne.value);
// 	if (fso.FileExists(ficStdOut))
// 	{
// 		fictmp = ficStdOut.OpenAsTextStream(1, -2);
// 		while (! fictmp.AtEndOfStream)
// 		{
// 			var textLine = fictmp.ReadLine();
// 			var posOc = textLine.indexOf(txt_numBLscanne.value + ".zpl", 0)
// 			if ( posOc >= 0) { fichierZPL = dossierRacine + textLine.substring(posOc, textLine.length); break;}
// 		}
// 	}
// 	//ferme et supprime le fichier stdout
// 	fictmp.Close();
// 	fso.DeleteFile(dossierRacine + "stdout" + txt_numBLscanne.value);
// 	//v?rifie si le fichier existe
// 	if (fso.FileExists(fichierZPL))
// 	{
// 		//connexion en base afin de r?cup?rer le poids du colis
// 		var asUrl = 'recupPoidsColis.php?numBL=' + txt_numBLscanne.value;
// 		var xhr = new XMLHttpRequest();
// 		xhr.open('GET', asUrl, true); // --- Asynchrone
// 		xhr.onreadystatechange = function()
// 		{
// 			if(xhr.readyState == 4 && xhr.status == 200)
// 			{
// 				var tblArea = xhr.responseText.split("[#SEP#]");
// 				var poids = tblArea[0].slice(-4);
// 				var poidsKG = parseFloat(poids) / 100;
// 				var destinataire = tblArea[1];
// 				var cpVillePays = tblArea[2];
// 				var typeTransport = tblArea[3];
// 				var message = tblArea[4]
// 				var personnalisation = tblArea[5];
// 				var produit = tblArea[6];
// 				var client = tblArea[7];
// 				var AppliName = tblArea[8];
// 				var ContenuImg = tblArea[9];
// 				var statuts = tblArea[10];
// 				var msgAnnule = tblArea[11];
// 				if ( destinataire == "NR" )
// 				{
// 					alert("Le BL " + txt_numBLscanne.value + " n'existe pas : abandon de l'impression de l'?tiquette de transport.\nPensez ? changer d'interface si vous ?tes en production SG ...");
// 					txt_numBLscanne.value = "";
// 					txt_numBLscanne.focus();
// 					return false;
// 				}
// 				var previewLabel = "";
//
// 				//GDH courant
// 				var GDH = "";
// 				var GDHformate = "";
// 				GDHetiquette = ""
//
// 				e = gdhCour.getDate();
// 				GDH += ((e < 10) ? ("0" + e) : e);
// 				GDHformate += ((e < 10) ? ("0" + e) : e);
// 				GDHetiquette += ((e < 10) ? ("0" + e) : e);
//
// 				e = gdhCour.getMonth() + 1;
// 				GDH += ((e < 10) ? ("0" + e) : e);
// 				GDHformate += "." + ((e < 10) ? ("0" + e) : e);
// 				GDHetiquette += "/" + ((e < 10) ? ("0" + e) : e);
//
// 				GDH += ((gdhCour.getFullYear()).toString()).substr(2, 2);
// 				GDHformate += "." + ((gdhCour.getFullYear()).toString()).substr(2, 2);
// 				GDHetiquette += "/" + ((gdhCour.getFullYear()).toString());
//
// 				e = gdhCour.getHours();
// 				GDH += ((e < 10) ? ("0" + e) : e);
// 				GDHformate += "-" + ((e < 10) ? ("0" + e) : e);
//
// 				e = gdhCour.getMinutes();
// 				GDH += ((e < 10) ? ("0" + e) : e);
// 				GDHformate += ":" + ((e < 10) ? ("0" + e) : e);
//
// 				e = gdhCour.getSeconds();
// 				GDH += ((e < 10) ? ("0" + e) : e);
// 				GDHformate += ":" + ((e < 10) ? ("0" + e) : e);
//
// 				// ouvre le .zpl et encapsule le contenu dans la variable 'contenuZPL'
// 				ficZPL = fso.GetFile(fichierZPL);
// 				fictmp = ficZPL.OpenAsTextStream(1, -2);
// 				contenuZPL = fictmp.ReadAll();
//
//
// 				//modification du contenu : marque la date courante, et le poids (en KG et en dizaine de gr), la cle
//
// 				contenuZplModifie = contenuZPL.replace("#DATE#", GDHetiquette);
// 				contenuZplModifie = contenuZplModifie.replace("#POIDSKG#", poidsKG);
//
// 				var numColissimo = "";
// 				var textLine = "";
// 				var posOc = 0;
// 				var cle = 0;
//
// 				//referme le fichier
// 				fictmp.Close();
//
// 				//cartouche infos du BL scann?
// 				GDHimp = "le " + GDHformate.substr(0, 8) + " ? <span style='font-size: 1.1em; font-weight: bold;'>" + GDHformate.substr(9, 2) + "H" + GDHformate.substr(12, 5) + "</span>";
// 				var cartouche = "<span style='text-decoration: underline; font-style: italic;'>Derni?re ?tiquette ?dit?e</span> : " + GDHimp + "<br /><br /><center><div style='margin-top: 20px; width: 95%;'><table width='100%'>"
// 				cartouche += "<tr valign='top' height='20'><td style='width: 10%; text-align: right;'><span style='text-decoration: underline; font-size: 1em; font-style: italic;'>n? BL</span> : </td><td style='padding-left: 5px;'><span style='font-size: 1.3em; color: blue; font-weight: bold;'>" + txt_numBLscanne.value + "</span></td></tr>";
// 				cartouche += "<tr valign='top' height='20'><td style='text-align: right;'><span style='text-decoration: underline; font-size: 1em; font-style: italic;'>Transport</span> : </td><td style='padding-left: 5px;'><span style='font-size: 1.2em; color: #3333CC;'>" + typeTransport + "  /  </span>";
// 				cartouche += "<span style='text-decoration: underline; font-size: 1em; font-style: italic;'>Poids colis</span> : <span style='font-size: 1.2em;'>" + poidsKG + "</span> Kg</td></tr>";
// 				cartouche += "<tr valign='top'><td style='text-align: right; padding-top: 15px;'><span style='text-decoration: underline; font-size: 1em; font-style: italic;'>Destinataire</span> :</span></td><td style='padding-left: 0px; padding-top: 7px;'><span style='font-size: 1.4em; color: blue; font-weight: bold;'>" + destinataire + "</span><br /><br /><span style='padding-top: 20px; font-size: 1.3em; font-weight: bold;'>" + cpVillePays + "</span></td></tr>";
// 				cartouche += "<tr valign='top'><td style='text-align: right; padding-top: 20px;'><span style='text-decoration: underline; font-size: 1em; font-style: italic;'>Message</span> :</span></td><td style='padding-left: 5px; padding-top: 20px;'><span style='font-size: 1.3em; color: #990000; font-weight: bold;'>" + message + "</span></td></tr>";
// 				cartouche += "<tr valign='top'><td style='text-align: right; padding-top: 20px;'><span style='text-decoration: underline; font-size: 1em; font-style: italic;'>Perso / Consigne</span> :</span></td><td style='padding-left: 5px; padding-top: 15px;'><span style='font-size: 1.4em; color: #990000; font-weight: bold;'>" + personnalisation + "</span></td></tr>";
// 				cartouche += "<tr valign='top'><td style='text-align: right; padding-top: 5px;'><span style='text-decoration: underline; font-size: 1em; font-style: italic;'>Qt? / Produit ? livrer</span> :</span></td><td style='padding-left: 5px; padding-top: 20px;'><span style='font-size: 1.5em; color: #990000; font-weight: bold;'>" + produit + "</span></td></tr>";
// 				cartouche += "</div></center>";
// 				// Infos clients
// 				var InfosClient = "<td><span style=' font-size: 1.4em;'>Client </span> : </td><td style='padding-left: 5px;'><span style='font-size: 1.4em; color: #188351; font-weight: bold;'>" + client + "<br /><br /><div style='margin-top: 20px; width: 95%;'><table width='100%'>"
// 				InfosClient += "<tr valign='top' height='20'><td style='width: 20%; padding-top: 0px; text-align: left;'><span style='font-size: 1em;'>Application </span> : </td><td style='padding-left: 0px;'><span style='font-size: 1.1em; color: #e465d8; font-weight: bold;'>" + AppliName + "</span></td></tr>";
// 				InfosClient += "</table></div>";
//
//
// 				//Affichage du cartouche infos du BL scann? + aper?u de l'?tiquette
// 				document.getElementById('areacli').innerHTML =InfosClient;
// 				document.getElementById('areaimg').innerHTML =ContenuImg;
// 				document.getElementById('area2').innerHTML =cartouche;
// 				document.getElementById('area3').innerHTML = previewLabel;
// 				document.getElementById('status').innerHTML = statuts;
//
// //				f = fso.GetFolder(repertoire);
// //				var NameFile;
// //				var f1 = new Enumerator(f.files);
// //				for (; !f1.atEnd(); f1.moveNext())
// //				{
// //					if (f1.items.contains(txt_numBLscanne.value) )
// //					{
// //						NameFile = f1.item()
// //					}
// //				}
//
// 				if (msgAnnule.length > 0)
// 				{
// 					alert(msgAnnule);
// 					//txt_numBLscanne.value = "";
// 					txt_numBLscanne.focus();
// 				}
//
// 				//imprime l'?tiquette
// 				if (document.getElementById('printEtiquette').checked == true)
// 				{
// 					//cr?e un fichier temporaire, y incopore le contenu modifi?
// 					cheminFicImpZPL = dossierRacine + "tmpPrint" + txt_numBLscanne.value + ".zpl";
// 					ficImpZPL = fso.CreateTextFile(cheminFicImpZPL, true);
// 					ficImpZPL.Write(contenuZplModifie);
// 					ficImpZPL.Close();
//
// 					var commande = "c:\\WINDOWS\\ssdal.exe /p zebraSg send " + cheminFicImpZPL;
// 					wShell.Run(commande, 0, true);
//
// 					//supprime le fichier temporaire
// 					fso.DeleteFile(cheminFicImpZPL);
//
// 					//renomme le fichier .zpl en .old
// 					//ficZPL.name = etiq + "_" + GDH + "-" + dossier + "-3.old"
// 					//enregistre la saisie dans la base sg_log, table sg_filigrane_bl
//
// 					var asUrl2 = 'recordProdColis.php' + '?numBL=' + txt_numBLscanne.value;
//
// 					if (document.getElementById('chkDateDepot'))
// 					{
// 						if (document.getElementById('chkDateDepot').checked == true)
// 						{
// 							asUrl2 += "&dateDepot=" + document.getElementById('dateDepot').value;
// 						}
// 					}
//
// 					var xhr2 = new XMLHttpRequest();
// 					xhr2.open('GET', asUrl2, true); // --- Asynchrone
// 					xhr2.onreadystatechange = function()
// 					{
// 						if(xhr2.readyState == 4 && xhr2.status == 200)
// 						{
// 						}
// 					};
// 					xhr2.send(null);
// 				}
//
// 				//efface le contenu de la zone de texte n? BL
// 				txt_numBLscanne.value = "";
// 				txt_numBLscanne.focus();
//
// 			}
// 		};
// 		xhr.send(null);
//
//
//     }
// 	else
// 	{
// 		//connexion en base afin de r?cup?rer le poids du colis
// 		var asUrl = 'recupPoidsColis.php?numBL=' + txt_numBLscanne.value;
// 		var xhr = new XMLHttpRequest();
// 		xhr.open('GET', asUrl, true); // --- Asynchrone
// 		xhr.onreadystatechange = function()
// 		{
//
// 			if(xhr.readyState == 4 && xhr.status == 200)
// 			{
//
// 				var tblArea = xhr.responseText.split("[#SEP#]");
// 				var poids = tblArea[0].slice(-4);
// 				var poidsKG = parseFloat(poids) / 100;
// 				var destinataire = tblArea[1];
// 				var cpVillePays = tblArea[2];
// 				var typeTransport = tblArea[3];
// 				var message = tblArea[4]
// 				var personnalisation = tblArea[5];
// 				var produit = tblArea[6];
// 				var client = tblArea[7];
// 				var AppliName = tblArea[8];
// 				var ContenuImg = tblArea[9];
// 				var statuts = tblArea[10];
// 				var msgAnnule = tblArea[11];
// 				if ( destinataire == "NR" )
// 				{
// 					alert("Le BL " + txt_numBLscanne.value + " n'existe pas : abandon de l'impression de l'?tiquette de transport.\nPensez ? changer d'interface si vous ?tes en production SG ...");
// 					txt_numBLscanne.value = "";
// 					txt_numBLscanne.focus();
// 					return false;
// 				}
// 				var previewLabel = "";
// 				//GDH courant
// 				var GDH = "";
// 				var GDHformate = "";
// 				GDHetiquette = ""
//
// 				e = gdhCour.getDate();
// 				GDH += ((e < 10) ? ("0" + e) : e);
// 				GDHformate += ((e < 10) ? ("0" + e) : e);
// 				GDHetiquette += ((e < 10) ? ("0" + e) : e);
//
// 				e = gdhCour.getMonth() + 1;
// 				GDH += ((e < 10) ? ("0" + e) : e);
// 				GDHformate += "." + ((e < 10) ? ("0" + e) : e);
// 				GDHetiquette += "/" + ((e < 10) ? ("0" + e) : e);
//
// 				GDH += ((gdhCour.getFullYear()).toString()).substr(2, 2);
// 				GDHformate += "." + ((gdhCour.getFullYear()).toString()).substr(2, 2);
// 				GDHetiquette += "/" + ((gdhCour.getFullYear()).toString());
//
// 				e = gdhCour.getHours();
// 				GDH += ((e < 10) ? ("0" + e) : e);
// 				GDHformate += "-" + ((e < 10) ? ("0" + e) : e);
//
// 				e = gdhCour.getMinutes();
// 				GDH += ((e < 10) ? ("0" + e) : e);
// 				GDHformate += ":" + ((e < 10) ? ("0" + e) : e);
//
// 				e = gdhCour.getSeconds();
// 				GDH += ((e < 10) ? ("0" + e) : e);
// 				GDHformate += ":" + ((e < 10) ? ("0" + e) : e);
// 				//cartouche infos du BL scann?
// 				//cartouche infos du BL scann?
// 				GDHimp = "le " + GDHformate.substr(0, 8) + " ? <span style='font-size: 1.1em; font-weight: bold;'>" + GDHformate.substr(9, 2) + "H" + GDHformate.substr(12, 5) + "</span>";
// 				var cartouche = "<span style='text-decoration: underline; font-style: italic;'>Derni?re ?tiquette ?dit?e</span> : " + GDHimp + "<br /><br /><center><div style='margin-top: 20px; width: 95%;'><table width='100%'>"
// 				cartouche += "<tr valign='top' height='20'><td style='width: 10%; text-align: right;'><span style='text-decoration: underline; font-size: 1em; font-style: italic;'>n? BL</span> : </td><td style='padding-left: 5px;'><span style='font-size: 1.3em; color: blue; font-weight: bold;'>" + txt_numBLscanne.value + "</span></td></tr>";
// 				cartouche += "<tr valign='top' height='20'><td style='text-align: right;'><span style='text-decoration: underline; font-size: 1em; font-style: italic;'>Transport</span> : </td><td style='padding-left: 5px;'><span style='font-size: 1.2em; color: #3333CC;'>" + typeTransport + "</span></td></tr>";
// 				cartouche += "<tr valign='top' height='20'><td style='text-align: right;'><span style='text-decoration: underline; font-size: 1em; font-style: italic;'>Poids colis</span> : </td><td style='padding-left: 5px;'><span style='font-size: 1.2em;'>" + poidsKG + "</span> Kg</td></tr>";
// 				cartouche += "<tr valign='top'><td style='text-align: right; padding-top: 15px;'><span style='text-decoration: underline; font-size: 1em; font-style: italic;'>Destinataire</span> :</span></td><td style='padding-left: 0px; padding-top: 7px;'><span style='font-size: 1.4em; color: blue; font-weight: bold;'>" + destinataire + "</span><br /><br /><span style='padding-top: 20px; font-size: 1.3em; font-weight: bold;'>" + cpVillePays + "</span></td></tr>";
// 				cartouche += "<tr valign='top'><td style='text-align: right; padding-top: 20px;'><span style='text-decoration: underline; font-size: 1em; font-style: italic;'>Message</span> :</span></td><td style='padding-left: 5px; padding-top: 20px;'><span style='font-size: 1.3em; color: #990000; font-weight: bold;'>" + message + "</span></td></tr>";
// 				cartouche += "<tr valign='top'><td style='text-align: right; padding-top: 20px;'><span style='text-decoration: underline; font-size: 1em; font-style: italic;'>Perso / Consigne</span> :</span></td><td style='padding-left: 5px; padding-top: 15px;'><span style='font-size: 1.4em; color: #990000; font-weight: bold;'>" + personnalisation + "</span></td></tr>";
// 				cartouche += "<tr valign='top'><td style='text-align: right; padding-top: 20px;'><span style='text-decoration: underline; font-size: 1em; font-style: italic;'>Qt? / Produit ? livrer</span> :</span></td><td style='padding-left: 5px; padding-top: 20px;'><span style='font-size: 1.5em; color: #990000; font-weight: bold;'>" + produit + "</span></td></tr>";
// 				cartouche += "</div></center>";
// 				var InfosClient = "<td><span style=' font-size: 1.4em;'>Client </span> : </td><td style='padding-left: 5px;'><span style='font-size: 1.4em; color: #188351; font-weight: bold;'>" + client + "<br /><br /><div style='margin-top: 20px; width: 95%;'><table width='100%'>"
// 				InfosClient += "<tr valign='top' height='20'><td style='width: 20%; padding-top: 0px; text-align: left;'><span style='font-size: 1em;'>Application </span> : </td><td style='padding-left: 0px;'><span style='font-size: 1.1em; color: #e465d8; font-weight: bold;'>" + AppliName + "</span></td></tr>";
// 				InfosClient += "</table></div>";
//
// 				//Affichage du cartouche infos du BL scann? + aper?u de l'?tiquette
// 				document.getElementById('areacli').innerHTML =InfosClient;
// 				document.getElementById('areaimg').innerHTML =ContenuImg;
// 				document.getElementById('area2').innerHTML =cartouche;
// 				document.getElementById('area3').innerHTML = previewLabel;
// 				document.getElementById('status').innerHTML = statuts;
// 				if (msgAnnule.length > 0)
// 				{
// 					alert(msgAnnule);
// 					txt_numBLscanne.value = "";
// 					txt_numBLscanne.focus();
// 					return false;
// 				}
// 			}
// 		};
// 		var message = "L'?tiquette ZPL correspondant au BL n? " + txt_numBLscanne.value + " n'a pas ?t? trouv?e.\n Impression ?tiquette abandonn?e.";
// 		alert (message);
// 		txt_numBLscanne.style.color = "red";
// 		txt_numBLscanne.style.background = "#FFD0E0";
// 		txt_numBLscanne.focus();
// 		txt_numBLscanne.select();
// 		xhr.send(null);
//     }
// } // parenth?se fermante de function
//
//
// function interrogeBL(numBL){
//
//     var asUrl = "interrogeBL.php?numBL=" + numBL;
//     var xhr = new XMLHttpRequest();
//
//     xhr.open('GET', asUrl, true); // --- Asynchrone
//   	xhr.onreadystatechange = function()
//     {if(xhr.readyState == 4 && xhr.status == 200)
//     		{
//           var resultat = xhr.responseText;
//           resultat = reverseHTMLentities(resultat);
//           document.getElementById('zoneReponseBL').innerHTML = resultat;
//     		}};
//   	xhr.send(null);
//
// }
//
//
// function chercheBLviaTracking(numTracking) {
//     var asUrl = "chercheBLviaTracking.php?numTracking=" + numTracking;
//     var xhr = new XMLHttpRequest();
//
//     xhr.open('GET', asUrl, true); // --- Asynchrone
//   	xhr.onreadystatechange = function()
//     {if(xhr.readyState == 4 && xhr.status == 200)
//     		{
//           var resultat = xhr.responseText;
//           resultat = reverseHTMLentities(resultat);
//           document.getElementById('numBLscanne').value = resultat;
//     		}};
//   	xhr.send(null);
//
// }
//
// function synthPRODzpl(){
//
//     var asUrl = "synthPRODzpl.php";
//     var xhr = new XMLHttpRequest();
//
//     xhr.open('GET', asUrl, true); // --- Asynchrone
//   	xhr.onreadystatechange = function()
//     {if(xhr.readyState == 4 && xhr.status == 200)
//     		{
//           var resultat = xhr.responseText;
//           resultat = reverseHTMLentities(resultat);
//           var tblArea = resultat.split("[###]");
//
//           document.getElementById('area2').innerHTML = tblArea[0];
//           document.getElementById('area3').innerHTML = tblArea[1];
//
//           var nl = document.getElementsByName('trLi').length;
//           filtre = new Array();
//           for (n = 0 ; n < nl ; n++) {
//             filtre[n] = new Array();
//             filtre[n][0] = 1;
//             filtre[n][1] = 1;
//             filtre[n][2] = 1;
//             filtre[n][3] = 1;
//           }
//           document.getElementById('spanCptLignesAffichees').innerHTML = '<span style="font-size: 1.1em; color: blue; font-weight: bold;">' + nl + '</span><span style="font-size: 0.9em; color: black;"> / ' + nl + '</span>';
//     		}};
//   	xhr.send(null);
//
// }
//
// function majPRODzpl(){
//
//     var asUrl = "majPRODzpl.php";
//     var xhr = new XMLHttpRequest();
//
//     xhr.open('GET', asUrl, true); // --- Asynchrone
//   	xhr.onreadystatechange = function()
//     {if(xhr.readyState == 4 && xhr.status == 200)
//     		{
//           var resultat = xhr.responseText;
//           alert (resultat);
//
//     		}};
//   	xhr.send(null);
//
// }
//
//
//
// function verifEtiqzpl(){
//
//     var asUrl = "verifEtiqzpl.php";
//     var xhr = new XMLHttpRequest();
//
//     xhr.open('GET', asUrl, true); // --- Asynchrone
//   	xhr.onreadystatechange = function()
//     {if(xhr.readyState == 4 && xhr.status == 200)
//     		{
//           var resultat = xhr.responseText;
//           resultat = reverseHTMLentities(resultat);
//           var tblArea = resultat.split("[###]");
//
//           document.getElementById('area2').innerHTML = tblArea[0];
//           document.getElementById('area3').innerHTML = tblArea[1];
//
//           var nl = document.getElementsByName('trLi').length;
//           filtre = new Array();
//           for (n = 0 ; n < nl ; n++) {
//             filtre[n] = new Array();
//             filtre[n][0] = 1;
//             filtre[n][1] = 1;
//             filtre[n][2] = 1;
//             filtre[n][3] = 1;
//           }
//           document.getElementById('spanCptLignesAffichees').innerHTML = '<span style="font-size: 1.1em; color: blue; font-weight: bold;">' + nl + '</span><span style="font-size: 0.9em; color: black;"> / ' + nl + '</span>';
//     		}};
//   	xhr.send(null);
//
// }
//
// function Search_cle(code) {
//     var SP = 0, SI = 0, cle = 0;
//     //calcul : somme des chiffres pairs
//     for (var i=8; i<=22; i+=2) {
//                   SP += parseInt(code.charAt(i));
//     }
//     //calcul : somme des chiffres impairs
//     for (var i=9; i<=21; i+=2) {
//                   SI += parseInt(code.charAt(i));
//     }
//     // le reste de la division par 10 ou bien si reste= 10 -> 0
//     cle = (10-((SP*3) + SI) % 10) % 10;
//    return (cle);
// }
//
// function dropNext() {
//
//   var imgstatut = document.getElementsByName('imgStatus');
//   if (indexSuivant >= imgstatut.length) {indexSuivant = 0;}
//   for (var i = indexSuivant + 1; i < imgstatut.length; i++) {
//     if ( (imgstatut[i].src).indexOf("gris") > 0 && (imgstatut[i+1].src).indexOf("vert") > 0 ) {
//       var valScroll = (i * 24) - (parseInt(document.getElementById('divTblReponse').style.height) / 2);
//       document.getElementById('divTblReponse').scrollTop = valScroll;
//       indexSuivant = i;
//       break;
//     }
//   }
// }
//
// function infosLigne(numBL, numBonPrepa, numColissimofic, destinataire, adresseDestinataire) {
//
//
// var txtTd = "<div style='margin-left: 5%; margin-top: 10%; width: 95%;'><table width='100%'>"
//     txtTd += "<tr height='24'><td width='40%'><span style='text-decoration: underline; font-size: 1em; font-style: italic;'>n? BL</span> : </td><td><span style='font-size: 1.2em; color: blue;'>" + numBL + "</span></td></tr>";
//     txtTd += "<tr height='24'><td><span style='text-decoration: underline; font-size: 1em; font-style: italic;'>n? BON PREPA</span> : </td><td><span style='font-size: 1.2em; color: #990000;'>" + numBonPrepa + "</span></td></tr>";
//     txtTd += "<tr height='24'><td><span style='text-decoration: underline; font-size: 1em; font-style: italic;'>n? Colissimo</span> : </td><td><span style='font-size: 1.2em; color: #006600;'>" + numColissimofic + "</span></td></tr>";
//     txtTd += "<tr height='24'><td colspan='2'><span style='text-decoration: underline; font-size: 1em; font-style: italic;'>Destinataire</span> :</span></td></tr>";
//     txtTd += "<tr><td colspan='2'><div style='margin-left: 20px;'><span style='font-size: 1.4em; color: blue; font-weight: bold;'>" + destinataire + "</span><br /><span style='font-size: 1.2em;'>" + adresseDestinataire + "</span></div></td></tr>";
//     txtTd += "</table></div>";
//     document.getElementById('tdZoom').innerHTML = txtTd;
// }
//
// function filtreLigne(valSelect, champ, indexChamp) {
//
//   var ligne = document.getElementsByName('trLi');
//   for (var i = 0; i < filtre.length; i++) {
//     if ( valSelect == "tous" ) {
//       filtre[i][indexChamp] = 1;
//     } else {
//       if ((document.getElementById(champ + i).innerHTML).indexOf(valSelect) >= 0) {
//         filtre[i][indexChamp] = 1;
//       } else {
//         filtre[i][indexChamp] = 0;
//       }
//     }
//   }
//
//   var f = 0;
//   for (var i = 0; i < filtre.length; i++) {
//     if ((parseInt(filtre[i][0]) + parseInt(filtre[i][1]) + parseInt(filtre[i][2])+ parseInt(filtre[i][3])) == 4)
//       { ligne[i].style.display = 'table-row'; f++; } else { ligne[i].style.display = 'none';}
//   }
//   document.getElementById('spanCptLignesAffichees').innerHTML = '<span style="font-size: 1.1em; color: blue; font-weight: bold;">' + f + '</span><span style="font-size: 0.9em; color: black;"> / ' + filtre.length + '</span>';
//
//   intitFiltre = "";
//   if (document.getElementById('selectDateFicPROD').value != "tous")
//   {intitFiltre += '<span style="margin-left: 30px; text-decoration: underline;">Filtre 1</span> : Fichiers du <span style="color: blue; font-weight: bold;">' + document.getElementById('selectDateFicPROD').value + '</span>';}
//
//
//   if (document.getElementById('selectPoids').value != "tous") {
//     intitFiltre += '<span style="margin-left: 30px; text-decoration: underline;">' + ((intitFiltre == '') ? 'Filtre 1' : 'Filtre 2') + '</span> : ';
//
//     switch (document.getElementById('selectPoids').value) {
//       case "g":
//         intitFiltre += '<span style="color: blue; font-weight: bold;">Poids Colis renseign?s</span>';
//         break;
//       case "NR":
//         intitFiltre += '<span style="color: red; font-weight: bold;">Poids Colis NR</span>';
//         break;
//     }
//   }
//
//
//   if (document.getElementById('selectSitePROD').value != "tous") {
//     intitFiltre += '<span style="margin-left: 30px; text-decoration: underline;">' + ((intitFiltre == '') ? 'Filtre 1' : 'Filtre 3') + '</span> : ';
//
//     switch (document.getElementById('selectSitePROD').value) {
//       case ".":
//         intitFiltre += '<span style="color: red; font-weight: bold;">Fichiers non produits</span>';
//         break;
//       case "TMD 2":
//         intitFiltre += 'Fichiers produits sur <span style="color: blue; font-weight: bold;">Chaine SEMI-AUTO</span>';
//         break;
//       case "SAVOYE":
//         intitFiltre += 'Fichiers produits sur <span style="color: blue; font-weight: bold;">Chaine SAVOYE</span>';
//         break;
//     }
//   }
//
//
//   if (intitFiltre == "") {intitFiltre = '<span style="color: blue;">Aucun</span>';}
//   document.getElementById('spanFiltresSaisis').innerHTML = intitFiltre;
//
// }
//
//   function openFic(cheminFic){
//
//     var asUrl = 'openFic.php' + '?cheminFic=' + cheminFic + '&ongletSel=' + ongletSel;
//     var xhr = new XMLHttpRequest();
//
//     xhr.open('GET', asUrl, true); // --- Asynchrone
//   	xhr.onreadystatechange = function()
//     {if(xhr.readyState == 4 && xhr.status == 200)
//     		{
//           var resultat = xhr.responseText;
//           resultat = reverseHTMLentities(resultat);
//           document.getElementById('contenuFic').innerHTML=resultat;
//     		}}
//   	xhr.send(null);
//
//   }
//
//    function affichePileDernieresSaisies(){
//
//     var asUrl = 'affichePileDernieresSaisies.php';
//
//     var xhr = new XMLHttpRequest();
//
//     xhr.open('GET', asUrl, true); // --- Asynchrone
//   	xhr.onreadystatechange = function()
//     {if(xhr.readyState == 4 && xhr.status == 200)
//     		{
//           var resultat = xhr.responseText;
//           resultat = reverseHTMLentities(resultat);
//           document.getElementById('zoneAffichageDernieresSaisies').innerHTML=resultat;
//     		}};
//   	xhr.send(null);
//
//   }
//
//
//    function afficheSyntheseJour(){
//
//     var asUrl = 'afficheSyntheseJour.php';
//
//     var xhr = new XMLHttpRequest();
//
//     xhr.open('GET', asUrl, true); // --- Asynchrone
//   	xhr.onreadystatechange = function()
//     {if(xhr.readyState == 4 && xhr.status == 200)
//     		{
//           var resultat = xhr.responseText;
//           resultat = reverseHTMLentities(resultat);
//           document.getElementById('zoneAffichageSyntheseJour').innerHTML=resultat;
//     		}};
//   	xhr.send(null);
//
//   }
//
//    function recordNewColis(){
//
//     var dossier = document.getElementById('dossier').value;
//     var codescann = document.getElementById('codescann').value;
//     document.getElementById('codescann').value="";
//     document.getElementById('zoneAffichageDernieresSaisies').innerHTML='';
//     document.getElementById('zoneAffichageSyntheseJour').innerHTML='';
//
//     var asUrl = 'recordNewColis.php' + '?dossier=' + dossier + '&codescann=' + codescann;
//     var xhr = new XMLHttpRequest();
//
//     xhr.open('GET', asUrl, true); // --- Asynchrone
//   	xhr.onreadystatechange = function()
//     {if(xhr.readyState == 4 && xhr.status == 200)
//     		{
//          affichePileDernieresSaisies();
//          afficheSyntheseJour();
//     		}};
//   	xhr.send(null);
//
//     document.getElementById("codescann").focus();
//
//   }
//
//
//   function Verif(){
// 		var codescann = document.getElementById("codescann").value;
// 		var dossier = document.getElementById("dossier").value;
//
//     if (codescann == "") {
// 			alert("Le champ est vide, veuillez scanner un article.");
//       document.getElementById("codescann").focus();
//       return false;
// 		}
//
// 		if (dossier == "") {
// 			alert("Le champ est vide, veuillez entrer un numero de dossier.");
//       document.getElementById("dossier").focus();
//       return false;
// 		}
//
//     return true;
//   }
//
//
//   function confirmReedition(msg){
//   	var Rep = confirm(msg);
//   	if(Rep == false) {return false;} else {return true;}
//   }
//
//
// function nodoublecote(chaine) {
//   //supprime les double-cotes de la chaine
//   var reg=new RegExp("\"", "g");
//   chaine=chaine.replace(reg,"");
//   // supprime les espaces en d?but de mot
//   chaine=chaine.replace(/^\s+/,"");
//   // supprime les espaces en fin de mot
//   chaine=chaine.replace(/\s+$/,"");
//
//   return chaine;
// }
//
// function nocote(chaine) {
//   var reg=new RegExp("\'", "g");
//   chaine=chaine.replace(reg,"");
//   return chaine;
// }
//
//   function reverseHTMLentities(texte) {
//     texte = texte.replace(/&oelig;/g,'?');
//     texte = texte.replace(/&Agrave;/g,'?');
//     texte = texte.replace(/&Aacute;/g,'?');
//     texte = texte.replace(/&Acirc;/g,'?');
//     texte = texte.replace(/&Atilde;/g,'?');
//     texte = texte.replace(/&Ccedil;/g,'?');
//     texte = texte.replace(/&ccedil;/g,'?');
//     texte = texte.replace(/&Egrave;/g,'?');
//     texte = texte.replace(/&Eacute;/g,'?');
//     texte = texte.replace(/&Ecirc;/g,'?');
//     texte = texte.replace(/&Euml;/g,'?');
//     texte = texte.replace(/&Icirc;/g,'?');
//     texte = texte.replace(/&Iuml;/g,'?');
//     texte = texte.replace(/&Ocirc;/g,'?');
//     texte = texte.replace(/&Ouml;/g,'?');
//     texte = texte.replace(/&Ugrave;/g,'?');
//     texte = texte.replace(/&Uacute;/g,'?');
//     texte = texte.replace(/&Ucirc;/g,'?');
//     texte = texte.replace(/&Uuml;/g,'?');
//     texte = texte.replace(/&aacute;/g,'?');
//     texte = texte.replace(/&aacute;/g,'?');
//     texte = texte.replace(/&acirc;/g,'?');
//     texte = texte.replace(/&atilde;/g,'?');
//     texte = texte.replace(/&auml;/g,'?');
//     texte = texte.replace(/&egrave;/g,'?');
//     texte = texte.replace(/&eacute;/g,'?');
//     texte = texte.replace(/&ecirc;/g,'?');
//     texte = texte.replace(/&euml;/g,'?');
//     texte = texte.replace(/&icirc;/g,'?');
//     texte = texte.replace(/&iuml;/g,'?');
//     texte = texte.replace(/&ocirc;/g,'?');
//     texte = texte.replace(/&ouml;/g,'?');
//     texte = texte.replace(/&ugrave;/g,'?');
//     texte = texte.replace(/&uacute;/g,'?');
//     texte = texte.replace(/&ucirc;/g,'?');
//
//     return texte;
//   }
//
// function cocheTous(nameCheckBoxes, valeur) {
//
//   var liste = document.getElementsByName(nameCheckBoxes);
//
//
//   for (var i = 0; i < liste.length; i++) {
//     liste[i].checked = valeur;
//     if (valeur == true) {
//       typeClassName = "soustableau-row soustableau-row-select";
//       if (document.getElementById("tdCodeScann" + i)) {document.getElementById("tdCodeScann" + i).style.fontWeight = "bold";}
//     } else {
//       (i % 2) ? typeClassName = "soustableau-row soustableau-row-odd" : typeClassName = "soustableau-row soustableau-row-even";
//       if (document.getElementById("tdCodeScann" + i)) {document.getElementById("tdCodeScann" + i).style.fontWeight = "normal";}
//     }
//     document.getElementById("pileLi" + i).className = typeClassName;
//   }
//   compteCoches();
// }
//
//
// function compteCoches() {
//
//   var liste = document.getElementsByName("chksPile");
//   var nbCoches = 0;
//   for (var i = 0; i < liste.length; i++) {
//     if (liste[i].checked == true) {
//       nbCoches++;
//     }
//   }
//   document.getElementById("spanNbSelect").innerHTML = nbCoches;
//   if (nbCoches > 0) {document.getElementById("divBtnAction").style.display='block';} else {document.getElementById("divBtnAction").style.display='none';}
// }
//
// function listeMultipleColisAsupprimer(pageSource) {
//
//   var lstChk= document.getElementsByName("chksPile");
//   var listeColisAsupprimer = "";
//   var lstNomColis = "";
//
//   var n = 0;
//   for (var i = 0; i < lstChk.length; i++) {
//     if (lstChk[i].checked == true) {
//           listeColisAsupprimer += document.getElementById("idcolis" + i).value + "-";
//           lstNomColis += 'n ? ' + document.getElementById("nomcolis" + i).value + "\n"
//           n++;
//     }
//   }
//
//   if (n > 0) {
//     lstNomColis = lstNomColis.substr(0, lstNomColis.length - 1);
//     if (n == 1) {
//       msg = 'Supprimer de la Base le colis ' + lstNomColis + ' ?';
//     } else {
//       msg = 'Supprimer de la Base les ' + n + ' colis s?lectionn?s ?\n' + lstNomColis;
//     }
//     if(confirmSuppression(msg)) {deleteColis(listeColisAsupprimer, pageSource);}
//   } else {
//     alert("Aucun colis ? supprimer n'a ?t? s?lectionn?.")
//   }
// }
//
//
// function deleteColis(idcolis, pageSource){
//
//     var asUrl = 'deleteColis.php' + '?idcolis=' + idcolis;
//     var xhr = new XMLHttpRequest();
//
//     xhr.open('GET', asUrl, true); // --- Asynchrone
//   	xhr.onreadystatechange = function()
//     {if(xhr.readyState == 4 && xhr.status == 200)
//     		{
//
//          if (pageSource == 'saisiEcom') {
//           affichePileDernieresSaisies();
//           afficheSyntheseJour();
//          } else {
//           document.getElementById('frmRqGest').submit();
//          }
//
//     		}};
//   	xhr.send(null);
//
// }
//
//
// function listeMultipleColisAdeplacer(dossierDest) {
//
//   var lstChk= document.getElementsByName("chksPile");
//   var listeColisAdeplacer= "";
//   var lstNomColis = "";
//
//   var n = 0;
//   for (var i = 0; i < lstChk.length; i++) {
//     if (lstChk[i].checked == true) {
//           listeColisAdeplacer += document.getElementById("idcolis" + i).value + "-";
//           lstNomColis += 'n ? ' + document.getElementById("nomcolis" + i).value + "\n"
//           n++;
//     }
//   }
//
//   if (n > 0) {
//     lstNomColis = lstNomColis.substr(0, lstNomColis.length - 1);
//     if (n == 1) {
//       msg = 'D?placer le colis ' + lstNomColis + ' vers le dossier ' + dossierDest + ' ?';
//     } else {
//       msg = 'D?placer les ' + n + ' colis s?lectionn?s vers le dossier ' + dossierDest + ' ?\n' + lstNomColis;
//     }
//     if(confirmSuppression(msg)) {moveColis(listeColisAdeplacer, dossierDest);}
//   } else {
//     alert("Aucun colis ? d?placer ver le dossier " + dossierDest + " n'a ?t? s?lectionn?.")
//   }
// }
//
// function moveColis(idcolis, dossierDest){
//
//     var asUrl = 'moveColis.php' + '?idcolis=' + idcolis + '&dossierDest=' + dossierDest;
//     var xhr = new XMLHttpRequest();
//
//     xhr.open('GET', asUrl, true); // --- Asynchrone
//   	xhr.onreadystatechange = function()
//     {if(xhr.readyState == 4 && xhr.status == 200)
//     		{
//           document.getElementById('frmRqGest').submit();
//     		}};
//   	xhr.send(null);
//
// }
//   function verifSaisie(ctrl){
//   	var ctrl = document.getElementById(ctrl)
//   	if(ctrl.value.length < 3)
//   	   {alert("Veuillez saisir au moins 3 carateres dans la zone de texte ..."); ctrl.style.background="#FFC0E0"; ctrl.focus(); return false;}
//
//     return true;
//   }
//
//
//   function validSaisieDate(frmOrigine){
//
//   frm=document.forms[frmOrigine];
//
//   chaineIdPosition = (frm.elements['idposition'].value).split('*-*');
//   var idposition = chaineIdPosition [0];
//
//   // v?rifie la validit? de la date de d?but saisie
//     if (frm.elements['dateDebut'].value == "")
//   		  {alert ("Merci de saisir une date de d?but.");frm.elements['dateDebut'].focus();frm.elements['dateDebut'].style.background="#FFC0E0";return false;}
//   	else
//   	    {
//           var reg=new RegExp("^[0-9]{2}[/]{1}[0-9]{2}[/]{1}[0-9]{4}$","g");
//           var dateTestee=frm.elements['dateDebut'].value;
//           if (!reg.test(dateTestee)){alert ("Date de d?but saisie non conforme.");frm.elements['dateDebut'].focus();frm.elements['dateDebut'].style.background="#FFC0E0";return false;}
//         }
//
//   // v?rifie la validit? de la date de fin d'?v?nement saisie
//     if (frm.elements['dateFin'].value == "")
//   		  {alert ("Merci de saisir une date de fin.");frm.elements['dateFin'].focus();frm.elements['dateFin'].style.background="#FFC0E0";return false;}
//   	else
//   	    {
//           var reg=new RegExp("^[0-9]{2}[/]{1}[0-9]{2}[/]{1}[0-9]{4}$","g");
//           var dateTestee=frm.elements['dateFin'].value;
//           if (!reg.test(dateTestee)){alert ("Date de fin saisie non conforme.");frm.elements['dateFin'].focus();frm.elements['dateFin'].style.background="#FFC0E0";return false;}
//         }
//
//   // v?rifie si la date de fin d'?v?nement saisie est > ? la date de d?but d'?v?nement saisie
//     var duree = diffJours(frm.elements['dateDebut'].value, frm.elements['dateFin'].value);
//     if (duree.substring(0,1) == '-')
//     	  {
//           alert ("Saisie incoh?rente : date de fin ant?rieure ? date de d?but.");frm.elements['dateDebut'].style.background="#FFC0E0";frm.elements['dateFin'].style.background="#FFC0E0";frm.elements['dateFin'].focus();return false;
//         }
//
//   return true;
// }
//
//
//   function diffJours(dte_Debut, dte_Fin) {
//
//     var jourDebut = dte_Debut.substr(0,2);
//     var moisDebut = dte_Debut.substr(3,2);
//     var anDebut = dte_Debut.substr(6,4);
//
//     var jourFin = dte_Fin.substr(0,2);
//     var moisFin = dte_Fin.substr(3,2);
//     var anFin = dte_Fin.substr(6,4);
//
//     var dateFin = new Date(anFin, moisFin, jourFin);
//     var dateDebut = new Date(anDebut, moisDebut, jourDebut);
//
//     var duree = (dateFin - dateDebut)/86400000;
//     var nbAnnee = Math.floor(duree/365.25);
//     var nbMois = Math.floor(((duree/365.25)-nbAnnee)*12);
//     var nbJour = Math.floor(((((duree/365.25)-nbAnnee)*12)-nbMois)*30.43);
//
//     var espaceTemps = "";
//
//     if (Math.floor(duree) == 0)
//       {espaceTemps = (Math.floor(duree)+1) + " jour";}
//
//     if (Math.floor(duree) > 0)
//     {espaceTemps = (Math.floor(duree)+1) + " jours";}
//
//     if (Math.floor(duree) < 0)
//       {espaceTemps = (Math.floor(duree)-1) + " jours";}
//
//     return espaceTemps;
//   }
//
//
// function semaineFocus(caseSem, indexCaseSem){
//
//   var caseSem = document.getElementById(caseSem + indexCaseSem);
//
//   if (caseSem) {
//     caseSem.style.background='#FFFF9A';
//     caseSem.style.borderColor= 'black';
//   }
// }
//
// function semaineLostFocus(caseSem, indexCaseSem){
//
//   var caseSem = document.getElementById(caseSem + indexCaseSem);
//
//   if (caseSem) {
//     caseSem.style.backgroundImage = 'url("images/head_bg.gif")';
//     caseSem.style.borderColor = '#c3daf9';
//   }
// }
//
//
// function caseSemFocus(caseSem, caseSup, caseInf, j, pinf, ps){
//
//   var caseSem = document.getElementById(caseSem + j);
//
//   if (caseSem) {
//     caseSem.style.background='#FFFF9A';
//     caseSem.style.borderColor= 'black';
//   }
//
//   for (var p = pinf ; p <= ps; p++) {
//
//     if (document.getElementById(caseSup + p) && document.getElementById(caseInf + p)) {
//       document.getElementById(caseSup + p).style.background='#FFFF9A';
//       document.getElementById(caseSup + p).style.borderColor='black';
//
//       document.getElementById(caseInf + p).style.background='#FFFF9A';
//       document.getElementById(caseInf + p).style.borderColor='black';
//     }
//   }
// }
//
// function caseSemLostFocus(caseSem, caseSup, caseInf, j, pinf, ps){
//
//   var caseSem = document.getElementById(caseSem + j);
//
//   if (caseSem) {
//     caseSem.style.background='#FFFFFF';
//     caseSem.style.borderColor='#c3daf9';
//   }
//
//   for (var p = pinf ; p <= ps; p++) {
//
//     if (document.getElementById(caseSup + p) && document.getElementById(caseInf + p)) {
//
//       document.getElementById(caseSup + p).style.background = bgCase[p-1];
//       document.getElementById(caseSup + p).style.borderColor = bcCase[p-1];
//
//       document.getElementById(caseInf + p).style.background=bgCase[p-1];
//       document.getElementById(caseInf + p).style.borderColor=bcCase[p-1];
//     }
//   }
// }
//
//
// function caseSupFocus(caseSup, caseInf, j){
//
//   var caseSup = document.getElementById(caseSup + j);
//   var caseInf = document.getElementById(caseInf + j);
//
//   if (caseSup && caseInf) {
//     coulIntSup = caseSup.style.background;
//     coulIntInf = caseInf.style.background;
//     coulCadreSup = caseSup.style.borderColor;
//     coulCadreInf = caseSup.style.borderColor;
//
//     caseSup.style.background='#FFFF9A';
//     caseSup.style.borderColor='black';
//
//     caseInf.style.background='#FFFF9A';
//     caseInf.style.borderColor='black';
//   }
// }
//
// function caseSupLostFocus(caseSup, caseInf, j){
//
//   var caseSup = document.getElementById(caseSup + j);
//   var caseInf = document.getElementById(caseInf + j);
//
//   if (caseSup && caseInf) {
//     caseSup.style.background = coulIntSup;
//     caseSup.style.borderColor = coulCadreSup;
//
//     caseInf.style.background = coulIntInf;
//     caseInf.style.borderColor = coulCadreInf;
//   }
// }
//
// function afficheCalendar(moisConsulte, anConsulte){
//
//   //document.getElementById('zoneAffichageCalendar').innerHTML="";
//
//   var asUrl = 'rapportPROD.php?&moisConsulte=' + moisConsulte + '&anConsulte=' + anConsulte;
//
//   var xhr = new XMLHttpRequest();
//
//   xhr.open('GET', asUrl, true); // --- Asynchrone
// 	xhr.onreadystatechange = function ()
//   {if(xhr.readyState == 4 && xhr.status == 200)
//   		{
//         var resultat = xhr.responseText;
//         resultat = reverseHTMLentities(resultat);
//         document.getElementById('zoneAffichageCalendar').innerHTML=resultat;
//   		}};
// 	xhr.send(null);
//
// }
//
// function afficheSynthAn(anConsulte){
//
//   //document.getElementById('zoneAffichageSyntAn').innerHTML="";
//
//   var asUrl = 'tblSynthAn.php?&anConsulte=' + anConsulte;
//
//   var xhr = new XMLHttpRequest();
//
//   xhr.open('GET', asUrl, true); // --- Asynchrone
// 	xhr.onreadystatechange = function ()
//   {if(xhr.readyState == 4 && xhr.status == 200)
//   		{
//         var resultat = xhr.responseText;
//         resultat = reverseHTMLentities(resultat);
//         document.getElementById('zoneAffichageSyntAn').innerHTML=resultat;
//   		}};
// 	xhr.send(null);
//
// }
//
// function afficheSynthDossiers(anneeConsultee){
//
//   var asUrl = 'tblSynthDossiers.php?&anneeConsultee=' + anneeConsultee;
//
//   var xhr = new XMLHttpRequest();
//
//   xhr.open('GET', asUrl, true); // --- Asynchrone
// 	xhr.onreadystatechange = function ()
//   {if(xhr.readyState == 4 && xhr.status == 200)
//   		{
//         var resultat = xhr.responseText;
//         resultat = reverseHTMLentities(resultat);
//         document.getElementById('zoneAffichageSyntDossiers').innerHTML=resultat;
//   		}};
// 	xhr.send(null);
//
// }
//
// function selLien(indexLien){
//
//   var lstLien = document.getElementsByName('tdLiens');
//   for (var i = 0; i < lstLien.length; i++) {
//     lstLien[i].className = 'tbl-lien';
//   }
//   document.getElementById('tdLien' + indexLien).className = 'tbl-lien-actif';
// }
//
//
