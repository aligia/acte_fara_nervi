Cum pot incepe sa contribui?

1. Instalati XAMPP, SourceTree si Git
SourceTree - este un tool care ne permite sa vizualizam in ce stare se afla codul de pe acest repository (ar
trebui folosit mai mult ca un tool de vizualizare).
SourceTree poate executa vizual si prin menu-uri comenzile care se pot face si cu Git in linie de comanda. 
E de preferinta sa se foloseasca un singur program pentru a administra versiunile site-ului, atat local,
pe calculatorul propriu cat si aici, in repository: ori SourceTree, ori Git.

Git - este un tool in linie de comanda care ne permite sa cream branch-uri de la versiunea principala a codului
aflat aici in repository, sa efectuam modificarile noastre si apoi sa salvam si sa facem merge cu versiunea principala. 
Deci este un tool care mentine legatura dintre versiunea de cod de pe calculatorul nostru si versiunea de cod de pe aceasta pagina.

XAMPP - avem nevoie de el pentru Serverul Appache si baza de date MySQL. De obicei deschidem aplicatia si pornim 
cele 2 servicii pentru a putea vizualiza site-ul si a putea sa-l administram local. Cand site-ul e urcat deja pe un
hosting si lucram remote la site, cele 2 servicii sunt deja activate si nu e nevoie de XAMPP... Local insa da.

2. Faceti cu ajutorul SourceTree un repository intr-un folder nou local (spre ex: D:/FolderNou si puneti 
link la sursa "github/acte_fara_nervi.git" de pe pagina proiectului (verificati atent link-ul care nu e exact scris aici, 
se poate da copy-paste acestui link pe pagina principala).

3. In folder-ul "BazaDate" aveti baza de date a site-ului. Aceasta trebuie importata in bazele de date PhpMyAdmin. Mergeti
intr-un browser si scrieti: localhost/phpmyadmin - acolo apasati pe "Import" si importati baza de date "administratii", 
care a fost exportata sub numele de "acte_fara_nervi"

4. O data ce ati facut toate lucruri 
copiati continutul repository-ului in C:/xampp/htdocs/FolderulMeu, 
deschideti panoul XAMPP si apasati "Start" la serverul Appache si MySql 
mergeti intr-un browser unde scrieti la adresa: localhost/FolderulMeu

5. Pentru alte detalii tehnice - vom discuta pe platforma aleasa si comunicata prin mail.
