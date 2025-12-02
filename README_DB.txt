descrição para Documento de Contexto deBD

Tabelas principais:
- usr (usuários): id_usr, nm, eml, pwd, adm, criado_em
- itn (itens): id_itn, id_usr, ttl, artista, formato, genero, ano, desc_txt, capa, favorito, criado_em
- lst (listas): id_lst, id_usr, nm_lst, desc_txt, publico, criado_em
- lst_itn (lista-itens): id_li, id_lst, id_itn, posicao, adicionado_em
- fav (favoritos): id_fav, id_usr, id_itn, criado_em
- cmntr (comentários): id_cmn, id_itn, id_usr, txt, criado_em
- seg (seguidores): id_seg, seguidor, seguido, criado_em

Relacionamentos:
usr (1) - (N) itn
itn (1) - (N) cmntr
usr (1) - (N) lst
lst (1) - (N) lst_itn