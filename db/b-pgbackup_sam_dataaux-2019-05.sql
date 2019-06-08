--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

-- ACTIVAMOS LA BASE DE DATOS
\c "isurgob"

--
-- TOC entry 5635 (class 0 OID 5283682)
-- Dependencies: 174
-- Data for Name: banco; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.banco (bco_ent, bco_suc, nombre, domi, tel, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5649 (class 0 OID 5283999)
-- Dependencies: 188
-- Data for Name: banco_cuenta; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.banco_cuenta (bcocta_id, titular, cbu, tipo, tmoneda, interna, ultcheque, est, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5636 (class 0 OID 5283686)
-- Dependencies: 175
-- Data for Name: banco_entidad; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.banco_entidad (bco_ent, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5650 (class 0 OID 5284003)
-- Dependencies: 189
-- Data for Name: banco_tcuenta; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.banco_tcuenta (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5651 (class 0 OID 5284007)
-- Dependencies: 190
-- Data for Name: banco_tmoneda; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.banco_tmoneda (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5652 (class 0 OID 5284011)
-- Dependencies: 191
-- Data for Name: caja; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caja (caja_id, nombre, usr_id, teso_id, sup1, sup2, sup3, sup4, tipo, destino, validar, copia, resumen, editamonto, ext_num, ext_bco_ent, ext_tori, ext_host, ext_usr, ext_pwd, ext_recurso, ext_tdisenio, ext_cod_ent, est, calc_rec, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5653 (class 0 OID 5284020)
-- Dependencies: 192
-- Data for Name: caja_anulado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caja_anulado (tipo, comprob, cajero, fchpedido, sup, fchaprob, motivo) FROM stdin;
\.


--
-- TOC entry 5654 (class 0 OID 5284023)
-- Dependencies: 193
-- Data for Name: caja_arqueo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caja_arqueo (caja_id, fecha, cant1000_00, cant500_00, cant200_00, cant100_00, cant050_00, cant020_00, cant010_00, cant005_00, cant002_00, cant001_00, cant000_50, cant000_25, cant000_10, cant000_05, cant000_01, val_ef, val_ch, val_tc, val_td, val_de, val_tr, val_nc, val_bo, val_ha, val_ot, recuento, efectivo, otros, fondo, total, sobrante, cant_retiro, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5655 (class 0 OID 5284027)
-- Dependencies: 194
-- Data for Name: caja_caja_mdp; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caja_caja_mdp (caja_id, mdp) FROM stdin;
\.


--
-- TOC entry 5656 (class 0 OID 5284030)
-- Dependencies: 195
-- Data for Name: caja_cheque_cartera; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caja_cheque_cartera (cart_id, nrocheque, monto, bco_ent, bco_suc, bco_cta, titular, plan_id, plan_id2, est, fchalta, fchcobro, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5657 (class 0 OID 5284035)
-- Dependencies: 196
-- Data for Name: caja_estado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caja_estado (caja_id, fecha, apesup, fchapesup, apecaj, fchapecaj, ciecaj, fchciecaj, ciesup, fchciesup, est) FROM stdin;
\.


--
-- TOC entry 5658 (class 0 OID 5284038)
-- Dependencies: 197
-- Data for Name: caja_externa_anula; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caja_externa_anula (codbarra, ctacte_id, caja_id, fecha, cta_id, monto, error, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5659 (class 0 OID 5284041)
-- Dependencies: 198
-- Data for Name: caja_externa_err; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caja_externa_err (codbarra, ctacte_id, caja_id, fecha, cobrado, error, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5660 (class 0 OID 5284044)
-- Dependencies: 199
-- Data for Name: caja_mdp; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caja_mdp (mdp, nombre, tipo, cotiza, simbolo, habilitado, financia) FROM stdin;
\.


--
-- TOC entry 5661 (class 0 OID 5284048)
-- Dependencies: 200
-- Data for Name: caja_mdp_cuota; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caja_mdp_cuota (mdp, cuotas, rec) FROM stdin;
\.


--
-- TOC entry 5662 (class 0 OID 5284051)
-- Dependencies: 201
-- Data for Name: caja_opera; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caja_opera (opera, caja_id, fecha, lote, cant, monto, cobrado, comision, deposito, fchrecep, fchproc, ctacte_id, est, fchmod, usrmod, cant_lotes) FROM stdin;
\.


--
-- TOC entry 5663 (class 0 OID 5284060)
-- Dependencies: 202
-- Data for Name: caja_opera_mdp; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caja_opera_mdp (orden, opera, mdp, cant, cotiza, monto, comprob, bco_ent, bco_suc, bco_cta, titular, tcta, fchcobro, bcocta_id, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5664 (class 0 OID 5284066)
-- Dependencies: 203
-- Data for Name: caja_pagoold; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caja_pagoold (pago_id, obj_id, trib_id, anio, cuota, fchpago, comprob, obs, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5665 (class 0 OID 5284072)
-- Dependencies: 204
-- Data for Name: caja_tdestino; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caja_tdestino (cod, nombre, fchmod, usrmod) FROM stdin;
0	No Definido	2012-03-08 08:52:40.187446	100
1	COM1	2012-03-08 08:52:40.216935	100
2	COM2	2012-03-08 08:52:40.252472	100
3	LPT1	2012-03-08 08:52:40.289566	100
4	Reporte	2012-03-08 08:52:40.323152	100
\.


--
-- TOC entry 5666 (class 0 OID 5284076)
-- Dependencies: 205
-- Data for Name: caja_tdisenio; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caja_tdisenio (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5667 (class 0 OID 5284080)
-- Dependencies: 206
-- Data for Name: caja_tesoreria; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caja_tesoreria (teso_id, nombre, est, fchmod, usrmod) FROM stdin;
1	MUNICIPAL	A	2011-09-22 11:47:41.501016	109
0	EXTERNA	A	2012-05-15 16:11:49	100
\.


--
-- TOC entry 5668 (class 0 OID 5284084)
-- Dependencies: 207
-- Data for Name: caja_test; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caja_test (cod, nombre, fchmod, usrmod) FROM stdin;
0	Cerrada Supervisor	2006-02-28 09:10:27	100
1	Abierta Supervisor	2006-02-28 09:11:10	100
3	Cerrada Cajero	2006-02-28 09:11:55	100
2	Abierta Cajero	2006-02-28 09:12:19	100
\.


--
-- TOC entry 5641 (class 0 OID 5283774)
-- Dependencies: 180
-- Data for Name: caja_ticket; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caja_ticket (ticket, opera, caja_id, fecha, hora, ctacte_id, trib_id, obj_id, subcta, anio, cuota, faci_id, num, monto, monto_valida, est, obs, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5642 (class 0 OID 5283783)
-- Dependencies: 181
-- Data for Name: caja_ticket_det; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caja_ticket_det (ticket, cta_id, monto) FROM stdin;
\.


--
-- TOC entry 5669 (class 0 OID 5284088)
-- Dependencies: 208
-- Data for Name: caja_ticket_item; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caja_ticket_item (ticket, item_id, cant, monto) FROM stdin;
\.


--
-- TOC entry 5670 (class 0 OID 5284091)
-- Dependencies: 209
-- Data for Name: caja_tipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caja_tipo (cod, nombre, fchmod, usrmod) FROM stdin;
1	On Line	2006-07-10 11:51:42	100
2	Off Line	2006-07-10 11:51:53	100
3	Débito	2006-07-10 11:52:12	100
4	Tarjeta Crédito	2012-07-31 07:17:52.445475	100
5	Cesión Haberes	2012-07-31 07:17:52.600205	100
6	Home Banking	2012-08-16 10:51:52.9984	100
\.


--
-- TOC entry 5671 (class 0 OID 5284095)
-- Dependencies: 210
-- Data for Name: caja_tmdp; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caja_tmdp (cod, nombre, fchmod, usrmod) FROM stdin;
EF	Efectivo	2011-11-01 11:00:51	100
TC	Tarjeta Crédito	2011-11-01 11:01:10	100
TD	Tarjeta Débito	2011-11-01 11:01:23	100
CH	Cheque	2011-11-01 11:01:34	100
HA	Sesión Haberes	2011-11-01 11:01:44	100
TR	Transferencia	2011-11-01 11:02:18	100
BO	Bonos	2011-11-01 11:02:29	100
NC	Nota de Crédito	2011-11-01 11:02:37	100
OT	Otros	2011-11-01 11:02:53	100
DB	Débito	2011-12-28 10:25:53	100
CA	Cheque Cartera	2012-12-12 07:53:54.891071	100
DE	Depósito	2011-11-01 11:02:00	100
\.


--
-- TOC entry 5672 (class 0 OID 5284099)
-- Dependencies: 211
-- Data for Name: caja_tori; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caja_tori (cod, nombre, fchmod, usrmod) FROM stdin;
0	Manual	2011-11-01 10:51:52	100
1	Archivo	2011-11-01 10:52:04	100
2	FTP	2011-11-01 10:52:13	100
3	WS	2011-11-01 10:52:23	100
\.


--
-- TOC entry 6042 (class 0 OID 5317063)
-- Dependencies: 679
-- Data for Name: calc_act; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.calc_act (fchdesde, fchhasta, indice, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5673 (class 0 OID 5284103)
-- Dependencies: 212
-- Data for Name: calc_desc; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.calc_desc (desc_id, trib_id, item_id, anual, perdesde, perhasta, aplicavenc, pagodesde, pagohasta, montodesde, montohasta, verificadeuda, existedeuda, verificadebito, verificaexen, desc1, desc2, cta_id, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5674 (class 0 OID 5284109)
-- Dependencies: 213
-- Data for Name: calc_feriado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.calc_feriado (fecha, detalle, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5675 (class 0 OID 5284113)
-- Dependencies: 214
-- Data for Name: calc_interes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.calc_interes (fchdesde, fchhasta, indice, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5676 (class 0 OID 5284117)
-- Dependencies: 215
-- Data for Name: calc_mm; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.calc_mm (fchdesde, fchhasta, valor, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5677 (class 0 OID 5284121)
-- Dependencies: 216
-- Data for Name: calc_multa; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.calc_multa (trib_id, perdesde, perhasta, tipo, montodesde, montohasta, item_id, tcalculo, valor, alicuota, finmes, diasvenc, quita, valormaximo, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5678 (class 0 OID 5284127)
-- Dependencies: 217
-- Data for Name: calc_multa_tfcalculo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.calc_multa_tfcalculo (cod, nombre, detalle, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5679 (class 0 OID 5284131)
-- Dependencies: 218
-- Data for Name: cem; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cem (obj_id, nc, cua_id, cue_id, tipo, piso, fila, nume, bis, cat, deleg, sup, tomo, folio, fchcompra, fchingreso, fchvenc, exenta, edicto, cod_ant) FROM stdin;
\.


--
-- TOC entry 5680 (class 0 OID 5284135)
-- Dependencies: 219
-- Data for Name: cem_alquiler; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cem_alquiler (alq_id, obj_id, titulo, fchalq, fchini, fchfin, duracion, resp, est, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5681 (class 0 OID 5284140)
-- Dependencies: 220
-- Data for Name: cem_cuadro; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cem_cuadro (cua_id, nombre, tipo, piso, fila, nume, bis, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5682 (class 0 OID 5284148)
-- Dependencies: 221
-- Data for Name: cem_cuerpo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cem_cuerpo (cua_id, cue_id, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5683 (class 0 OID 5284152)
-- Dependencies: 222
-- Data for Name: cem_fall; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cem_fall (fall_id, obj_id, est, tdoc, ndoc, apenom, fchnac, nacionalidad, sexo, estcivil, domi, actadef, foliodef, fchdef, fchinh, causamuerte, procedencia, med_nombre, med_matricula, emp_funebre, resp, indigente, obs, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5684 (class 0 OID 5284160)
-- Dependencies: 223
-- Data for Name: cem_fall_serv; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cem_fall_serv (fall_id, orden, tserv, fecha, acta, resp, obj_id_ori, obj_id_dest, destino, obs, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5685 (class 0 OID 5284166)
-- Dependencies: 224
-- Data for Name: cem_fall_test; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cem_fall_test (cod, nombre, fchmod, usrmod) FROM stdin;
BA	Baja	2015-05-27 10:41:40.560603	100
CRE	Cremado	2015-05-27 10:41:40.628601	100
INH	Inhumado	2015-05-27 10:41:40.696303	100
RED	Reducido	2015-05-27 10:41:40.771545	100
TRA	Trasladado	2015-05-27 10:41:40.84199	100
\.


--
-- TOC entry 5686 (class 0 OID 5284170)
-- Dependencies: 225
-- Data for Name: cem_fall_tserv; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cem_fall_tserv (cod, nombre, est_fin, pedir_obj_dest, pedir_dest, fchmod, usrmod) FROM stdin;
INH	Inhumación	INH	1	0	2015-05-27 11:32:34.491684	100
RED	Reducción	RED	0	0	2015-05-27 11:32:34.55266	100
CRE	Cremación	CRE	0	0	2015-06-18 12:48:05.950785	108
CCA	Cambio de Caja	0	0	0	2015-08-13 12:34:28.641212	110
TEX	Traslado Externo	0	0	1	2015-09-18 14:37:23	100
TIN	Traslado Interno	0	1	1	2016-02-17 08:26:38.932729	22
\.


--
-- TOC entry 5687 (class 0 OID 5284177)
-- Dependencies: 226
-- Data for Name: cem_talq; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cem_talq (cod, desde, hasta, tipo, cuadesde, cuahasta, cue_id, fila, cat, supdesde, suphasta, duracion, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5688 (class 0 OID 5284181)
-- Dependencies: 227
-- Data for Name: cem_talq_est; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cem_talq_est (cod, nombre, fchmod, usrmod) FROM stdin;
A	Activo	2008-04-16 14:29:37	100
B	Baja	2008-04-16 14:29:43	100
R	Renovado	2008-04-16 14:29:54	100
\.


--
-- TOC entry 5689 (class 0 OID 5284185)
-- Dependencies: 228
-- Data for Name: cem_tcat; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cem_tcat (tipo, cat, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5690 (class 0 OID 5284189)
-- Dependencies: 229
-- Data for Name: cem_tcausa; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cem_tcausa (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5691 (class 0 OID 5284193)
-- Dependencies: 230
-- Data for Name: cem_tdeleg; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cem_tdeleg (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5692 (class 0 OID 5284197)
-- Dependencies: 231
-- Data for Name: cem_texenta; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cem_texenta (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5693 (class 0 OID 5284201)
-- Dependencies: 232
-- Data for Name: cem_tfunebre; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cem_tfunebre (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5694 (class 0 OID 5284205)
-- Dependencies: 233
-- Data for Name: cem_tipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cem_tipo (cod, nombre, fchmod, usrmod) FROM stdin;
SP	Sepultura	2017-12-20 10:24:26.53865	100
NI	Nicho	2017-12-20 10:24:26.53865	100
PA	Panteón	2017-12-20 10:24:26.53865	100
CO	Columbario	2017-12-20 10:24:26.53865	100
DE	Depósito	2017-12-20 10:24:26.53865	100
\.


--
-- TOC entry 5695 (class 0 OID 5284209)
-- Dependencies: 234
-- Data for Name: comer; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.comer (obj_id, legajo, thab, tipo, fchhab, fchvenchab, pi, cantemple, supcub, supsemi, supdes, alquila, zona, inmueble, rodados, tel, mail) FROM stdin;
\.


--
-- TOC entry 5696 (class 0 OID 5284213)
-- Dependencies: 235
-- Data for Name: comer_ddjj_anual; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.comer_ddjj_anual (obj_id, anio, fchpresenta, base, auto, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5697 (class 0 OID 5284218)
-- Dependencies: 236
-- Data for Name: comer_infrac; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.comer_infrac (obj_id, tinfrac, detalle) FROM stdin;
\.


--
-- TOC entry 5698 (class 0 OID 5284221)
-- Dependencies: 237
-- Data for Name: comer_inm; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.comer_inm (obj_id, inmueble, subcta, porc, est, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5699 (class 0 OID 5284226)
-- Dependencies: 238
-- Data for Name: comer_thab; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.comer_thab (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5700 (class 0 OID 5284230)
-- Dependencies: 239
-- Data for Name: comer_tinfrac; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.comer_tinfrac (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5701 (class 0 OID 5284234)
-- Dependencies: 240
-- Data for Name: comer_tipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.comer_tipo (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5644 (class 0 OID 5283826)
-- Dependencies: 183
-- Data for Name: comer_tiva; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.comer_tiva (cod, nombre, porc, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5702 (class 0 OID 5284238)
-- Dependencies: 241
-- Data for Name: comer_tliq; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.comer_tliq (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5703 (class 0 OID 5284242)
-- Dependencies: 242
-- Data for Name: comer_torgjuri; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.comer_torgjuri (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5704 (class 0 OID 5284246)
-- Dependencies: 243
-- Data for Name: comer_tzona; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.comer_tzona (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5706 (class 0 OID 5284252)
-- Dependencies: 245
-- Data for Name: comp; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.comp (comp_id, expe, tipo, aplic_num, fchalta, fchaplic, fchconsolida, fchbaja, trib_ori, obj_ori, trib_dest, obj_dest, monto, monto_aplic, est, obs, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5707 (class 0 OID 5284257)
-- Dependencies: 246
-- Data for Name: comp_aplic; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.comp_aplic (comp_id, fecha, ctacte_id, monto_aplic) FROM stdin;
\.


--
-- TOC entry 5708 (class 0 OID 5284260)
-- Dependencies: 247
-- Data for Name: comp_saldo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.comp_saldo (comp_id, ctacte_id, saldo, saldo_cub) FROM stdin;
\.


--
-- TOC entry 5709 (class 0 OID 5284263)
-- Dependencies: 248
-- Data for Name: comp_test; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.comp_test (cod, nombre, fchmod, usrmod) FROM stdin;
D	Devengado	2007-10-24 16:22:59	100
A	Aplicado	2007-10-24 16:23:12	100
B	Baja	2007-10-24 16:23:24	100
\.


--
-- TOC entry 5710 (class 0 OID 5284267)
-- Dependencies: 249
-- Data for Name: comp_tipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.comp_tipo (cod, nombre, fchmod, usrmod) FROM stdin;
2	Compensa Manual	2007-10-24 16:22:36	100
3	Compensa Saldo	2009-01-15 14:47:25	100
4	Movimiento Pago	2009-01-15 14:47:25	100
\.


--
-- TOC entry 5711 (class 0 OID 5284271)
-- Dependencies: 250
-- Data for Name: ctacte; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ctacte (ctacte_id, trib_id, tobj, obj_id, subcta, anio, cuota, ucm, nominal, nominalcub, multa, est, fchemi, fchvenc, fchpago, caja_id, montovenc1, montovenc2, montoanual, texto_id, expe, obs, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5712 (class 0 OID 5284284)
-- Dependencies: 251
-- Data for Name: ctacte_ajuste; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ctacte_ajuste (aju_id, trib_id, ctacte_id, expe, obs, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5713 (class 0 OID 5284289)
-- Dependencies: 252
-- Data for Name: ctacte_baja; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ctacte_baja (ctacte_id, orden, tipo, ucm, nominal, fchemi, expe, obs, fchbaja, usrbaja) FROM stdin;
\.


--
-- TOC entry 5714 (class 0 OID 5284299)
-- Dependencies: 253
-- Data for Name: ctacte_baja_liq; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ctacte_baja_liq (ctacte_id, orden, orden_liq, item_id, param1, param2, param3, param4, monto) FROM stdin;
\.


--
-- TOC entry 5715 (class 0 OID 5284302)
-- Dependencies: 254
-- Data for Name: ctacte_cambioest; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ctacte_cambioest (cambio_id, tipo, trib_id, obj_id, subcta, perdesde, perhasta, est_orig, est_dest, expe, obs, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5716 (class 0 OID 5284312)
-- Dependencies: 255
-- Data for Name: ctacte_cambioest_tipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ctacte_cambioest_tipo (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5717 (class 0 OID 5284316)
-- Dependencies: 256
-- Data for Name: ctacte_det; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ctacte_det (ctacte_id, topera, comprob, cta_id, debe, haber, fecha, est, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5718 (class 0 OID 5284320)
-- Dependencies: 257
-- Data for Name: ctacte_excep; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ctacte_excep (excep_id, ctacte_id, trib_id, obj_id, subcta, anio, cuota, tipo, fchusar, fchlimite, expe, obs, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5719 (class 0 OID 5284325)
-- Dependencies: 258
-- Data for Name: ctacte_libredeuda; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ctacte_libredeuda (ldeuda_id, obj_id, fchemi, escribano, texto_id, obs, est, fchbaja, usrbaja, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5720 (class 0 OID 5284333)
-- Dependencies: 259
-- Data for Name: ctacte_libredeuda_bloq; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ctacte_libredeuda_bloq (obj_id, est, obs, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5721 (class 0 OID 5284337)
-- Dependencies: 260
-- Data for Name: ctacte_libredeuda_desc; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ctacte_libredeuda_desc (obj_id, est, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5722 (class 0 OID 5284341)
-- Dependencies: 261
-- Data for Name: ctacte_liq; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ctacte_liq (ctacte_id, orden, item_id, param1, param2, param3, param4, monto) FROM stdin;
\.


--
-- TOC entry 5723 (class 0 OID 5284344)
-- Dependencies: 262
-- Data for Name: ctacte_pagocta; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ctacte_pagocta (pago_id, trib_id, obj_id, subcta, anio, cuota, monto, est, obs, fchlimite, fchpago, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5724 (class 0 OID 5284349)
-- Dependencies: 263
-- Data for Name: ctacte_pagocta_det; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ctacte_pagocta_det (pago_id, cta_id, monto) FROM stdin;
\.


--
-- TOC entry 5725 (class 0 OID 5284352)
-- Dependencies: 264
-- Data for Name: ctacte_planvenc; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ctacte_planvenc (corr_id, plan_id, cuota_desde, vencdesde_nuevo, vencdesde_ant, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5726 (class 0 OID 5284357)
-- Dependencies: 265
-- Data for Name: ctacte_rec; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ctacte_rec (ctacte_id, recibo, fecha, acta, item_id, area, monto) FROM stdin;
\.


--
-- TOC entry 5727 (class 0 OID 5284360)
-- Dependencies: 266
-- Data for Name: ctacte_sem; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ctacte_sem (ctacte_id, cuota, descsem, montosem, fchvenc, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5728 (class 0 OID 5284364)
-- Dependencies: 267
-- Data for Name: ctacte_tcta; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ctacte_tcta (cod, nombre, fchmod, usrmod) FROM stdin;
1	Nominal	2006-02-28 09:58:03	100
3	Recargo	2006-02-28 09:58:51	100
4	Multa	2006-02-28 09:59:00	100
2	Bonificación	2006-02-28 09:58:15	100
\.


--
-- TOC entry 5729 (class 0 OID 5284368)
-- Dependencies: 268
-- Data for Name: ctacte_test; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ctacte_test (cod, nombre, fchmod, usrmod) FROM stdin;
P	Pagado	2006-02-28 09:16:56	100
C	Convenio	2006-02-28 09:17:15	100
X	Falta DJ/Emis	2006-02-28 09:17:28	100
J	Juicio	2006-02-28 09:18:07	100
D	Deuda	2006-02-28 09:18:34	100
B	Baja	2006-02-28 09:18:47	100
O	Condona	2008-04-08 19:14:09	100
T	Prescripto	2006-02-28 09:18:58	100
E	Exento	2007-05-08 09:58:36	100
\.


--
-- TOC entry 5730 (class 0 OID 5284372)
-- Dependencies: 269
-- Data for Name: ctacte_topera; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ctacte_topera (cod, nombre, fchmod, usrmod) FROM stdin;
2	Dec.Jurada	2006-07-10 11:35:18	100
3	Pago	2006-07-10 11:35:40	100
4	Mov. Pago	2006-07-10 11:35:54	100
6	Convenio	2006-07-10 11:36:23	100
8	Decaimiento	2006-07-10 11:36:45	100
9	Facilidad	2006-07-10 11:36:57	100
10	Pago Facilidad	2006-07-10 11:37:09	100
11	Ajuste	2006-07-10 11:37:18	100
12	Compensa Origen	2006-07-10 11:37:33	100
13	Liq. Admin.	2006-07-10 11:37:49	100
15	Dec.Jurada Fisc.	2007-08-28 10:00:11	100
16	Compensa Destino	2007-10-24 17:49:53	100
1	Emisión	2006-07-10 11:35:04	100
5	Débito	2006-07-10 11:36:08	100
7	Imputación	2006-07-10 11:36:33	100
17	Pago a Cuenta	2012-06-12 13:51:50.321091	100
\.


--
-- TOC entry 5643 (class 0 OID 5283786)
-- Dependencies: 182
-- Data for Name: cuenta; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cuenta (cta_id, nombre, nombre_redu, tcta, part_id, cta_id_atras, est, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5732 (class 0 OID 5284378)
-- Dependencies: 271
-- Data for Name: ddjj; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ddjj (dj_id, dj_id_web, ctacte_id, trib_id, obj_id, subcta, fiscaliza, anio, cuota, orden, tipo, base, anual, anticipos, monto, multa, fchpresenta, est, error_act, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5733 (class 0 OID 5284387)
-- Dependencies: 272
-- Data for Name: ddjj_anual; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ddjj_anual (dj_id, cuota, base, monto) FROM stdin;
\.


--
-- TOC entry 5734 (class 0 OID 5284390)
-- Dependencies: 273
-- Data for Name: ddjj_liq; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ddjj_liq (dj_id, item_id, monto) FROM stdin;
\.


--
-- TOC entry 5735 (class 0 OID 5284393)
-- Dependencies: 274
-- Data for Name: ddjj_recep; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ddjj_recep (recep_id, contador, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5736 (class 0 OID 5284397)
-- Dependencies: 275
-- Data for Name: ddjj_recep_det; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ddjj_recep_det (recep_id, obj_id, anio, cuota, base, total, est, obs, dj_id) FROM stdin;
\.


--
-- TOC entry 5737 (class 0 OID 5284404)
-- Dependencies: 276
-- Data for Name: ddjj_ret; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ddjj_ret (dj_id, ret_id) FROM stdin;
\.


--
-- TOC entry 5738 (class 0 OID 5284407)
-- Dependencies: 277
-- Data for Name: ddjj_rubros; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ddjj_rubros (dj_id, rubro_id, base, cant, minimo, alicuota, monto) FROM stdin;
\.


--
-- TOC entry 5739 (class 0 OID 5284410)
-- Dependencies: 278
-- Data for Name: ddjj_test; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ddjj_test (cod, nombre, fchmod, usrmod) FROM stdin;
A	Aprobada	2015-08-03 14:40:24	100
R	Rectificada	2015-08-03 14:40:37	100
B	Baja	2015-08-03 14:40:51	100
\.


--
-- TOC entry 5740 (class 0 OID 5284414)
-- Dependencies: 279
-- Data for Name: ddjj_tipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ddjj_tipo (cod, nombre, fchmod, usrmod) FROM stdin;
1	Aprobada	2006-09-21 07:55:18	100
2	De Oficio	2006-09-21 07:55:29	100
3	Fiscalización	2006-09-21 07:55:47	100
\.


--
-- TOC entry 5741 (class 0 OID 5284418)
-- Dependencies: 280
-- Data for Name: debito_adhe; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.debito_adhe (adh_id, trib_id, obj_id, subcta, resp, resptdoc, respndoc, respsexo, caja_id, temple, temple_area, bco_suc, bco_tcta, tpago_nro, cbu, fchalta, fchbaja, est, perdesde, perhasta, obs, texto_id, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5742 (class 0 OID 5284432)
-- Dependencies: 281
-- Data for Name: debito_entidad; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.debito_entidad (caja_id, trib_id, anio, mes, cantdebito, montodebito, fchgenerado, fchenvio, fchrecep, fchimputa, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5744 (class 0 OID 5284438)
-- Dependencies: 283
-- Data for Name: debito_periodo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.debito_periodo (deb_id, ctacte_id, adh_id, plan_id, caja_id, anio, mes, monto, montodebito, fchdebito, obs, est, trechazo, rechazo, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5745 (class 0 OID 5284444)
-- Dependencies: 284
-- Data for Name: debito_rechazo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.debito_rechazo (codbarra, caja_id, fecha, rechazo, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5746 (class 0 OID 5284447)
-- Dependencies: 285
-- Data for Name: debito_test; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.debito_test (cod, nombre, fchmod, usrmod) FROM stdin;
1	Pendiente	2006-11-23 10:24:09	100
2	Debitado	2006-11-23 10:24:20	100
3	No Debitado	2006-11-23 10:24:32	100
4	Imputado	2006-11-27 17:19:51	100
\.


--
-- TOC entry 5747 (class 0 OID 5284451)
-- Dependencies: 286
-- Data for Name: debito_trechazo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.debito_trechazo (cod, nombre, caja_id, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5748 (class 0 OID 5284455)
-- Dependencies: 287
-- Data for Name: domi; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.domi (torigen, obj_id, id, loc_id, cp, barr_id, calle_id, nomcalle, puerta, det, piso, dpto, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5749 (class 0 OID 5284459)
-- Dependencies: 288
-- Data for Name: domi_barrio; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.domi_barrio (barr_id, nombre, cat, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5750 (class 0 OID 5284465)
-- Dependencies: 289
-- Data for Name: domi_calle; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.domi_calle (calle_id, nombre, tcalle, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5751 (class 0 OID 5284469)
-- Dependencies: 290
-- Data for Name: domi_localidad; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.domi_localidad (loc_id, nombre, prov_id, cp, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5752 (class 0 OID 5284473)
-- Dependencies: 291
-- Data for Name: domi_pais; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.domi_pais (pais_id, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5753 (class 0 OID 5284477)
-- Dependencies: 292
-- Data for Name: domi_provincia; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.domi_provincia (prov_id, nombre, pais_id, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5754 (class 0 OID 5284481)
-- Dependencies: 293
-- Data for Name: domi_tcalle; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.domi_tcalle (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5755 (class 0 OID 5284485)
-- Dependencies: 294
-- Data for Name: domi_torigen; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.domi_torigen (cod, nombre, origen, fchmod, usrmod) FROM stdin;
COM	Parcelario	obj	2011-08-17 00:00:00	100
INM	Parcelario	obj	2011-08-17 00:00:00	100
JUD	Caratula	id	2011-08-17 00:00:00	100
JUZ	Caratula	id	2011-08-17 00:00:00	100
OBJ	Postal	obj	2011-08-17 00:00:00	100
PLA	Responsable	id	2011-08-17 00:00:00	100
PLE	Legal	obj	2011-08-17 00:00:00	100
PRE	Residencia	obj	2011-08-17 00:00:00	100
HPA	Parcelario	id	2011-08-29 09:08:58	100
HPO	Postal	id	2011-08-29 09:09:23	100
\.


--
-- TOC entry 5756 (class 0 OID 5284489)
-- Dependencies: 295
-- Data for Name: domi_tpav; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.domi_tpav (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5757 (class 0 OID 5284493)
-- Dependencies: 296
-- Data for Name: emision_err; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.emision_err (trib_id, obj_id, subcta, anio, cuota, err) FROM stdin;
\.


--
-- TOC entry 5758 (class 0 OID 5284497)
-- Dependencies: 297
-- Data for Name: emision_esta; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.emision_esta (trib_id, anio, cuota, cant, monto, cant_err, fchemi, usremi, fchaprob, usraprob) FROM stdin;
\.


--
-- TOC entry 5759 (class 0 OID 5284500)
-- Dependencies: 298
-- Data for Name: emision_mail; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.emision_mail (trib_id, obj_id, subcta, anio, cuota, fecha, mail) FROM stdin;
\.


--
-- TOC entry 5760 (class 0 OID 5284504)
-- Dependencies: 299
-- Data for Name: emision_margen; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.emision_margen (cod, nombre, sup, izq, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5761 (class 0 OID 5284510)
-- Dependencies: 300
-- Data for Name: emision_mensaje; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.emision_mensaje (ctacte_id, detalle) FROM stdin;
\.


--
-- TOC entry 5762 (class 0 OID 5284516)
-- Dependencies: 301
-- Data for Name: emision_terr; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.emision_terr (cod, nombre, trib_id, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5773 (class 0 OID 5284560)
-- Dependencies: 312
-- Data for Name: facilida; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.facilida (faci_id, trib_id, obj_id, nominal, accesor, multa, quita, monto, est, fchalta, fchvenc, fchconsolida, fchimputa, fchbaja, usrbaja, baja_auto, obs, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5774 (class 0 OID 5284567)
-- Dependencies: 313
-- Data for Name: facilida_periodo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.facilida_periodo (faci_id, ctacte_id, nominal, accesor, multa, quita) FROM stdin;
\.


--
-- TOC entry 5775 (class 0 OID 5284570)
-- Dependencies: 314
-- Data for Name: fiscaliza; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.fiscaliza (fisca_id, obj_id, expe, inspector, fchalta, fchbaja, est, obs, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5776 (class 0 OID 5284574)
-- Dependencies: 315
-- Data for Name: fiscaliza_test; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.fiscaliza_test (cod, nombre, fchmod, usrmod) FROM stdin;
0	Anulado	2006-02-28 09:44:20	100
2	Verificado	2006-02-28 09:45:00	100
3	Verificado Rentas	2006-02-28 09:45:18	100
4	Cerrado	2006-08-11 00:00:00	100
1	En Verificación	2006-02-28 09:44:38	100
\.


--
-- TOC entry 5777 (class 0 OID 5284578)
-- Dependencies: 316
-- Data for Name: his_cem; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.his_cem (his_id, obj_id, nc, cua_id, cue_id, tipo, piso, fila, nume, bis, cat, deleg, sup, tomo, folio, fchcompra, fchingreso, fchvenc, exenta, edicto, cod_ant, operacion, fch_bd, usr_bd) FROM stdin;
\.


--
-- TOC entry 5778 (class 0 OID 5284582)
-- Dependencies: 317
-- Data for Name: his_comer; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.his_comer (his_id, obj_id, legajo, thab, tipo, fchhab, fchvenchab, pi, cantemple, supcub, supsemi, supdes, alquila, zona, inmueble, rodados, tel, mail, operacion, fch_bd, usr_bd) FROM stdin;
\.


--
-- TOC entry 5780 (class 0 OID 5284588)
-- Dependencies: 319
-- Data for Name: his_ctacte_ajuste; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.his_ctacte_ajuste (his_id, aju_id, trib_id, ctacte_id, expe, obs, fchmod, usrmod, operacion, fch_bd, usr_bd) FROM stdin;
\.


--
-- TOC entry 5782 (class 0 OID 5284595)
-- Dependencies: 321
-- Data for Name: his_domi; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.his_domi (his_id, torigen, obj_id, id, loc_id, cp, barr_id, calle_id, nomcalle, puerta, det, piso, dpto, fchmod, usrmod, operacion, fch_bd, usr_bd) FROM stdin;
\.


--
-- TOC entry 5783 (class 0 OID 5284600)
-- Dependencies: 322
-- Data for Name: his_inm; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.his_inm (his_id, obj_id, nc, s1, s2, s3, manz, parc, uf, porcuf, nc_ant, parp, parporigen, plano, anio_mensura, expe, urbsub, regimen, tinm, titularidad, uso, tmatric, matric, fchmatric, anio, comprador, supt, supt_pasillo, supm, avalt, avalm, frente, fondo, es_esquina, es_calleppal, zonat, zonav, zonaop, agua, cloaca, gas, alum, pav, valbas, coef, barr_id, patrimonio, objeto_superp, archivo, unihab, operacion, fch_bd, usr_bd) FROM stdin;
\.


--
-- TOC entry 5784 (class 0 OID 5284607)
-- Dependencies: 323
-- Data for Name: his_inm_cambio; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.his_inm_cambio (cbio_id, obj_id, tipo, cbio_nomencla, cbio_identif, cbio_carac, cbio_titular, cbio_dominio, cbio_dompos, cbio_dompar, cbio_avalt, cbio_avalm, cbio_zonaserv, supt_old, supt_new, supm_old, supm_new, avalt_old, avalt_new, avalm_old, avalm_new, zonat_old, zonat_new, serv_old, serv_new, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5786 (class 0 OID 5284635)
-- Dependencies: 325
-- Data for Name: his_inm_mej; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.his_inm_mej (his_id, obj_id, pol, perdesde, perhasta, tori, tform, nivel, tdest, tobra, anio, est, supcub, supsemi, plantas, cat, item01, item02, item03, item04, item05, item06, item07, item08, item09, item10, item11, item12, item13, item14, item15, estado, fchmod, usrmod, operacion, fch_bd, usr_bd) FROM stdin;
\.


--
-- TOC entry 5787 (class 0 OID 5284640)
-- Dependencies: 326
-- Data for Name: his_objeto; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.his_objeto (his_id, obj_id, tobj, num, nombre, obj_dato, est, distrib, tdistrib, obs, objunifica, vigencia, claveweb, fchalta, usralta, fchbaja, usrbaja, tbaja, fchmod, usrmod, operacion, fch_bd, usr_bd) FROM stdin;
\.


--
-- TOC entry 5788 (class 0 OID 5284649)
-- Dependencies: 327
-- Data for Name: his_objeto_item; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.his_objeto_item (his_id, obj_id, subcta, orden, trib_id, item_id, perdesde, perhasta, param1, param2, expe, obs, exen_id, fchmod, usrmod, operacion, fch_bd, usr_bd) FROM stdin;
\.


--
-- TOC entry 5789 (class 0 OID 5284657)
-- Dependencies: 328
-- Data for Name: his_objeto_reemplaza; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.his_objeto_reemplaza (oldobj, newobj, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5790 (class 0 OID 5284660)
-- Dependencies: 329
-- Data for Name: his_persona; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.his_persona (his_id, obj_id, inscrip, tipo, tdoc, ndoc, fchnac, sexo, nacionalidad, estcivil, clasif, iva, cuit, ag_rete, ag_rete_manual, tel, cel, mail, exis_doc, exis_insc, exis_foto, est_ib, ib, orgjuri, tipoliq, contador, contador_verdeuda, fchalta_ib, fchbaja_ib, nombre_fantasia, tbaja_ib, operacion, fch_bd, usr_bd) FROM stdin;
\.


--
-- TOC entry 5791 (class 0 OID 5284663)
-- Dependencies: 330
-- Data for Name: his_persona_reemplaza; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.his_persona_reemplaza (oldnum, oldnombre, oldtdoc, oldndoc, oldcuit, newnum, newnombre, newtdoc, newndoc, newcuit, est, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5792 (class 0 OID 5284666)
-- Dependencies: 331
-- Data for Name: his_persona_reemplaza_ctacte; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.his_persona_reemplaza_ctacte (oldnum, ctacte_id) FROM stdin;
\.


--
-- TOC entry 5793 (class 0 OID 5284669)
-- Dependencies: 332
-- Data for Name: his_persona_reemplaza_objeto; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.his_persona_reemplaza_objeto (oldnum, obj_id) FROM stdin;
\.


--
-- TOC entry 5794 (class 0 OID 5284672)
-- Dependencies: 333
-- Data for Name: his_persona_reemplaza_plan; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.his_persona_reemplaza_plan (oldnum, plan_id) FROM stdin;
\.


--
-- TOC entry 5795 (class 0 OID 5284675)
-- Dependencies: 334
-- Data for Name: his_plan; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.his_plan (his_id, plan_id, tplan, obj_id, num, resp, resptdoc, respndoc, resptel, nominal, accesor, multa, financia, sellado, anticipo, origen, tpago, caja_id, temple, temple_area, bco_suc, bco_tcta, tpago_nro, cuotas, montocuo, descnominal, descinteres, descmulta, interes, obs, est, fchalta, usralta, fchbaja, fchimputa, fchdecae, fchconsolida, planant, distrib, tdistrib, fchmod, usrmod, operacion, fch_bd, usr_bd) FROM stdin;
\.


--
-- TOC entry 5797 (class 0 OID 5284693)
-- Dependencies: 336
-- Data for Name: his_rodado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.his_rodado (his_id, obj_id, talta, perini, tliq, aforo_id, cat, marca, modelo, modelo_nom, anio, dominio, dominioant, marcamotor, nromotor, marcachasis, nrochasis, peso, cilindrada, deleg, color, combustible, uso, conductor, fchcompra, tform, remito, remito_anio, operacion, fch_bd, usr_bd) FROM stdin;
\.


--
-- TOC entry 5798 (class 0 OID 5284703)
-- Dependencies: 337
-- Data for Name: inm; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm (obj_id, nc, s1, s2, s3, manz, parc, uf, porcuf, nc_ant, parp, parporigen, plano, anio_mensura, expe, urbsub, regimen, tinm, titularidad, uso, tmatric, matric, fchmatric, anio, comprador, supt, supt_pasillo, supm, avalt, avalm, frente, fondo, es_esquina, es_calleppal, zonat, zonav, zonaop, agua, cloaca, gas, alum, pav, valbas, coef, barr_id, patrimonio, objeto_superp, archivo, unihab) FROM stdin;
\.


--
-- TOC entry 5799 (class 0 OID 5284710)
-- Dependencies: 338
-- Data for Name: inm_avaluo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_avaluo (obj_id, perdesde, perhasta, supt, supm, avalt, avalm, frente, regimen, es_esquina, es_calleppal, zonat, zonav, zonaop, agua, cloaca, gas, alum, pav, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5800 (class 0 OID 5284715)
-- Dependencies: 339
-- Data for Name: inm_frente; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_frente (obj_id, calle_id, medida, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5801 (class 0 OID 5284719)
-- Dependencies: 340
-- Data for Name: inm_manz; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_manz (ncm, zonat, zonav, zonaop, barrio) FROM stdin;
\.


--
-- TOC entry 5802 (class 0 OID 5284725)
-- Dependencies: 341
-- Data for Name: inm_mej; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_mej (obj_id, pol, perdesde, perhasta, tori, tform, nivel, tdest, tobra, anio, est, supcub, supsemi, plantas, cat, item01, item02, item03, item04, item05, item06, item07, item08, item09, item10, item11, item12, item13, item14, item15, estado, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5803 (class 0 OID 5284729)
-- Dependencies: 342
-- Data for Name: inm_mej_tcat; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_mej_tcat (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5804 (class 0 OID 5284733)
-- Dependencies: 343
-- Data for Name: inm_mej_tdest; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_mej_tdest (cod, nombre, fchmod, usrmod) FROM stdin;
1	Baldío	2011-09-26 11:35:40	100
2	Comercio	2011-09-26 11:35:40	100
3	Comunicaciones	2011-09-26 11:35:40	100
4	Cultura	2011-09-26 11:35:40	100
5	Deportes	2011-09-26 11:35:40	100
6	Educación	2011-09-26 11:35:40	100
7	Emergencias	2011-09-26 11:35:40	100
8	Entidad Civil	2011-09-26 11:35:40	100
9	Entidad Educativa	2011-09-26 11:35:40	100
10	Entidad Financiera	2011-09-26 11:35:40	100
11	Entidad Gremial	2011-09-26 11:35:40	100
12	Entidad Oficial	2011-09-26 11:35:40	100
13	Entidad Religiosa	2011-09-26 11:35:40	100
14	Espacio Verde	2011-09-26 11:35:40	100
15	Ganadería	2011-09-26 11:35:40	100
16	Hospedaje	2011-09-26 11:35:40	100
17	Industria	2011-09-26 11:35:40	100
18	Justicia	2011-09-26 11:35:40	100
19	Minería	2011-09-26 11:35:40	100
20	Plantación	2011-09-26 11:35:40	100
21	Propiedad del estado	2011-09-26 11:35:40	100
22	Recreación	2011-09-26 11:35:40	100
23	Salud	2011-09-26 11:35:40	100
24	Seguridad	2011-09-26 11:35:40	100
25	Servicios Públicos	2011-09-26 11:35:40	100
26	Servicios Profesionales	2011-09-26 11:35:40	100
27	Servicios Sociales	2011-09-26 11:35:40	100
28	Transportes	2011-09-26 11:35:40	100
29	Turismo	2011-09-26 11:35:40	100
30	Varios	2011-09-26 11:35:40	100
31	Vivienda	2011-09-26 11:35:40	100
32	Cochera	2011-09-26 11:35:40	100
33	Baulera	2011-09-26 11:35:40	100
34	Depósito	2011-09-26 11:35:40	100
35	Guardería	2011-09-26 11:35:41	100
36	Sotano	2011-12-13 22:07:07	135
40	Pileta	2011-11-18 10:22:44	1
\.


--
-- TOC entry 5805 (class 0 OID 5284738)
-- Dependencies: 344
-- Data for Name: inm_mej_test; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_mej_test (cod, nombre, fchmod, usrmod) FROM stdin;
0	No tiene	2006-07-11 15:57:25	100
1	Bueno	2006-07-11 15:57:34	100
2	Regular	2006-07-11 15:57:42	100
3	Malo	2006-07-11 15:57:50	100
\.


--
-- TOC entry 5806 (class 0 OID 5284742)
-- Dependencies: 345
-- Data for Name: inm_mej_tform; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_mej_tform (cod, nombre, nombre_redu, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5807 (class 0 OID 5284747)
-- Dependencies: 346
-- Data for Name: inm_mej_tobra; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_mej_tobra (cod, nombre, nombre_redu, fchmod, usrmod) FROM stdin;
0	 		2011-10-05 08:36:08.820851	100
1	Original		2011-10-05 08:36:08.840949	100
2	Reforma		2011-10-05 08:36:08.870427	100
3	En Construcción		2011-10-05 08:36:08.899983	100
4	En Demolición		2011-10-05 08:36:08.929545	100
5	No Justipreciable		2011-10-05 08:36:08.959086	100
\.


--
-- TOC entry 5808 (class 0 OID 5284753)
-- Dependencies: 347
-- Data for Name: inm_mej_tori; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_mej_tori (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5809 (class 0 OID 5284757)
-- Dependencies: 348
-- Data for Name: inm_restric; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_restric (obj_id, orden, trestric, sup, inscrip, obs, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5810 (class 0 OID 5284761)
-- Dependencies: 349
-- Data for Name: inm_s1; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_s1 (s1, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5811 (class 0 OID 5284764)
-- Dependencies: 350
-- Data for Name: inm_s2; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_s2 (s1, s2, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5812 (class 0 OID 5284767)
-- Dependencies: 351
-- Data for Name: inm_s3; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_s3 (s1, s2, s3, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5813 (class 0 OID 5284770)
-- Dependencies: 352
-- Data for Name: inm_talum; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_talum (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5814 (class 0 OID 5284774)
-- Dependencies: 353
-- Data for Name: inm_tipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_tipo (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5815 (class 0 OID 5284778)
-- Dependencies: 354
-- Data for Name: inm_tmatric; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_tmatric (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5816 (class 0 OID 5284782)
-- Dependencies: 355
-- Data for Name: inm_tpatrimonio; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_tpatrimonio (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5817 (class 0 OID 5284786)
-- Dependencies: 356
-- Data for Name: inm_tregimen; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_tregimen (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5818 (class 0 OID 5284790)
-- Dependencies: 357
-- Data for Name: inm_trestric; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_trestric (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5819 (class 0 OID 5284794)
-- Dependencies: 358
-- Data for Name: inm_tserv; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_tserv (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5820 (class 0 OID 5284798)
-- Dependencies: 359
-- Data for Name: inm_ttitularidad; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_ttitularidad (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5821 (class 0 OID 5284802)
-- Dependencies: 360
-- Data for Name: inm_turbsub; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_turbsub (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5822 (class 0 OID 5284806)
-- Dependencies: 361
-- Data for Name: inm_tuso; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_tuso (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5823 (class 0 OID 5284810)
-- Dependencies: 362
-- Data for Name: inm_tvtaest; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_tvtaest (cod, nombre, fchmod, usrmod) FROM stdin;
B	Baja	2017-05-18 14:30:30.715525	100
P	Pendiente	2017-05-18 14:30:30.828233	100
I	Informe Muni	2017-05-18 14:30:30.94251	100
A	Aprobada	2017-05-18 14:30:32.672561	100
\.


--
-- TOC entry 5824 (class 0 OID 5284814)
-- Dependencies: 363
-- Data for Name: inm_tzonaop; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_tzonaop (cod, nombre, fos, fot, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5825 (class 0 OID 5284818)
-- Dependencies: 364
-- Data for Name: inm_tzonat; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_tzonat (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5826 (class 0 OID 5284822)
-- Dependencies: 365
-- Data for Name: inm_tzonav; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_tzonav (cod, nombre, valor, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5827 (class 0 OID 5284826)
-- Dependencies: 366
-- Data for Name: inm_vta; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_vta (vta_id, obj_id, escribano, fecha, est, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5828 (class 0 OID 5284831)
-- Dependencies: 367
-- Data for Name: inm_vta_comp; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_vta_comp (vta_id, cuit, nombre, tnac, porc, dompo) FROM stdin;
\.


--
-- TOC entry 5829 (class 0 OID 5284837)
-- Dependencies: 368
-- Data for Name: inm_vta_vend; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inm_vta_vend (vta_id, cuit, nombre, tnac, porc, dompo) FROM stdin;
\.


--
-- TOC entry 5831 (class 0 OID 5284910)
-- Dependencies: 370
-- Data for Name: item; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.item (item_id, nombre, trib_id, tipo, cta_id, obs, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5832 (class 0 OID 5284918)
-- Dependencies: 371
-- Data for Name: item_asoc; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.item_asoc (item_id, perdesde, perhasta, param1, param2, param3, param4, monto, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5833 (class 0 OID 5284921)
-- Dependencies: 372
-- Data for Name: item_tfcalculo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.item_tfcalculo (cod, nombre, detalle, fchmod, usrmod) FROM stdin;
1	Monto	Monto Constante	2006-02-28 10:19:21	100
4	Param1 x Porc	Ejm.: Porcentaje del monto de obra	2006-02-28 10:21:22	100
5	Param1	Monto variable. Establecido por el usuario.	2006-02-28 10:21:47	100
6	S/Tabla Asoc.	Se determina a partir de una tabla asociada.	2006-02-28 10:22:04	100
14	Monto x Ent(Param1) x Param2	Ejm: carteles publicidad, Monto por fraccion de cartel por cantidad	2008-04-16 00:00:00	100
9	Monto Fijo por período	Se establece el valor en cada período. Ejm.: valor de nafta Super	2007-03-09 08:21:01	100
0	No Definido	Sin Fórmula definida. Se usa dentro de las Resoluciones	2006-02-28 10:19:03	100
11	Monto + Monto x Porc x (Param1 - 1)	Ejm.: boleterías tienen un mínimo más un porc. por cada empresa que incorpore	2007-03-12 15:58:39	100
12	VariableSistema x Param2	Ejm: Inspección de medidores se cobra un 12% del Kw básico	2007-03-15 08:48:17	100
13	Monto x Porc x Param1	Ejm.: Der. Edif de bóbeda es un 7% del valor de bóbeda según categoría por Sup.	2007-03-15 08:51:16	100
2	Monto x Param1	Ejm.: Cantidad x Monto Constante	2006-02-28 10:19:32	100
3	Monto x Param1 x Param2	Ejm.: Cantidad x Superficie x Monto Constante	2006-02-28 10:20:20	100
7	Min+(Param1-Cant Base)xMonto	Ejm: Se establece un mínimo de $10, mts $0,10 por cada excedente de 50	2006-02-28 10:22:25	100
8	Mn+Ent(Param1-Porc)xMonto	Ejm: Se establece un mínimo de $10, mts $0,10 por cada excedente de 50 (excedente entero)	2006-02-28 10:24:11	100
15	Porcentaje Sobre Total	Ejm.: Descuento para Municipales	2012-03-30 11:46:55	100
10	Monto * Ent(Param1/Porc)	Ejm.: Limpieza se cobra $10 por cada 50m2. Se requiere la superficie del terreno.	2007-03-12 15:46:49	100
17	Param1 / ValorModulo	Ej: Monto Fijo en $. Si el tributo usa MM al final lo vuelve a multiplicar, quedando importe origen	2017-03-08 13:27:54.0228	100
\.


--
-- TOC entry 5834 (class 0 OID 5284925)
-- Dependencies: 373
-- Data for Name: item_tipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.item_tipo (cod, nombre, fchmod, usrmod) FROM stdin;
7	Fondo	2007-03-16 11:05:12	100
6	Redondeo	2007-03-16 11:05:01	100
5	Multa	2007-03-16 11:04:51	100
4	Item por Objeto	2007-03-16 11:04:41	100
3	Descuento por Objeto	2007-03-16 11:04:33	100
2	Recargo por Objeto	2007-03-09 08:19:57	100
1	Emisión	2007-03-09 08:19:45	100
\.


--
-- TOC entry 5835 (class 0 OID 5284929)
-- Dependencies: 374
-- Data for Name: item_vigencia; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.item_vigencia (item_id, perdesde, perhasta, tcalculo, monto, porc, minimo, paramnombre1, paramnombre2, paramnombre3, paramnombre4, paramcomp1, paramcomp2, paramcomp3, paramcomp4, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5837 (class 0 OID 5284935)
-- Dependencies: 376
-- Data for Name: judi; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.judi (judi_id, obj_id, rep, nro, anio, expe, caratula, perdesde, perhasta, nominal, accesor, multa, multa_omi, hono_jud, gasto_jud, procurador, juzgado, fchalta, fchbaja, fchapremio, fchprocurador, fchjuicio, fchdev, motivo_dev, plan_id, est, obs, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5838 (class 0 OID 5284942)
-- Dependencies: 377
-- Data for Name: judi_etapa; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.judi_etapa (judi_id, orden, fecha, etapa, detalle, hono_jud, gasto_jud, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5839 (class 0 OID 5284946)
-- Dependencies: 378
-- Data for Name: judi_hono; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.judi_hono (est, supuesto, deuda_desde, deuda_hasta, hono_min, hono_porc, gastos, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5840 (class 0 OID 5284955)
-- Dependencies: 379
-- Data for Name: judi_juzgado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.judi_juzgado (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5841 (class 0 OID 5284959)
-- Dependencies: 380
-- Data for Name: judi_periodo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.judi_periodo (judi_id, ctacte_id, nominal, accesor, multa, est) FROM stdin;
\.


--
-- TOC entry 5842 (class 0 OID 5284962)
-- Dependencies: 381
-- Data for Name: judi_tdev; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.judi_tdev (cod, nombre, fchmod, usrmod) FROM stdin;
1	Cancelado Judicial	2006-07-11 10:29:29	100
2	Cancelado Extrajudicial	2006-07-11 10:29:58	100
3	Domicilio Inexistente	2006-07-11 10:30:24	100
4	Verificado Incobrable	2006-07-11 10:31:23	100
5	Pedido de D.R.M.	2006-07-11 10:33:55	100
6	Falta Coincidencia Titulares	2006-07-11 10:34:17	100
\.


--
-- TOC entry 5843 (class 0 OID 5284966)
-- Dependencies: 382
-- Data for Name: judi_test; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.judi_test (cod, nombre, fchmod, usrmod) FROM stdin;
B	Baja	2011-09-30 07:56:12	100
R	Previo	2011-09-30 07:56:23	100
A	Apremio	2011-09-30 07:56:31	100
E	Extrajudicial	2011-09-30 07:56:45	100
J	Judicial	2011-09-30 07:56:54	100
S	Subasta	2011-09-30 07:57:07	100
P	Pagado	2011-09-30 07:57:16	100
C	Convenio de Pago	2011-09-30 07:57:26	100
D	Convenio Decaido	2011-09-30 07:57:40	100
\.


--
-- TOC entry 5844 (class 0 OID 5284970)
-- Dependencies: 383
-- Data for Name: judi_tetapa; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.judi_tetapa (cod, nombre, est, supuesto, est_ini, pedir_proc, pedir_dev, pedir_hono, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5845 (class 0 OID 5284974)
-- Dependencies: 384
-- Data for Name: judi_trep; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.judi_trep (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5846 (class 0 OID 5284978)
-- Dependencies: 385
-- Data for Name: judi_tsupuesto; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.judi_tsupuesto (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5639 (class 0 OID 5283732)
-- Dependencies: 178
-- Data for Name: objeto; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.objeto (obj_id, tobj, num, nombre, obj_dato, est, distrib, tdistrib, obs, objunifica, vigencia, claveweb, fchalta, usralta, fchbaja, usrbaja, tbaja, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5847 (class 0 OID 5285072)
-- Dependencies: 386
-- Data for Name: objeto_accion; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.objeto_accion (obj_id, orden, taccion, fecha, fchdesde, fchhasta, expe, dato_ant, dato_ins, obs, est, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5848 (class 0 OID 5285079)
-- Dependencies: 387
-- Data for Name: objeto_computa; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.objeto_computa (computa_id, tobj, funcion, formula, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5849 (class 0 OID 5285086)
-- Dependencies: 388
-- Data for Name: objeto_computa_campo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.objeto_computa_campo (computa_id, campo) FROM stdin;
\.


--
-- TOC entry 5850 (class 0 OID 5285089)
-- Dependencies: 389
-- Data for Name: objeto_item; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.objeto_item (obj_id, subcta, orden, trib_id, item_id, perdesde, perhasta, param1, param2, expe, obs, exen_id, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5851 (class 0 OID 5285097)
-- Dependencies: 390
-- Data for Name: objeto_misc; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.objeto_misc (obj_id, orden, fecha, titulo, detalle, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5852 (class 0 OID 5285104)
-- Dependencies: 391
-- Data for Name: objeto_persona; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.objeto_persona (obj_id, num, tobj, tvinc, porc, est, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5853 (class 0 OID 5285108)
-- Dependencies: 392
-- Data for Name: objeto_rubro; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.objeto_rubro (obj_id, rubro_id, perdesde, perhasta, fiscaliza, subcta, cant, tipo, est, expe, obs, porc, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5854 (class 0 OID 5285116)
-- Dependencies: 393
-- Data for Name: objeto_taccion; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.objeto_taccion (cod, tobj, nombre, interno, estactual, estnuevo, desdehasta, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5855 (class 0 OID 5285120)
-- Dependencies: 394
-- Data for Name: objeto_tbaja; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.objeto_tbaja (cod, tobj, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5856 (class 0 OID 5285124)
-- Dependencies: 395
-- Data for Name: objeto_tdistrib; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.objeto_tdistrib (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5857 (class 0 OID 5285128)
-- Dependencies: 396
-- Data for Name: objeto_test; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.objeto_test (tobj, cod, nombre, estgral, fchmod, usrmod) FROM stdin;
2	E	Exento	A	2006-02-28 09:25:24	100
2	A	Activo	A	2006-06-20 15:42:05	100
2	U	Unificado	B	2006-06-08 11:55:27	100
3	A	Activo	A	2006-06-30 09:27:39	100
3	B	Baja	B	2006-06-30 09:27:54	100
2	T	Baja Temporal	A	2006-06-08 11:54:02	100
2	B	Baja	B	2006-06-08 11:54:49	100
3	E	Exento	A	2006-11-04 08:32:59	100
4	D	Disponible	A	2006-06-08 11:56:43	100
4	O	Ocupado	A	2006-06-08 11:56:56	100
1	A	Activo	A	2006-06-08 11:57:16	100
1	B	Baja	B	2006-06-08 16:40:44	100
4	B	Baja	B	2006-06-08 16:41:00	100
1	U	Unificado	B	2006-06-08 16:41:21	100
4	U	Unificado	B	2006-06-08 16:41:34	100
1	E	Exento	A	2006-06-08 16:42:11	100
4	R	Reservado	A	2007-01-22 07:54:59	100
6	A	Activo	A	2012-04-19 12:15:46.210645	100
6	B	Baja	B	2012-04-19 12:15:46.210645	100
6	E	Exento	A	2012-04-19 12:15:46.210645	100
5	A	Activo	A	2012-06-12 07:43:34.66149	100
5	B	Baja	B	2012-06-12 07:43:34.724321	100
5	E	Exento	A	2012-06-12 07:43:34.793065	100
4	E	Exento	E	2012-09-25 08:14:53.793142	100
2	F	Ficticio	B	2013-08-07 10:23:54.241631	100
1	M	PH Madre	A	2017-08-11 13:44:45.459469	100
\.


--
-- TOC entry 5858 (class 0 OID 5285132)
-- Dependencies: 397
-- Data for Name: objeto_tipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.objeto_tipo (cod, nombre, nombre_redu, campo_clave, letra, autoinc, est, fchmod, usrmod) FROM stdin;
4	Cementerio	CEM		E	1	A	2015-07-16 10:32:03.576429	100
2	Comercio	COM		C	1	A	2006-02-22 00:00:00	100
5	Rodado	ROD	0	R	1	A	2015-08-06 08:38:06.574674	100
1	Inmueble	INM	0	I	1	A	2015-08-10 09:15:59.689361	100
3	Persona	PER	0	P	1	A	2015-08-14 07:34:46.340616	100
\.


--
-- TOC entry 5859 (class 0 OID 5285140)
-- Dependencies: 398
-- Data for Name: objeto_trib; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.objeto_trib (obj_id, trib_id, perdesde, perhasta, cat, fchalta, expe, base, cant, sup, obs, est, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5860 (class 0 OID 5285145)
-- Dependencies: 399
-- Data for Name: objeto_trib_cat; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.objeto_trib_cat (trib_id, cat, nombre, det, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5861 (class 0 OID 5285149)
-- Dependencies: 400
-- Data for Name: objeto_trubro; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.objeto_trubro (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5862 (class 0 OID 5285153)
-- Dependencies: 401
-- Data for Name: objeto_var; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.objeto_var (obj_id, var_id, valor) FROM stdin;
\.


--
-- TOC entry 5863 (class 0 OID 5285219)
-- Dependencies: 402
-- Data for Name: osm; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.osm (obj_id, subcta, ctaosm, fchinicio, tliq, tipomedidor, nummedidor, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5864 (class 0 OID 5285226)
-- Dependencies: 403
-- Data for Name: osm_consumo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.osm_consumo (obj_id, subcta, anio, cuota, fchlect, tlect, lect_ant, lect_act, consumo, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5865 (class 0 OID 5285230)
-- Dependencies: 404
-- Data for Name: osm_tipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.osm_tipo (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5866 (class 0 OID 5285234)
-- Dependencies: 405
-- Data for Name: osm_tlect; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.osm_tlect (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5867 (class 0 OID 5285238)
-- Dependencies: 406
-- Data for Name: osm_tliq; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.osm_tliq (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5868 (class 0 OID 5285242)
-- Dependencies: 407
-- Data for Name: osm_treccons; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.osm_treccons (cod, nombre, item_id, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5645 (class 0 OID 5283830)
-- Dependencies: 184
-- Data for Name: persona; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.persona (obj_id, inscrip, tipo, tdoc, ndoc, fchnac, sexo, nacionalidad, estcivil, clasif, iva, cuit, ag_rete, ag_rete_manual, tel, cel, mail, exis_doc, exis_insc, exis_foto, est_ib, ib, orgjuri, tipoliq, contador, contador_verdeuda, fchalta_ib, fchbaja_ib, nombre_fantasia, tbaja_ib) FROM stdin;
\.


--
-- TOC entry 5870 (class 0 OID 5285248)
-- Dependencies: 409
-- Data for Name: persona_ajuste; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.persona_ajuste (aju_id, fecha, obj_id, nombre, tdoc, ndoc, cuit, domicilio, est, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5871 (class 0 OID 5285254)
-- Dependencies: 410
-- Data for Name: persona_rela; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.persona_rela (obj_id, obj_rela, trela, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5872 (class 0 OID 5285258)
-- Dependencies: 411
-- Data for Name: persona_socio; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.persona_socio (obj_id, socio_id, porc, nombre, domi, tel, contacto, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5873 (class 0 OID 5285262)
-- Dependencies: 412
-- Data for Name: persona_tbajaib; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.persona_tbajaib (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5874 (class 0 OID 5285266)
-- Dependencies: 413
-- Data for Name: persona_tclasif; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.persona_tclasif (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5875 (class 0 OID 5285270)
-- Dependencies: 414
-- Data for Name: persona_tdoc; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.persona_tdoc (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5876 (class 0 OID 5285274)
-- Dependencies: 415
-- Data for Name: persona_test_ib; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.persona_test_ib (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5877 (class 0 OID 5285278)
-- Dependencies: 416
-- Data for Name: persona_testcivil; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.persona_testcivil (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5646 (class 0 OID 5283842)
-- Dependencies: 185
-- Data for Name: persona_tipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.persona_tipo (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5878 (class 0 OID 5285282)
-- Dependencies: 417
-- Data for Name: persona_tnac; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.persona_tnac (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5879 (class 0 OID 5285286)
-- Dependencies: 418
-- Data for Name: persona_trela; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.persona_trela (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5880 (class 0 OID 5285290)
-- Dependencies: 419
-- Data for Name: persona_tsexo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.persona_tsexo (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5881 (class 0 OID 5285294)
-- Dependencies: 420
-- Data for Name: persona_tvinc; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.persona_tvinc (cod, nombre, tobj, fchmod, usrmod) FROM stdin;
2	Adjudicatario	1	2006-02-28 09:29:05	100
3	Poseedor	1	2006-02-28 09:29:24	100
4	Responsable	4	2006-02-28 09:29:40	100
1	Titular	0	2006-02-28 09:28:43	100
6	Usufructuario	1	2015-06-25 10:27:48	100
5	Comprador	0	2014-01-17 08:43:59	100
\.


--
-- TOC entry 5882 (class 0 OID 5285298)
-- Dependencies: 421
-- Data for Name: plan; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.plan (plan_id, tplan, obj_id, num, resp, resptdoc, respndoc, resptel, nominal, accesor, multa, financia, sellado, anticipo, origen, tpago, caja_id, temple, temple_area, bco_suc, bco_tcta, tpago_nro, cuotas, montocuo, descnominal, descinteres, descmulta, interes, obs, est, fchalta, usralta, fchbaja, fchimputa, fchdecae, fchconsolida, planant, distrib, tdistrib, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5883 (class 0 OID 5285314)
-- Dependencies: 422
-- Data for Name: plan_config; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.plan_config (cod, nombre, sistema, aldia, aplica, deuda, cantcuotas, importecuota, sinplan, aldiadesde, aldiahasta, aplicadesde, aplicahasta, mindeuda, maxdeuda, mincantcuo, maxcantcuo, minmontocuo, maxmontocuo, diavenc, descnominal, descinteres, descmulta, vigenciadesde, vigenciahasta, tactiva, tactivaporc, interes, sellado, anticipo, anticipocuota, anticipomanual, multa, usarctaper, cta_id, cta_id_rec, cta_id_sellado, cta_id_multa, texto_id, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5884 (class 0 OID 5285324)
-- Dependencies: 423
-- Data for Name: plan_config_decaer; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.plan_config_decaer (tplan, origen, tpago, caja_id, cant_atras, cant_cons, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5885 (class 0 OID 5285329)
-- Dependencies: 424
-- Data for Name: plan_config_trib; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.plan_config_trib (tplan, trib_id) FROM stdin;
\.


--
-- TOC entry 5886 (class 0 OID 5285332)
-- Dependencies: 425
-- Data for Name: plan_config_usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.plan_config_usuario (tplan, usr_id) FROM stdin;
\.


--
-- TOC entry 5887 (class 0 OID 5285335)
-- Dependencies: 426
-- Data for Name: plan_cuota; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.plan_cuota (plan_id, ctacte_id, cuota, capital, financia, cuota_adelanta) FROM stdin;
\.


--
-- TOC entry 5888 (class 0 OID 5285339)
-- Dependencies: 427
-- Data for Name: plan_periodo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.plan_periodo (plan_id, ctacte_id, nominalreal, nominal, accesorreal, accesor, multareal, multa, nominalcub, accesorcub, multacub, estant) FROM stdin;
\.


--
-- TOC entry 5889 (class 0 OID 5285342)
-- Dependencies: 428
-- Data for Name: plan_temple; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.plan_temple (cod, caja_id, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5890 (class 0 OID 5285346)
-- Dependencies: 429
-- Data for Name: plan_test; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.plan_test (cod, nombre, fchmod, usrmod) FROM stdin;
1	Vigente	2006-02-28 08:18:42	100
4	Pagado	2006-02-28 08:21:34	100
5	Imputado	2006-02-28 08:21:51	100
2	Borrado	2006-02-28 08:19:05	100
3	Decaído	2006-02-28 08:20:14	100
\.


--
-- TOC entry 5891 (class 0 OID 5285350)
-- Dependencies: 430
-- Data for Name: plan_torigen; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.plan_torigen (cod, nombre, fchmod, usrmod) FROM stdin;
5	De Oficio	2007-08-02 08:56:55	100
1	Espontánea	2006-02-28 09:40:32	100
2	Intimación	2006-02-28 09:40:52	100
4	Refinanciación	2006-02-28 09:41:20	100
3	Judicial	2006-02-28 09:41:08	100
\.


--
-- TOC entry 5892 (class 0 OID 5285354)
-- Dependencies: 431
-- Data for Name: plan_tpago; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.plan_tpago (cod, nombre, fchmod, usrmod) FROM stdin;
1	General	2006-02-28 09:32:58	100
2	Cheque Diferido	2006-02-28 09:33:54	100
3	Débito	2011-12-28 09:13:56	100
\.


--
-- TOC entry 5893 (class 0 OID 5285358)
-- Dependencies: 432
-- Data for Name: plan_tsistema; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.plan_tsistema (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5894 (class 0 OID 5285401)
-- Dependencies: 433
-- Data for Name: resol; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.resol (resol_id, nombre, trib_id, perdesde, perhasta, funcion, formula, filtro, anual, cant_anio, detalle, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5895 (class 0 OID 5285412)
-- Dependencies: 434
-- Data for Name: resol_aux; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.resol_aux (resol_id, aux) FROM stdin;
\.


--
-- TOC entry 5896 (class 0 OID 5285415)
-- Dependencies: 435
-- Data for Name: resol_local; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.resol_local (resol_id, varlocal, tipo, valor) FROM stdin;
\.


--
-- TOC entry 5897 (class 0 OID 5285418)
-- Dependencies: 436
-- Data for Name: resol_salida; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.resol_salida (resol_id, varsalida, item_id, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5898 (class 0 OID 5285422)
-- Dependencies: 437
-- Data for Name: resol_tabla; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.resol_tabla (tabla_id, nombre, resol_id, cantcol, cantcolfijas, uso_paramstr, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5899 (class 0 OID 5285428)
-- Dependencies: 438
-- Data for Name: resol_tabla_col; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.resol_tabla_col (tabla_id, orden, nombre, tipo, param, compara) FROM stdin;
\.


--
-- TOC entry 5900 (class 0 OID 5285431)
-- Dependencies: 439
-- Data for Name: resol_tabla_dato; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.resol_tabla_dato (tabla_id, dato_id, perdesde, perhasta, paramstr, param1, param2, param3, param4, param5, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5902 (class 0 OID 5285437)
-- Dependencies: 441
-- Data for Name: ret; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ret (retdj_id, ag_rete, anio, mes, cant, monto, fchpresenta, est, ctacte_id, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5903 (class 0 OID 5285444)
-- Dependencies: 442
-- Data for Name: ret_det; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ret_det (ret_id, retdj_id, obj_id, numero, lugar, fecha, tcomprob, comprob, base, ali, monto, fchaplic, ctacte_id, est, fchmod, usrmod, ag_rete) FROM stdin;
\.


--
-- TOC entry 5905 (class 0 OID 5285450)
-- Dependencies: 444
-- Data for Name: ret_tcomprob; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ret_tcomprob (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5906 (class 0 OID 5285454)
-- Dependencies: 445
-- Data for Name: ret_test; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ret_test (cod, nombre, fchmod, usrmod) FROM stdin;
P	Pendiente	2016-03-28 15:28:50.544218	100
B	Baja	2016-03-28 15:28:50.544218	100
I	Imputado	2016-03-28 15:28:50.544218	100
D	Devuelta	2016-03-28 15:28:50.544218	100
A	Aprobado	2016-06-21 10:05:22.780342	100
O	Aplic.de Oficio	2017-11-27 13:59:25.898559	100
\.


--
-- TOC entry 5907 (class 0 OID 5285458)
-- Dependencies: 446
-- Data for Name: rodado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rodado (obj_id, talta, perini, tliq, aforo_id, cat, marca, modelo, modelo_nom, anio, dominio, dominioant, marcamotor, nromotor, marcachasis, nrochasis, peso, cilindrada, deleg, color, combustible, uso, conductor, fchcompra, tform, remito, remito_anio) FROM stdin;
\.


--
-- TOC entry 5908 (class 0 OID 5285468)
-- Dependencies: 447
-- Data for Name: rodado_aforo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rodado_aforo (aforo_id, origen, fabr, marca, tipo, modelo, tvehic, marca_nom, tipo_nom, modelo_nom, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5909 (class 0 OID 5285472)
-- Dependencies: 448
-- Data for Name: rodado_aforo_val; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rodado_aforo_val (aforo_id, anio, anioval, valor, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5910 (class 0 OID 5285477)
-- Dependencies: 449
-- Data for Name: rodado_marca; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rodado_marca (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5911 (class 0 OID 5285481)
-- Dependencies: 450
-- Data for Name: rodado_modelo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rodado_modelo (cod, nombre, marca, cat, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5912 (class 0 OID 5285486)
-- Dependencies: 451
-- Data for Name: rodado_talta; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rodado_talta (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5913 (class 0 OID 5285490)
-- Dependencies: 452
-- Data for Name: rodado_tcat; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rodado_tcat (cod, nombre, gru, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5914 (class 0 OID 5285494)
-- Dependencies: 453
-- Data for Name: rodado_tcombustible; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rodado_tcombustible (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5915 (class 0 OID 5285498)
-- Dependencies: 454
-- Data for Name: rodado_tdeleg; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rodado_tdeleg (cod, nombre, encargado, prov_id, localidad, domi, cp, tel, fax, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5916 (class 0 OID 5285502)
-- Dependencies: 455
-- Data for Name: rodado_tform; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rodado_tform (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5917 (class 0 OID 5285506)
-- Dependencies: 456
-- Data for Name: rodado_tliq; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rodado_tliq (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5918 (class 0 OID 5285510)
-- Dependencies: 457
-- Data for Name: rodado_torigen; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rodado_torigen (cod, nombre, fchmod, usrmod) FROM stdin;
N	Nacional	2012-06-12 08:16:09.584994	100
I	Importado	2012-06-12 08:16:09.668755	100
\.


--
-- TOC entry 5919 (class 0 OID 5285514)
-- Dependencies: 458
-- Data for Name: rodado_tuso; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rodado_tuso (cod, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5920 (class 0 OID 5285518)
-- Dependencies: 459
-- Data for Name: rodado_val; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rodado_val (anioval, gru, anio, pesodesde, pesohasta, valor, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5921 (class 0 OID 5285522)
-- Dependencies: 460
-- Data for Name: rubro; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rubro (rubro_id, nombre, nomen_id, grupo, tunidad, osmreccons, tipif_id, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5922 (class 0 OID 5285527)
-- Dependencies: 461
-- Data for Name: rubro_general; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rubro_general (nomen_id, pi, perdesde, perhasta, alicuota, minimo, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 6041 (class 0 OID 5316994)
-- Dependencies: 671
-- Data for Name: rubro_grupo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rubro_grupo (cod, nomen_id, nombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5923 (class 0 OID 5285535)
-- Dependencies: 462
-- Data for Name: rubro_temporada; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rubro_temporada (cuota, temporada) FROM stdin;
\.


--
-- TOC entry 5924 (class 0 OID 5285538)
-- Dependencies: 463
-- Data for Name: rubro_tfcalculo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rubro_tfcalculo (cod, nombre, fchmod, usrmod) FROM stdin;
3	Fijo	2006-11-13 16:42:17	100
4	Fijo x Cantidad	2006-11-13 16:42:25	100
5	Fijo x Base	2006-11-13 16:42:38	100
6	Exento	2006-11-13 16:43:04	100
0	Régimen General	2006-11-13 16:41:40	100
1	Alícuota y Mínimo	2006-11-13 16:41:54	100
2	Alícuota sin Mínimo	2007-09-03 00:00:00	100
7	Exento si Base< Fijo	2007-08-31 17:12:59	100
\.


--
-- TOC entry 5925 (class 0 OID 5285542)
-- Dependencies: 464
-- Data for Name: rubro_tminimo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rubro_tminimo (cod, nombre, fchmod, usrmod) FROM stdin;
2	Fijo	2007-03-21 17:10:41	100
3	Fijo x Cantidad	2007-03-21 17:10:54	100
4	Fijo x Cant.(Exced)	2008-06-03 15:36:00	100
5	Fijo (s/Temp y Zona)	2009-01-15 15:04:15	100
6	Fijo x Cant(Temp/Zn)	2009-01-15 15:04:15	100
8	Fijo x Cant(s/Zona)	2010-01-26 17:36:15	100
0	Mínimo General	2007-03-21 17:10:24	100
1	Sin Mnimo	2007-03-21 17:10:33	100
7	Fijo (según Zona)	2010-01-26 17:36:15	100
\.


--
-- TOC entry 6039 (class 0 OID 5316928)
-- Dependencies: 669
-- Data for Name: rubro_tnomen; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rubro_tnomen (nomen_id, tobj, nombre, perdesde, perhasta, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5926 (class 0 OID 5285546)
-- Dependencies: 465
-- Data for Name: rubro_vigencia; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rubro_vigencia (rubro_id, perdesde, perhasta, tcalculo, tminimo, alicuota, alicuota_atras, minimo, minalta, fijo, canthasta, porc, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5972 (class 0 OID 5285678)
-- Dependencies: 511
-- Data for Name: texto; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.texto (texto_id, tuso, nombre, titulo, detalle, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5973 (class 0 OID 5285686)
-- Dependencies: 512
-- Data for Name: texto_tuso; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.texto_tuso (cod, nombre, proceso) FROM stdin;
6	Judiciales	3536
7	Cuenta Corriente	3537
9	Libre Deuda	3539
11	Cementerio	3541
10	Mejoras	3540
12	Fiscaliza	3542
14	Juzgado	3544
4	Intimación	3534
5	Emisión	3535
15	Declaración jurada	3545
8	Obras Privadas	3538
1	Convenio de Pago	3531
16	Adhesión Débito	3546
17	Clave Fiscal	3702
18	Inscrip. Sanitaria	3530
13	Rodado	3286
19	Mail Boleta	3547
20	Legajo	3548
\.


--
-- TOC entry 5974 (class 0 OID 5285689)
-- Dependencies: 513
-- Data for Name: texto_var; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.texto_var (tuso, variablenombre, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5975 (class 0 OID 5285693)
-- Dependencies: 514
-- Data for Name: trib; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.trib (trib_id, nombre, nombre_redu, nombre_reduhbank, tobj, tipo, genestcta, texto_id, ucm, calc_rec, calc_rec_tasa, cta_id_rec, rec_venc2, cta_id_redon, prescrip, uso_subcta, uso_mm, oficina, bol_domimuni, bol_domi, bol_tel, bol_mail, quitafaci, compensa, est, dj_tribprinc, inscrip_req, inscrip_auto, inscrip_incomp, fchmod, usrmod, cta_id_desc, calc_act, calc_act_faci, cta_id_act) FROM stdin;
1	Convenios de Pago	CONVENIO	3	0	0	0	0	0	1	0.00	0	0.000	0	5	0	0	0	f				0.00	1	A	0	0	0	0	2019-05-27 14:01:37	100	0	0	0	0
2	Facilidades de Pago	FACILIDAD		0	0	0	0	0	1	0.00	0	0.000	0	5	0	0	0	f				0.00	1	A	0	0	0	0	2019-05-27 14:01:37	100	0	0	0	0
4	Fiscalización	Fiscaliza		2	0	0	0	0	1	0.00	0	0.000	0	5	0	0	0	f				0.00	0	A	0	0	0	0	2019-05-27 14:01:37	100	0	0	0	0
5	Gastos Judiciales	Gtos. Jud.		3	0	0	0	0	1	0.00	0	0.000	0	5	0	0	0	f				0.00	1	A	0	0	0	0	2019-05-27 14:01:37	100	0	0	0	0
6	Caja Externa	CAJA EXT.		3	0	0	0	0	1	0.00	0	0.000	0	5	0	0	0	f				0.00	0	A	0	0	0	0	2019-05-27 14:01:37	100	0	0	0	0
7	Caja Interés	CAJA INT.		3	0	0	0	0	1	0.00	0	0.000	0	5	0	0	0	f				0.00	0	A	0	0	0	0	2019-05-27 14:01:37	100	0	0	0	0
8	Recibo Manual	Recibo		0	0	0	0	0	1	0.00	0	0.000	0	5	0	0	0	f				0.00	0	A	0	0	0	0	2019-05-27 14:01:37	100	0	0	0	0
10	Pago a Cuenta	Pago Cta.		0	0	0	0	0	1	0.00	0	0.000	0	5	0	0	0	f				0.00	1	A	0	0	0	0	2019-05-27 14:01:37	100	0	0	0	0
\.


--
-- TOC entry 5976 (class 0 OID 5285710)
-- Dependencies: 515
-- Data for Name: trib_tgenestcta; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.trib_tgenestcta (cod, nombre, fchmod, usrmod) FROM stdin;
0	No genera	2011-01-28 14:50:01	100
1	Genera Estado Cuenta	2011-01-28 14:50:17	100
2	Genera Estado Plan	2011-01-28 14:50:31	100
\.


--
-- TOC entry 5977 (class 0 OID 5285714)
-- Dependencies: 516
-- Data for Name: trib_tipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.trib_tipo (cod, nombre, detalle, fchmod, usrmod) FROM stdin;
0	Interno		2011-09-15 10:36:07	100
1	Emisión	Tributo de emisión masiva, tales como la Tasa General Inmobiliaria o la Tasa de Barrido y Limpieza.	2006-07-10 11:40:04	100
2	Declarativo	Tributo de tipo declarativo, que requiere de una declaración jurada por parte de los contribuyentes, tal como en el caso de la Tasa de Seguridad e Higiene.	2006-07-10 11:40:14	100
3	Eventual	Tributo que se liquida ocacionalmente, en forma manual, por ejemplo algunos Derechos por presentación de planos o bien servicios especiales, tales como desmalezamiento, etc.	2006-07-10 11:40:24	100
4	Periódico	Tributo de liquidación manual, a diferencia del eventual tienen definidos los períodos y sus vencimientos. Por ejemplo algún tipo de matrícula, que se pague en forma anual y tenga definidos los vencimientos.	2006-07-10 11:40:38	100
5	Item por Objeto	Tributo que se liquida de acuerdo a los ítems definidos en el objeto, en caso que los mismos existan. Por ejemplo la ocupación de la vía pública (anual o mensual). Este tributo se emite de forma masiva.	2006-07-10 11:40:50	100
6	Sellado	Tributo especial, que no tienen liquidación en la cuenta corriente y se pagan directamente en caja. Por ejemplo las distintas actuaciones administrativas que cobra el municipio.	2006-07-10 11:41:08	100
7	Boleto	Caso especial de sellado. Para estos casos se dispone de un formulario especial en el sistema de caja. Por ejemplo el cobro de entradas o pases que realice el municipio. El ticket posee un diseño especial.	2007-03-09 17:33:43	100
\.


--
-- TOC entry 5978 (class 0 OID 5285718)
-- Dependencies: 517
-- Data for Name: trib_venc; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.trib_venc (trib_id, anio, cuota, fchvenc1, fchvenc2, fchvencanual, segun_term, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5979 (class 0 OID 5285722)
-- Dependencies: 518
-- Data for Name: trib_venc_cuit; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.trib_venc_cuit (trib_id, anio, cuota, term, fchvenc) FROM stdin;
\.


--
-- TOC entry 5980 (class 0 OID 5285725)
-- Dependencies: 519
-- Data for Name: trib_venc_item; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.trib_venc_item (trib_id, anio, cuota, item_id, monto) FROM stdin;
\.


--
-- TOC entry 5985 (class 0 OID 5286416)
-- Dependencies: 613
-- Data for Name: val_coefmej; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.val_coefmej (cat, est, ant, coef) FROM stdin;
\.


--
-- TOC entry 5986 (class 0 OID 5286419)
-- Dependencies: 614
-- Data for Name: val_inm_coef1; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.val_inm_coef1 (freini, frefin, fonini, fonfin, coef) FROM stdin;
\.


--
-- TOC entry 5987 (class 0 OID 5286422)
-- Dependencies: 615
-- Data for Name: val_inm_coef2; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.val_inm_coef2 (supini, supfin, freini, frefin, valini, valfin, coef) FROM stdin;
\.


--
-- TOC entry 5988 (class 0 OID 5286425)
-- Dependencies: 616
-- Data for Name: val_inm_coef3; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.val_inm_coef3 (fonini, fonfin, supini, supfin, coef) FROM stdin;
\.


--
-- TOC entry 5989 (class 0 OID 5286428)
-- Dependencies: 617
-- Data for Name: val_inm_coef4; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.val_inm_coef4 (supini, supfin, coef) FROM stdin;
\.


--
-- TOC entry 5990 (class 0 OID 5286431)
-- Dependencies: 618
-- Data for Name: val_inm_coef5; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.val_inm_coef5 (freini, frefin, fonini, fonfin, coef) FROM stdin;
\.


--
-- TOC entry 5991 (class 0 OID 5286434)
-- Dependencies: 619
-- Data for Name: val_inm_coef6; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.val_inm_coef6 (freini, frefin, fonini, fonfin, coef) FROM stdin;
\.


--
-- TOC entry 5992 (class 0 OID 5286437)
-- Dependencies: 620
-- Data for Name: val_mej; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.val_mej (cat, form, perdesde, perhasta, valor) FROM stdin;
\.


--
-- TOC entry 5981 (class 0 OID 5285963)
-- Dependencies: 560
-- Data for Name: config; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.config (ctarecargo, interes_min, ctaredondeo, porcredondeo, ctacte_anio_desde, texto_ucm, ucm1, ucm2, titulo_libredeuda, titulo2_libredeuda, mensaje_libredeuda, proxvenc_libredeuda, copias_recl, calle_recl, path_recl, usar_codcalle_loc, inm_valida_nc, inm_valida_frente, inm_gen_osm, trib_op_matric, judi_item_gasto, judi_item_hono, ctadiferencia, itemcobro, itemcomision, itemcomisionbco, cajaverifdebito, repo_usu_nom, djfaltantes, op_hab_plazas, per_plan_decaido, comer_hab_vence, juz_origentransito1, juz_origentransito2, ctarectc, per_pedir_cuit, per_pedir_doc, ib_modo, domi_pedir_puerta, com_validar_ib, agrete_trib_id, agrete_path, ret_sin_aprob, inm_phmadre, bol_path, bol_mail, bol_mail_clave, bol_mail_host, bol_mail_port, cta_id_act) FROM stdin;
35	0	0	1.00	10	UF	7.660	8.250	Certificado de Deuda Líquida y Exigible	Certificado de Libre Deuda	Certificado con validez por 30 días desde su emisión.	0	1	0	./uploads/recl/	f	t	f	f	0	0	0	0	0	0	0	0	f	0	f	f	12	6	6	0	f	f	A	t	1	65	/var/www/html/samservweb/uploads/ag_rete/	0	0	\N	\N	\N	\N	\N	0
\.


--
-- TOC entry 5994 (class 0 OID 5287119)
-- Dependencies: 622
-- Data for Name: config_cem_nc; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.config_cem_nc (campo, aplica, nombre, max_largo, solo_nro, orden) FROM stdin;
cuadro_id	t	Cuadro	2	f	\N
cuerpo_id	t	Cuerpo	2	f	\N
nume	t	Nro	4	f	\N
\.


--
-- TOC entry 6040 (class 0 OID 5316938)
-- Dependencies: 670
-- Data for Name: config_ddjj; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.config_ddjj (trib_id, itemcompensamulta, itembasico, itemrete, itembonif, itemsaldo, djanual, nversion, cm_dj, cm_min, ai_dj, ai_min, perm_retemanual, perm_djfalta, perm_saldo, perm_bonif, perm_anterior, perm_tipos, nomen_id_1, nomen_id_2, fchmod, usrmod) FROM stdin;
23	0	8	11	9	10	0	1	0	0	0	0	1	1	1	1	1		N		2018-02-21 14:40:22.996613	109
\.


--
-- TOC entry 5995 (class 0 OID 5287131)
-- Dependencies: 623
-- Data for Name: config_fin_part; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.config_fin_part (anio, caracter, niv, nombre, largo) FROM stdin;
2016	1	1	subgru	2
2016	1	2	rubro	2
2016	1	3	cuenta	2
2016	1	4	subcta	2
2016	1	5	conc	2
2016	1	6	subcon	2
2016	1	7	niv7	2
2016	1	8	niv8	2
2016	1	9	niv9	2
2017	1	1	subgru	2
2017	1	2	rubro	2
2017	1	3	cuenta	2
2017	1	4	subcta	2
2017	1	5	conc	2
2017	1	6	subcon	2
2017	1	7	niv7	2
2017	1	8	niv8	2
2017	1	9	niv9	2
2016	2	1	gasto	2
2016	2	2	fin	2
2016	2	3	fun	2
2016	2	4	objeto	3
2016	2	5	niv5	2
2016	2	6	niv6	2
2016	2	7	niv7	2
2016	2	8	niv8	2
2016	2	9	niv9	2
2017	2	1	gasto	2
2017	2	2	fin	2
2017	2	3	prog	2
2017	2	4	proy	2
2017	2	5	ob-act	2
2017	2	6	objeto	3
2017	2	7	niv7	2
2017	2	8	niv8	2
2017	2	9	niv9	2
2018	1	1	subgru	2
2018	1	2	rubro	2
2018	1	3	cuenta	2
2018	1	4	subcta	3
2018	1	5	conc	2
2018	1	6	subcon	2
2018	1	7	niv7	4
2018	1	8	niv8	4
2018	1	9	niv9	4
2018	2	1	gasto	2
2018	2	2	fin	2
2018	2	3	prog	2
2018	2	4	proy	3
2018	2	5	ob-act	2
2018	2	6	objeto	2
2018	2	7	niv7	4
2018	2	8	niv8	4
2018	2	9	niv9	4
\.


--
-- TOC entry 5996 (class 0 OID 5287137)
-- Dependencies: 624
-- Data for Name: config_inm_mej; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.config_inm_mej (campo, nombre) FROM stdin;
item01	Fach
item02	Techo
\.


--
-- TOC entry 5997 (class 0 OID 5287140)
-- Dependencies: 625
-- Data for Name: config_inm_nc; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.config_inm_nc (campo, aplica, nombre, max_largo, solo_nro, orden) FROM stdin;
s1	t	Circuns	1	t	\N
s2	t	Sector	1	t	\N
s3	t	Chacra	2	f	\N
manz	t	Manz	4	f	\N
parc	t	Parc	3	f	\N
\.


--
-- TOC entry 5998 (class 0 OID 5287179)
-- Dependencies: 626
-- Data for Name: muni_datos; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.muni_datos (codigo, nombre, loc_id, cod_ent, iva, cuit, ingbrutos, logo, logo_grande, logo_talon, incluir_logo2, presidente, domi, tel, mail, skype, url, recl_domi, recl_tel, recl_mail, fisc_domi, fisc_tel, fisc_mail, juz_domi, juz_tel, juz_mail, fchmod, usrmod) FROM stdin;
9111	Municipalidad de Camarones	647	9203	5	30658342025		\\x89504e470d0a1a0a0000000d494844520000002f0000003c0806000000f665e399000000017352474200aece1ce90000000467414d410000b18f0bfc6105000000097048597300000ec300000ec301c76fa8640000001874455874536f667477617265005061696e742e4e45542076332e3336a9e7e22500001c91494441546843ed9a09545467b6efed189318639c15117044448d03288a22ca8cccf33c57315501051420a3200a280838003283cc088ab328ce38b7269a98c4216d12ed444de21413e2fc7b9f69eef2ddd5dd6f75dfdbefdef5deba7badbdcea953e7ecefbff7b7c73ad5e77fe8ff078acb29539878c564eb5a0565cfb70bc936728fca367295672f760cceb6f353646714542b7a6ffdefa7949ce229f2a4d549d2f895b72dfca26ffb26153c0fc82843bea681c8bca6df599eb39988ec5a82d34bf056ae7e6eea137ddb4d9eb6d7c637da6a6d69cb945e51ff7514bdb270a05499b9dada5b71c555b602f9aa2aa20a9a7089cf47df238669e6014cb30842db3c9029e6414cb70e6181671cbe699b882fea409653836774360e4171571232d70939f27ebda2ffef52406c86a57b640a76b274e4b90d78279732cd32987e130ce93bd680bee397d06f9209ef689ad06fa2913837e6ed094b787bfc62fa8e33a4bfa611faee4a242bab51166ec1599681b15b78f3f2bc0af3de25fef5b4a2a85e2526637d978967d4fda0e5a5f82e2f6792b06affc9460c9c628cfa3c470cdda2718fca223abb9a15a51d6496ef22a558583aab165b5916336dc31939c75128b68401dac6cc728c20747523928c0a1c8293ef5bfb463bf72ef7afa3c0c4d583bc62569eb10c5c46ccda564c0232f870da52fa4f3264bab92f92e40de4371ca26af705ea3a3fa67ebfe0039f08be447dd767d41df88ceafd17a9dc779195359db8c6ac6682a1b3d8110386ce76c03e663dd1056d2c0d48782451aedcb8a971e7a0bfacfc9fa465cbd76bb84993ce9907a610222c38c33a94fe13173371911b61cb4ba8dc71969adde769ecfa9cfa835f507ff81a8d47aed374f42b9a8f8aa3f8dc7c44b038afeb12df0baeedbc4849fb093c62d63066be0bfd848b2df44921bab00deba06524e46c3c57b96597462f84ff186dde716c50486cce396bef04a2563789000ce203adc5e8bb840b7768a374c7692a769da776df65c15fd070e8ba00ff2d75476e527fe45b1a0f7f23f86b1ac4b1fea8b8def515b55dd7a9d9ff059bf77d4ad9f633c4e4d533d9c48777262f619e8792f8f5428140254b7de4a1bd30fe6314915658b4c4554178661d538d257ca06d8a914f1c59557b2868394cd1d66e360a5729d97d999ace6bc22dbea46cff9fd8b4ff2bc15729ebbc42d9de2b6cda7b8d5271adfcf5b950729350b674fb276cda7e9efc2dc75851b35b64256fde9db81003d758e20b5b31f58e7d66e31f6dd90be59f23d79014670307e9a3f0f4624c3da3519d698a83444956592beb9bf653d67e9cea9d6728397091f283d7a8dd7f8d86035f0a7fbfcc66e14235072e5329b8f6e015eaf75da575df151a3ac5e7ae4fa9397851dc7341ecd659aa3b4e52da7284e51b9b307492304a7b014e2293c956d66017b4ec51e29a927f2e8837d6ee32b7f54fbaef1ab90a497c16e64ebe44a666d0dcb193aeeeb39cbdfc15d76efdc88d3b8ff8e6e1536e3e7cce9f7f7ac2773ff470eb875ff8eefe6fdcfab1873fdf7bc2cd1f9e72f7ce2fdcbbf580efbff989efeefdc69feeff2a9e79ccf7f7057fff88cb5fdee6d839a1e4d6bd8444c463ec1244d8aa721cc5faaeb2e44b9109597fe885f67fa6ccf54dfd9ca469cdc65e8928722a3173f2224900dfb27d2b274e1ee5fad52b3cbcff33bff5fcc693273dbc7cfc041ef4f0e2c62d2eb4b5f3e4a71f78f1e2052f9fbd825f5ef0fcfbefb9d65acff58a12ce5556f2ead1cf3c7dfe9c272f9ff1f2e54b5efdf6825f1ff570e3e62dce5ef898f6ed7b8402892c76f023a1a81953df585cfc15ff585be1139531d940e45fbfa462eca4b184472ba92aaf66dfde837c7af913eedcfe8ea78f9fc21301f0c1031e9e38c71715b5dc2ea9e04246364faf5de3d5af4f7825147c72e52a9f97ace350883f5f242fe34cf62a7eb97e85e7cf7a78faea357878f5e2154f9f3ee5dec31fb972e3bad8d9d39496d4e12f89c2459e8c54b41526f6d21b39059b27f642fcfbb4d437bacdd8671921cb8bb0f395905750405b5307ddc7ce73e3dbebdc7bfc23cfc5623cfc99ef3bf7735211cb556534d7fdbcb9e0ebcfdd7d077976e132572a6b392e8f60bfbf3f9f15e673bfb5896faacaf9784b33cf7efd9997cf9ff252ecd0abe7af78f1ec05bf3dfb4db8dd1d2e7e71951d6d7bc85c9187996b1071f9f598892c149dbcaead17e2dfa6b0c402534367f91349ea461c250a22e294945794b36767179f5eb8c8fd0777849584abdcf9819b3b3ab87f781f7785ab5c5428f9d4d681b396d6dc2d29e5b4ab37a7ddbd39b32c911ff7eea2e7dc491e75eee348568e70af1b3cfcf22a0f3efb121edfe3f9777fe6fbcf3fe7d593a7f4f4fccad5afafd37dea18d5351548e40adcc2939165d630c73ce0847b48cac85ea87f4d5265de1e034739212905b80586b32a278b8686460e1e3ec5d737bfe1975f1e09ab3de3f1c797391218c83177577edddbc5f36327f8a3833d972ccd789c9ec4a925a6dcce59cbcbd367b9dbd1cee575f9fcd0ba951f3a8ff2eae3cf391ca5e4704828bf748a384a49e4e4860dc2d57ac48ebee0c79f1e70fe934b6cd9b69dd495d9d87805139b5bc712d7282c3c225d7aa1fe35193a84df7195adc42f229988e8788a8a8a68dfba9d93e73fe3bb9feefdc5ea4f9ff3d3f193ec5d6ac931dd793c2cabe1e9d1c3ecd2d3e193b93a7c6b6bc1551767eea6a6722942ce8f69cbb9e6edcfadd4155ccfcce2715d23d7c2155c14d7ae96ace7fae606ee5db8c42b21f7c5d397c2404ff9f2ead774761d67437139fea10a029459b84466e31db5e24e2fd47f4fa979558af9d6c1cf23448038784ac9c8cca1aca28a1d7b0f70f9cad73cfce58988d11e7a9e3ee6f9ed5b5ccacbe1a8a333475ddcb81317c761ad297c32498bcfb4a7714977167fd49dc105dd99fcb8d48a0796769cd6d4e284c13c8eb8d873c4c78fae90287a6e7ccd8b5f9ef14480ee79fe52c4c02b7e137170f3ee4f9c3c77814aa1983225032b8f10a2441b3dcf36eca7bc92dd6abd90df90a17550b6919b12bf843c7cc363c92a28a3a0aa99dddd17b876f3010f4476b9cf4b7e7cf982474f9ef1ecc1435e7c7a955db64e1c983e874b5a33f8446d1c9f8fd7e6faa4c99cd59c4cf7f8b17cac3e9a8f555438ab3196f38646dc5f93c5cfc78ef0fcdbdb3c1669f29108d87b2f5ef293c83c8f5ebde2675ef1ada81da7afdca47adb1e96af1545d23190d8bc3a8cdd93884aaec8ee85fc17ca2f6b98e42c4d786e1d96859e181ac61b7931df330903691676c935f8acde8ba4a41b49d141c2379d2474e369c2d71f21b5703715411934ce77a04b6b1efb474f64afda64bac6cf60cf8c851cd69ec50915750e8dd662b7b6017bb517b173b22e35260ee42717215fdf4578f129a41b0f23293d849f902f293a4ec0dac378a6b76312b686b9a2f757d573c6366a0dce118558b9c6ee68ed3834b0177a9f3e6ea1299a86ce32dce2d631c942c2d0f93e6858c630d13995192125cc89ed607672177ac9fb9893d8c9dcc42e1627ecc133ac927506a1ec98e74ecbe40554cd32639355182d862e6c9f3e8fa31326b34f7d32edf3ac6936f767a79e0bfba699b256c7967069010b13f6a2bfec087ae23823b983e9a9bbd049de8b5edc6ee644b430c53b9f7136f10c9eeb89eeeb492ca91caf90e5ac2daad7ec85dea78fb6be87e6b4c53ef8096b8cd27765b88194d1964a345c33991c568d76dc5e34930e337999e0a4436809f0f36376e0e393c7dac99654682ca468e2027217ba9022c6c072331376cd19cb293d1576cd9f4883ad25658bada8509b45c7783d0aa79913e698c2dcd89d4c8e3bc4d4e4a3427e1713538f0bf9dd68c71f4453d1ced8806254ac531938df8f09a2150f5a598791b31c475fc51bf0a9abea3b662e09c42b6103efcfb065b8a18c61e6098c745d8386ac8571ca4ec6c677324e58495d28a021c0cf50ee10bd772132e3105658c828b20ca0c1c58bad71615cc9f1e7e1061f7aca45d12af5e7729e3fa7c25d68d1d7a378b621c90bbdf1b24b67b662a7902be4251c625cc26121ffa4383fc384b8e3a84577302ab084e156a90c5b2c65b8981fa439f5ccb192326da1f31bf0218a826e7deb481ca3d6f2ee0c7b86188433c86c1943dd36304ab61d35e541c6c6ed475d2ca49a78049544b1a0f83c3d7a1bfa114dd84b36b22e385e14257fbeaf88e4d9a9b5bcba5ccacb2bd5bcfa4aa4d2f36bb9db10c5819400d2a40a6c838bd18bdcc584d86ec6bd66e5113484ec31f147508d3f2eae1d6774f41e86059533c43a85e146a21dd7b512e03733c7364c04717d6e2ff43e7d6cbc12ba8ddc9661245dc17b3a4e7cb040ca00017e905b3123643b51893b888ad8cae1ca6e86c59f6648fc31460a85c6c71e445bbe8590e055ec8f0de06e950cbe28e3d50f75bc7abc157e3bc08b477b450fb49517570ab9d91846655a28a611f968c4ee46557980d1b1fb041f4035f6a858e728c3e3bb508b39c868c51e864a6b79df2e83fe8601f49fbd14eff4d2dffbfde4ec9aee5ee87dfa183945761bfba4b224309d7766dbf29e4100ef9ac532d02d9f61e12d0c89d9c93061e921ca43820f305804ebd084dda8c7ec657e6835ebe4cbf872b93bcf0ea6f2fc46b1002bc0f73489bcbd959ed7fcb48117770b78722c9e63b961044766a21955874acc7646c6ed6478dc1e86c71c6084903f48ac3332b6939151db192cade25da70cfa2d0e64c05c7b9c133760e49d842cbee00d7803bb886e0bbf152cf68ae7c399c60c5ce8c087267e8c7651325ea42bf5e88dc2129b508d2e66624c311a51ebd08829629a6203d66189d4c67a7223df925f8f86f2e256162f7f2de2c5930da2f52da2e755314f9eade3c5bd153cfd44c6c5321752955274c233991459ca84a81234845c354529ea51e5c2d78b19af28669c6c3da304a68122503f5ce4c4101d0b3c538a30f14bc33364e5ff06de46d6bdd42f55cc8da14c5fa483aef57c16ba2fc131c201495600c1eb45bfb33106e9fa482479b1f8ae4e22304fccb5052164e6bbd198bf80a3455a9c6fd3e3ec3e23ba8f9b8be6ca82f39f3a71e2cc524e1c37e5f411134eef9e4b67f50c8a3798a1280c22bc201a79a18cb00d61bfcb0e5d1f43785138e11b650417c8705fee8f59b015f36de633c3d880a0e5b99807a6e11c94f606fc22dbe06e4bbf385c82fd3159aa8183f3683c03461197349ef545b3d854338fe24a43d657989159e084ee1235e2d217535a399bba9ae9d455aad3543d92d6ea513455aa50b7790c1d3ba6f1ed773e74ec9946f5e6d1d4d488eb35a368ac516373ad16a5b5d3292e9f4e59cd344a370baed6a5ac52f7f7eb25753a6c10e7e9b9da842a34707651c5d06a1ac1e9cbb10888c72920fe0df8250ec1dd26624e7592f862683642801f8877d07bc4270d62ddc6f16caa9a476cfc3c16180e6194ea1f1831bc2fcb96cda4ae6e0235d523a86f18cee69a0f68ac1e4c73cd705a1ad4f8e64f3ea2d14ae5e6b7be34b78ca1a17a186dd5aa34570e170a8ca4a16a0c2d42e9da12556a2bc78aefd5d9d53281ad9b47b0b94c95b24de3c8cc1a852c6220ae2e8330b6168aa46560e4a9c44b9ef106bcbd7f42f7eb96d32558c222d331d83b8dc2c57b3032c5181292a6e3e2319e495ac3193b69281f7d341efdb9a3d850a84f65d568ea1ac652250037d48da4be6684d885a15c3e6240cff722653e4ae1c9bd282e9f594493b07c7df51836bf06573fec77058e756873a0792acde5eab457ab70e9c00c4e6e194de766a164990a456bc7a0900dc0cdf503165b6a1199b1067d874822d38bde8097c4e475eb5905e31d19c3f4396a68690d4473ca3b7c346308fafaea24a72a18a9320a43e305ecdbd78e95890e7e9eaac21da651533b56b8c1686a05b8cd951aecaa99ca9d4e6b7e3a2fa7e7bb953cba1cc6ede376ec699a4d6d9506d50daa946f1e4a6df550f637a9736a9b265bcb86b04f5c3fb55583735bd5b8b44f9b5dc2055bcab450ca3ec45d8037b49c2ac0e78a2215c4aaa2fa37e0e3d24bba3f5ae241a07225b3e7eb63b0703e2aa3463272c450468be3f48f66f2fe8041b87b7922970773604f1b8eb60b51c64f15eea22dacaa4e75b5a650c2949d35819cab0ee1bb339562003fcdd543ab38dd22635b6b18f575666caed6a2ae4a9dfa0a35da378ea4bb4970f3183eedd4e168cb383a4a47d0d93481a6e21194e5a823f7791f67bb0fc41cab4764663e3ae65ee899bbbdf9693cb7b8a16396913beeb2158c99a083da9889f47deb7ddefac3dbe82f98cb2c9d19bcfdd63b0c1e3c9479f374d97fa0834347b662663e85d5991a34554d1501694c737b0adb3b9a68a92da56bcf3e2efdf13cb7bffe9cbdbb5b69dedb4643fb72015adcb7690a65ab46b363fd7891a12670aa4193aef24974d54ca23e5fb861e1780a570c2729bc3f8ac06102fc0896ba2f25282517430729b69ee16fda03072f99e682a5be027c1653742d78ebedf7784b80eddbb72fa3460f4745753883067c40ff7efde9fb8777e83fe03da2e303d19b3b86fcdc1954971ad2d41447cbb6262aeaf7e0abccc4c82d1cfff014fe74f307ce7df615cdbbcfd0b6ab43583f9cba927994ad1ccb81aae99c6c9acc895a1d3a568fa7ab589396d5da6c5aa1295a6655522306132f1b879b9326767ebe782a73b0f15592baaae40df8d5859b356d3ca3b0f44dc0d03e90b7de7997b7fbbdcbb061c3e8f7763ffaf5ed8fc1fc45f87a7a3064f0874cd01c88b3870acb33a75052a147557d205dc7dbb876eb1685e5ad2c768bc2c0250663e74892328bf9ecea6dcaea3b5166e453509223e224889a325df6366ad2bd458bc3d55304f0719caad1e450890e7b8af4284d51272d6c28011ea330b3d012a3603a964149042832a96edcfd067c7dcbc1814b3d143be6db87e1139dc9acb90bb0b276242a3292feeff5e79dbeef337ce808e6e8e8306ce8200c0d35a8a834a6bc7c361b2a9ca86e5fcbc5affec48ece131c3c7515c7c01416b9c70a8ec1c1378970c55aacdca3595bd94e44523a29591236559ad054a6c6a1fa497cb273269fee99ca17bb67d355328b43e58694264c42e6f401aef6a3d0379a4eecea72163885119a98b3a3657bd79b61e435c9530ab33f32f1c22f2e17efd078d257e4929db9924913c7d1bf7f7f060e1c487a7a2a7af3b4993af56d7257cf1505cc04175f334c5d3db17097e1139a89835f82b0ce2a2a771e212c7d0326ceb118db2a710fcb6643c32ef22a9b7108f42248b198aa4dfab4954e1099469dae56558e344ea62a652c9b964d23c1431589fd086c6d34b0f0722320a59079d6beacab68fcf763e06b2adadcae10c1f0dcdc7719fec2fa0192487cbd7c99364d1b63332334d4d59836550d0f9f2924a5cd635dd152821526c8e393a9dcba1f133739666e7118b944218dcfe6e467d7088e5b8932b30a6b9f144c3d142c760ac6232c95b4824a6c7c5c440537a1b26caec8ff1ad4950fa35514a6b551e350baa9136033124ffb91a2e26b8a096a2546a203700e8879be65dbfebffdd35f9062e51d1df340e15fd9a2a2b9f0e1c06122e74fe1830f073078904859cee3282ad7a7a86601cab445c2151c502e2f4112978d34311723a728013e8665ab37939edf808d9782c67d67b197c6d37ae43431ab2a307288c5232407ffa8152208979292634449f547d4d56b50be5e9d8c702d24d6a3f1b61b8ea3a30ac67673095db9011d2b7f925715fded9f3e5e937fc40a973966beb8c93330750ee0fd0f473068d06054555f57d6fea4674c6163e91cd2b28d71f6b76589f0c100452e1b1bbab0f28e1701aac4c43d4e14bb35988b5d301155dbd2371a1b69343bcf7d4a61fd412c3c123171528ae66a0546eefe38045bb2629da98881a9ac5f3b81e880b1b8980ec1c56e24c64bc7e12193e015b75ac80a117dd0d63dbd50ff9a2aea769a7a4a139fccb791e22f1e50d3d665f0f0a18c18fa1636d6434949d322237b311e8136bf2b67e0a4c03524138ff0acdf2dbed851b0532c4b9ca35922dae9d7df2f120a584b92b00a4ac03a5000f78c11f74663ea26329b4b18c6eeeef8452d257fbd3139e9d308f154c1de62280eb6e345ace8235f99c75c1b091189394f6a5b7699f642fddbb4a6a0b66da6a1bbb0d672ec24b1bc3fa43fb3670ec6cb730cd2306d1cbc17b0c8c68ea5de61d848942c719463e42a67b14b38c602a8a94893163ed162e29163289a3d03b71816380ba5c404b4c43d5470180b9d22c5fd4a168b546aec2e9e73b22524d498b8d03962b61d250c3506339b19f82814d8cb9663e8184ace869aa05e887f9f3655b64f0e90a531d73a181f61fd3946fa58db698ab6540d23cbc9cc5c324f44bd156eb24031bbfa8b4c138ab94f10f6a1e1626b7d719606e119a5c0d05dec8c006af83a653ac5612a8e269efe187948c5ae0845c44e198ae05ee81cce025b0f8c4d0d70b1d6c1dc601496361f89dd594a78d646e63984224b597ba3a072cbfbbd10ff3ed535edec97bba1bad9c03608332f31784446606227842d1dcf149d494cd09dcb6c9385f844b862e76b2f7a0e4fbc14c1b8ca7db0f1b64596e88687dc1373df2061e588dfdd67899d122bcf702409d1d8078819d65986b9505adf31185d3b09b34c9dd1d13760d64c2d6cace6b0c87c1192b46c16784461e3af246b63dd3ffe9f85e6f69de6e939c5f77596b8e12672bebd24102dfd5968e9cc61aaae1ef38d66e2e667285cc812573f0f64494104c6d889d4694656be83c8e18e78c9c47c204dc1559a8147401a3e213ec26fa57804fbe02ef52630c25704b917fab6f6e81a9ba2356b2e33f41732659e0e01b169b847666360ff7a52abb85458d1fc8fbdd6f9372ad850ed298d4845d742649f983558072b099027131dbb0c1f5f13dc3c6760e3aacb9a75f1accc0fc52f7c216d3b122929b71503b6096dfb7670faf32f397df932173ebb44c3b614a212ad89cbf0a175772e89194ef887d8109b2a1569793e1279943054028e91cbf08acdc3c03298f8e4fc479575dbfef9b7e23575ad2a95f5ed67ecbca3986b25c127a500c7a044dc44e172719989b9d5304cadc6131c6146d432337c823f226f9d2db96be71322336463551e95ed256c6acca5b1bd90ec756604cbe6208f3116ca3a2095cfc6dee123bc7cf53030d6c4d255b89b220bbfe5eb45cf1e42686416cd8d3b8b7ae1fcf3d4babd7350464e71b84b600cb36cfcf18a2ec431200233875958d8ab626a298a89af001030013bf791c426cd2136610aeeded3c9cc0d232d2f80905853d6ae0f2669f94c8282b40908984a58a4162e5e622e3552c7cc4e9b190653f0884816094200b70dc03f229dd2aa2de7b66dddf39f7b8d5fd7b25b637dc59673b6a2e39c6321c73b7a8d282cbe983acdc2def5751652c5cd639800330c5bb72178f88fc64b329990e8797849456a75d12042ae873c5c0d69e018bcbc87e1eaf93e0e6ec345f59c26d2e522fc121385d50b5860a310ee97245cafbea7bd7dd7df7f0bf2cf50fbb6ae4115556d6764d1ab986bec855d40ace85f92710870c05400b0731c8ba3fd301c9d8660ed34082b970158380cc0d276086ea24f71771d8587c7201c5d0760e3f621d6eeeaa24d9881833490c0946c2cfce330b096238f2e7c5456d6b4b1a971ebbfe68f13ff46adcd3b552aaa1a4e27a4e560e6128c9ead3fb661d1624088c421c8013bd705d8d98b61dd698a388ec2c9750c760e6370761b8bbd9b0a164eaa98394fc1dc73b1287ede0424a4e112be42546439b65e51e4e456d054bffb68ef72ff7a6a6edba2d6dcbef55c6e511541b12b59601f809e9d270ee111f8286345998fc45f168a47a82b4e41b6a23db6c355e2847ba8c82cd15231238b8c15978e7368120b457e7f9def436332c95d57d1dcdeb6e34c47fbaebf7e65f3afa4edbbf7bc5751df3ab0b276dbeafc7575576293b244719260e0ec2baaa554b4016158f8c9b00b89c1397c198ed244d13dc663e12a5a013b19668e61b8062848cac8635d49250d2ded9d6d1ddb4d76ecdcfd5eef12ff35545fb96d4a557963524959f5dd156b37129db68690983431cc28f1109da467b0687f2571a240a5218bc92129b584dcfc1a2aaaebefb6b66cb9debaa5dd6a4bdbd60f7ac5fdf7d0b68e5dfd5bdb7664d7d4b76597d534669754356797d6b464d7346ec9ae69da925d21cecb6b9ab26b6bdab3b7b5efc9debda3b37fefa3ff43ff0f529f3eff0baaf2db9bed57fd500000000049454e44ae426082	\\xffd8ffe000104a46494600010101006000600000ffe100664578696600004d4d002a000000080004011a0005000000010000003e011b0005000000010000004601280003000000010002000001310002000000100000004e00000000000000600000000100000060000000015061696e742e4e45542076332e333600ffdb0043000201010201010202020202020202030503030303030604040305070607070706070708090b0908080a0807070a0d0a0a0b0c0c0c0c07090e0f0d0c0e0b0c0c0cffdb004301020202030303060303060c0807080c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0cffc0001108006400d003012200021101031101ffc4001f0000010501010101010100000000000000000102030405060708090a0bffc400b5100002010303020403050504040000017d01020300041105122131410613516107227114328191a1082342b1c11552d1f02433627282090a161718191a25262728292a3435363738393a434445464748494a535455565758595a636465666768696a737475767778797a838485868788898a92939495969798999aa2a3a4a5a6a7a8a9aab2b3b4b5b6b7b8b9bac2c3c4c5c6c7c8c9cad2d3d4d5d6d7d8d9dae1e2e3e4e5e6e7e8e9eaf1f2f3f4f5f6f7f8f9faffc4001f0100030101010101010101010000000000000102030405060708090a0bffc400b51100020102040403040705040400010277000102031104052131061241510761711322328108144291a1b1c109233352f0156272d10a162434e125f11718191a262728292a35363738393a434445464748494a535455565758595a636465666768696a737475767778797a82838485868788898a92939495969798999aa2a3a4a5a6a7a8a9aab2b3b4b5b6b7b8b9bac2c3c4c5c6c7c8c9cad2d3d4d5d6d7d8d9dae2e3e4e5e6e7e8e9eaf2f3f4f5f6f7f8f9faffda000c03010002110311003f00fdfca28af97ff6fcfdb553e0c5ac9e13d075082cf5d9ad4dd6a77ec7e5d16d31cb9ffa68c3ee8ebdfd2b5a3465566a11dccea548d38f34b63d0fe377ed79a1fc2bba9b4cb1d9ac6b70ae6589240b0590f59a4e8bf4eb5f29fc46ff00828d4dac6a4f6f1eaba86a931257ecfa40fb3db2f3d3ccfbcdf502be65f005cebdfb5a6b0e96f7379a2f816094979e43fe91aa3e7977279663ee7033eb5f55fc2ef861e11f867a7c70e99a75b248aa03dc4837cd21f52c79fc06057bf472f8c748abb3c7a98d949ddbb23841fb5578a27996e13c2fad3aa8c2b9b9b82f8f66adbf067fc142ee3c31a82c17579aee832e7a5d93776c79e8c1b0ca3dc66bd613c4168b1ed1228f615cdf8efc21e19f881a6496daae9d67791b0eac803afb861c83f4ade5826d6b131fad25b48f6ef831fb6f68be366b4b4d71ed6c26bc216db50865df63767d377f037b377af75470ea1948656190477afc72f8b9e05d73f65abdb8d63c372cdae783a56cea1a5cc7718d33c9fa8ea1c723bf15f5a7fc13e3f6f8b4f130d27c33ac6a9f6ed1f58fdde85a94effbc8651d6ca63fdf5fe12793903d2bcac5e5dcb1e7a7f347a186c75e5c9537ee7db5451457927a4145145001451450014514500145145001451450014514500145145001451450079ffed41f1f34dfd9a3e086bbe30d51bf77a6c07c88b3f35c4e788e31ee5b02bf24744d1b50fda97e245d5a7889e4bc9b57bb1aa788642c7f792b10c96e3fd98d4aae3d7e95ef7ff05c2fda17fb4fe27783fe1d5bcdbacb448e4f12ead1ab712ec1b608d877cbb671ea0564ff00c1387e1936a77566f347e65d5e3fdaae5db92ccc73c9fc49fc6bd3d686154a3f14ff0025fe6ce07fbeaee2fe18fe6ffc91f5c7c02fd94bc1fe18f055bf9da4d9dbd959c5c2962891a8fc6b1fc61f173e10e85e29b7d174dd2edafef24d423b17725a3b7526611be1c9c315ce7e5cf6ad3ff828578abc69f093f66f8efbc076d7335f5a5e462e56de213318b90418cfde563804007835f057ed53f1ff00c061be13f8ab4616dfda1a8ac7a8f89acf4e2556caf1447e6c0b0fdd8c870c76e39cd7cb66bc435f0adc29cda6acdddbd5376d3d3a9fb2787fe1a61f3b8c31388873c66e718a8d9f2ca11e6f7d5b452da3aeacfd15f8e2ff0009fe03e84f75ac59d87dab693058c729371707b00b9c81ee78ae67f669d7bc1bf1d756beb1bcf0adb68f7d6d1a491db397dd229505db39c6016518ebf9d7c1bf0dbf6b7f0a3ea5abeb7aa5adcf89bc6b7f24ba84373ab798b65a765cf970b73bb76390c32aa4ae3a123d6bc27ff0501f0ee99e0df123f82352d52d7c6b7125bc2f757ba634f63b637fdfc569b3cccb1dc582b637704f35cb4f8aab4ea29bab68eba5dddfcf4d7cba1efe33c18585c1cb0d0c34a759b8af68e368a6dabd924da82d6f26aef7492d4fb63e217ecafe0fd6342b858b46b6f990865c92181fc6bf327f688f81d6bfb317c419ed74b8db4cf0eebd700e50902c2eb3fba994e7e5dad819f43ed5fa3dfb3dea7f14fe20c9e1cd7bc54fa7697e1fbad0a2967d3d61db7925e3a82c64078450390a0f538af17ff829efc15835ff00075db342ad1cd1b738ef835f4d85cc6ac9aab77f3ea7e239ae4d0c354785728c9addc1dd277daf65aab743d9ff00e09fbfb519fda6be07412ea5b63f14f875ce99ad443bcc9c0907b3a80df89af75afc81ff00824efed1137c31fdae74bd2f50b8916d7c73652e957aa5be57d42d7eeb9edb9e21b89ee6bf523e237ed2bf0f3e0febd6ba5f8afc71e13f0dea57b134f6f6ba96ab0dacd346a09665576048001391e86b7c7d154eafb9f0b49af4679f83acea52bcb75a3f5476d4560f85fe29f867c6f650dc68de22d0f56b7b84124525a5f4532c8a7a10558e456ef98a1776e1b7d735c4750b4542751b71711c3e7c3e7480944de37381d703a9a9a800a28a2800a28a2800a28a2800af88ff00e0afff00f0594d2ffe096fa8fc37d017c2979e2af13fc4ed496cec544e21b5b185668924964382cc7f79f2a28e48e481d7edcafc6fff0083ab3c351ea9f1a7f639ba9add5615f1d4b6d25dcb1978220ef68763aafcc73b49007f74d007ec569d73f6dd3e0988c79d1abe3d323353543a7284d3e055c1511a8040c03c54d400514518f9b3f875a00fe777fe0adbf1ff00519ff69bf8a9ad5adcedba5f10597866d24001f2d220f348403c7de400fd6a8fecadfb7e7c4ef05a79da6f89e4b661d08b788e31c775af10ff008290ebb35efc4bf12cc777fa57c44d7277cf7d92045fcb9fceb13e026b0c9e1f0dcf23d2bf6ee13c930d899c1568292496e93e8bb9f8cf1867d5f08a6e94dc5b7d1b5f91f7f4bff0562f8d81198f8dae1b8e7fd120e7ff001caf933c4df13ae7e2e78e269824f6d73aa6a325e5c4f1c1b53cd3d4a2a8014b1ea3d6a97f6fc9eff5c57d55ff0004cffd9f2c3e337c408eeb584dd636e4b14e9bb07803ea7afb0af03c6ecb729ca3258e69898c234e124b914237a92935cb1e7ba705652e692bb4aed6b647e91f46bf10332866f8ac261f9e55674dcbdabab251a308a9734dd26a51ab2e69439232b2e6493766daf9c35ff0178bf5d60aba4f88af22490037937cebb7be13ad6a7c2ffda8bc61fb2678c9adfc27e21d6b40bc5963fb5a3209209c1eade5b29008f5ebef5fbbfa17c0ff0009e9de1f4b38f45d316154dbb4c0a7fa57e78ffc1587f64dd27c0363ff000936836f1da95399513d3b8fa7715fcadc2fc4982c667347058a54a92c4548c6338a6fd9ddb518b4e51ba72714e7cca4badd368feafcdfc58955caaaa842a575428cdba72a914ab4924e536dd39a4d454daa7cae9ddfbaa32499e7b6ff00f0564f8d7242acbe38b86561907ecb0723fef8ae27e3bffc149fe2ef8e7c23243a878ba6b98c29041b6847f24af9e0ebb227f7bf2ac8f19eb4f2e813fdee95fe8456e13cbfd8b4e842f6dd452d7faf33fcc7a7c658cfac5e15a7cade89c9bd2fd764fee4747fb297ed05ab1f18cfaf5f5e34ba87853c57a6ead1cb80a4452b79320e38c7ddfcebf687e22ffc110ff66afdaf3e245f7c52f88be07baf1978a3c5d1417334fa96b3772436ca225511c11ac81638fbed03a926bf9f1fd9db56912efe23296c0fec2173f4315d42c3f9d7f54ffb375f1d53f67df045cb36e6b8d0ece427d730a1afe7ee2ac34694e2a3b2bafc9fea7eff00c318a956a2e52eb67fd7dc7e3f7fc1783fe090df027fe09e5ff04e1f157c4ff83fe13f117847c5ba2de59c16d75a778af528e3b559ee1119cc46628c17770300026bdbbf608ff8246d9fed41ff0004eaf85fe22f157c72fda46cf59f1a68167aeea31d978d7640b7134418ec4685885e73b4b1ebd6bdbbfe0e2cd2a0d57fe08dbf1b45c5bc770b6fa4c73a8762bb19678c8618ee0f2057a6ff00c1380eff00f8257fc1f3a3ead35d337c3dd3cdb6a1228de5fec4b8723730c86edb8f4af923ea0c5fd887fe0927e02fd88bc69378a2d7c51f123c7de2a68a4b5b7d53c5de2093507b0b77c6e8a18c6d89738e5b6963d338e2bea7afc27ff821c782ff006a8fdbf7c17f1fe3d4bf686d7bc2fe135f1d5c6977bac403eddaecb771c31ef8ad5e5cc76b0089e23f20cee3c018cd7d71f0affe094ba7ff00c1313e33eb1f1f7c51fb507c6bd73e1ef8474eb8d5350d17c45aec9756f34a10a979896c4ca1090a857716239ed401fa41457e16da7fc16f7c31ff00051cf1b6ad75e31f8c5f14be09f80acefe4b7d27c31e05f0e5fcba9ea968bf76e6f7508a17f2cc98384848dbc02d9af23d5ffe0a7cdff04c9fdadbc23ab7c04f8a1f183e347c29f13dcb378afc1de2fd37529eeb4cc942d3413dc4618bb06638ce43260820e4007f45d457e2effc159bf686f891fb17ff00c1617f667f8f371e30f14c9f00fc602df4f9b4e12bad8e9ed71198e55788614968e55932d96051b1d2bf662e359b5b7d1a4d41a78859c709b833161e5f961776ecf4c639cfa5005aa2bf333fe09b7fb4ec3f063f649f8f1fb5e7c64f14eaada1f8e3c4f7fa86950dc5dc93c369a45b4af0595bdac6c700cb8e0281b8b0ed5f2b784ffe0b1be16ff828bdecfe22f8a3f1b3e2e7c25f0e99e5167e03f879e1ed49248ed949f2daf3528a02ef2b801b6c2c14640c9e4000fddcafcb1ff8397344d53c55e2bfd91749b4f2dac6fbe2b5aacf1b8015a40a0a6588e38dfdf9cd7c67f093fe0a997dff0004defdbf7c27a07c2bf895f14be387c01f185c243ad691e29d2afe5d43c30f2cc15e48a69e30d205dfbf83d158119009fae7fe0e7af88d79e15d4ff649fecdbeb8b49e6f8a16f79198c1da7622a863db23cce01f53401eedff0005c0fdb2ff0068cfd8cbe13f806f3f67af876be38d4b59d6d2cb5673a64ba97d922c0da9e4c6411e637cbbf3f2e3d4e6bed3f87babea5aff0080b45bed62c7fb3356bcb1867bdb4ce7ecb3322978ff00e02c48fc2bf3a3fe0e8bf8c9f117e0bffc135f4dd5be1ff8b75af09cda87886cf4dd5ee74d97c8965b49d1d594caa43c6376394e4e6b5ffe0b69e2cf16fc16ff00820bea7ab7843c71ae78775cd1f42d1d5b56b5b966bbbd8dbc94910cc4ef0640c72e0eea00fd18a2bf207f64ff00f82557ed05fb7e7ec97f0bfc49f15bf6a0f1ff0082fc377de11d3934ef0ef826f658247b436f1b4535ddd4843cb70ff2b3e4103a024578efc5ef825f1bbfe086ff00f0534fd9e349f08fc76f891f137e1afc62d6d34abed2bc53a93ddb2ca244495595894e5240eac814e54839e3201f2efed816eb0fc58f164778b095b6f1debc8de60e1099c37f235d17c19f8e1e05f86ff0aaf2f6e96cf56b9b3b596e0d9e9f07daee19631b9998203b107777c2ae7922b9eff82c5d85e7c28f8c3f1a9a1b48ae4e8fe2d8b5ef2658d58496d721d1f1b8103e76439c76afcecf85ff001eef74af87de3ad26eaf75ab7d5bc5ba7c3a669d259c82de1b847b9469a09d5405789d4739e4155038cd7cff001bf06bcdb1d1c44f133a709460ed06d5ecacf5be97f43972b9c234a5094136a4f757eb73f4d3e10fc5bf1cfc56fda1344875bf08dbf80fc1b756b732d94775668d1f894a1d8fe4cee9fbc31b143fba38c1249618afabb41be9bc2af9d2e4934f27fe7dcf97fcabe07fd993e3a789349d1fc47f057e257c32d63fe125f0bcd2eabe1ff10f86a4133691725218e08510068beceed6e72fb88cb3838c935fa29fb34fc2cf117c4cd02c6e353d34dc6a367a625cea90da2168c4cb106942ff00b21b763b9e2bf0af10b82b158bceb09956515538d456e4726dc2dab9ceedbb59df99ebd1743e830188a58784ab35cbdda49369f4f3f4d8fa4be127c329f5ff00d9df52b8d4bc51a847e24d497ceb3737f262cc2f2a87e6c7cddfea3d2be5ff0016eb1ab6aed3d8eb179757de531478ee65322e471df22baafd91bf6e6f05fc53f1feabf05ef74f9745b4d7ae0a691aecd75279d25e8dab142632bfbb466dc396efdab2be33e956167ab5f58e93a969379ace9aa52eed60bc498c6c091962a4f7e0fe15e467de07f10e53829e3e55612f64949a8b95ed77792ba5a4559b7a3df4d0f52a67193d46a18194ef2935ef2495add6cdd9b7756d55ba9c047e14b183fe3e34fd35565c18a510aed23807231c6d3907e959bf167c0f6ba2f87f5087fb32c6410a11e6c712ed738278e3d066b6346d42eb45d1b4fdd689717cf32a5ca96dd1aac8bb58023d37671cf207a579e7ed0de3ad6b45f0bc9148ab15bce259d18c876800846cfd39007a115f2786799d5c4a50c44ad7ff009f8ecedd57bd7b34afd9dfc8f66a641818c39bd92f925a6df99f2e787ec6dd66f1ccd0c11c7bb42960f9142f325c42a3fad7f4e5fb2edb359fecdbe0285b3ba2f0fd921cfa881057f34ff07fc37fdbcd776706666f116b9a6e8d09fe29079be6c9fc94d7f4e7f096c3fb2be17f87ad7fe7df4e823fc900afecec8613a7c3d838556dc9a94b5bdecde9bf91f98c609636bb8ec9a5f723e46ff838b7c476fe19ff008237fc6b92e2149d6e74a8ed515f38df24f1aa9e3d09cd7a77fc12cf435f0fff00c12e3e0958ab595e083c07a7266c6659219bfd1549d8ebc1ce7a8ef9acff00f82c0fec6d7dfb7c7ec0fe30f863a7eb96fe1d9f5c92d2437d3406758922b8491be4046490a40e7a9af44fd8f3f66c87f65afd8e3c0bf0b6cf51babf8bc23e1f87478ef6e40f365d91eddec060753d07d2ba8eb3f3f7fe0d64f15cda9f847f6a4d2574f1a4e9ba67c5bbc9adac8b79925b34b0a075690fccff00ea97939c5767ff00075c5a788e4ff82466b975a0ee6b6d37c45a65deb108242dc59ac8db91f1c94f30c64804702bd87fe08f3ff04e4b5ff8277782be265943e2cd47c5575e38f15cbadde3dc5aa5bc56f315c1112a927690472cc4f03a57d3bf1b3c27e1ef1e7c28d7b45f166936bae787756b47b5bfd3ee103c7771b8c1420fae6803c6bfe0977f177e1dfed43fb0d7c36f1bf8274df0e4367a96856d1dcdbd8db46bf60b948c24d6ec3190c9206539f4cf39cd7b778aad3c2fe11d12f35cd66df43d3f4fd2e17bababdba8a38e3b58d06e676761850002493e95f989fb1b7fc1167c49fb3afc58f1578bbf667fda035cf85de15bfd52582ffc23a968e9ae69f0ce8df3228778f0141c0ce5bfdac57a9f8e7fe0987e3bfdaf1f56f0ff00c5cfda73c5df11b44d1ef106b3e12d0f4c87c33a7dd865de209de23248ea5483b77007bd00769ff057bfd927c27ff055bff825eead1f87b514d5a382c57c63e0ed4f4c97747713c3048d032100ee8e4476180390c31c815f297ec97ff0571befdabffe08cfe0df05f87f50b493e3f78aaf62f84f2585c0f326b5b86cc725ebc67e628b621a6248c06041fba6bf59fc11e12d2fc05f0f749d0b4bd3e1d2f46d1f4f86c6d6c51408ed608e308910038c2a803f0afce9fd8dff00e08e7f0ebf628fdbd3e257ed3535d4c9a1ead737575a35834616dbc3ab2a833ca7a9662de6056180aad8e7ad0078f7fc1cbdf05e4fd90ffe0935f02f41f09daade780fe15f8c3488b54b19c9f2efe18209563330520b2b4992c01eae0f6e3f4eff00642f1d7c3ff8f9fb39783bc6de05b4f0ec9e1ff126936f776cfa7dbc6b1a0283319da3828d952a790548357bf68ef027827e3f7ecf9aa685e32d16d7c55e11f135a2c72d8ceb95ba4900284775238208e4119afcfafd8a7fe08d3f10bf631f887e2ab2f807fb4aeade07f0bb5c24f75e0dd6b418f5fb7d35e54deb86692303e5208c0ce00c93401fa47f10f54f05fc26f08ea5e2af133787b41d174581aeaf752bd48a186d62519676761c0afca6ff0083a43e3968fe16d13f647f1441347ad68adf1020d6a389255fb2dfdbac71b2bf4248dae08238c31f515f47fc43ff00823ff8d3f68a9f4e7f8e9f1f3c65f17341d3ef16f65f09da58c1e1ed1ef8a3065595222ef2a8c7dd670a7d2a97fc1673fe092763ff000521d6fe06ade788a6f0bf85fe1cea13c971a7595a879af56416fe5c519ced8c288581386e1b81401c87fc1d23a9e8bacffc11f2f6e2fafbec2d7dad6932e9a163deb3cacfb827fb2366e39f6ad3ff0082f25e0b3ff837d7c54f1adadc0ff847347556789654e5adc6e50c08cf707a8ea2bd0ffe0b13fb0a68bfb797c03f87df086fbc4d3681a75aebb69ab4f6f041e65d6a16b68851a3472711b7ce06f20e33d0d765ff000527fd82edbf6bcff826f6a5f05ac35c93c2fa64b6da7db8bc683ed92c76f6d246c5002cb972a98dc4f04e706803bcff00826b3dd49ff04f2f81ad78cb25cffc207a2ef652a54ffa0c38c6de3a63a57e7dff00c1c83afc5e18fdb47f61dbebab766b3b7f8840c934885a042d2dbaaab631f3127239ed5fa5dfb257c19b7fd9dbf661f007812cefaef54b5f08e8367a543777417ce9d228950336dc0ce076af997fe0aabff04cb87f6f9f8f7f017c41ab78b352d2743f861ae1d58e93676eacda9dc896092263231c22af94411b49218f22803e23ff0082e5fc098fe1efed95a76bf71636f3687e3bb47d22e9658c3c4f303e743bc1c83921873fddafca6fdb57c43f0fbe266b763a3dd69fa868b2f820b5aac3a794daea7695da002a11c285c00191cf39afe93bfe0b31fb239fda67f661be92c62ff89c68e05d5acaa3e68a54f9a36fa6783ec6bf9e0f8b9f0621f19fc40d37c53710ad96c98aeb76d80186a103ae51811f2ab14dc79e7a718aecccb1708654f172d5d14ee96ada7b7e3a791e7d38ba78be4e9536f55bfe07bcfece9f0c9be12ac9f17b56b7d37c2efabe90b6e340d3c328906d8c42b31dc55dc6c2d9550732be49af59f117ed23f10fe07fc3bd0758f0dfc46d7eeb4fd6a67b9bab5b70d65630dd804a4709e1e758b237903cb2c00f9abe69d1be373fc76f1043a2c97461d2f4b89d86deaec072d8e879c01ed5b3aadbf883c69a0e96f797b2de5a69d20d22c6177ff8f75c93b557b0c939ef9af99e00ca21849d4cdb3b947eb95ed74d24a14ddfd9d35a6f657975dafb22734c54ea354a827c91dbcde9767baf8b7c35e07f147c31f08b49a93685f156eec2ff00c59e23d7aeb542c5d76335adb85e3f7d361582afccbe6739e81758b4f197c13f01f847c17e2ed2b45f0eff006c69ebe20f0b6afa7dbc33ea13cb360aa4d702418490101d242428dbf2f7af9d3c69e1e93c2de2296d2491a6318521d8105863dfd3a7e1573c59aff88bc5ba3dbea1abdedf5f5a3ceeb0b4f216557daa0ed1d8615471c7cbed5fa8539d0ad4613534e1536bf5baba4afe57d3b6878ae4e326ad66bfad7fccfadfe09fc63baf17f84a7cc91daeb5625adef62c2b08a4524161db9c751c7d6be65fda73e306a1e2af14dd68f35c1fb2d9cb249e529f92357219940e982ca0d71de1af8e737c11d7bed4aede5480c57509fe25ff0011d4567f84e78fc7fe32d4b5ed5976e956e7edb76c1b225881caa67d5db0b8f4cd7f32e2fc29965bc4952960229d1c459d27fc8dbf7977b415dafeedbccfd0301c4d3fecc9fb596b156b77ed6f5fccfa8bfe0979f026e3e2c7ed63f0f745fb3b490f876293c43a82e3849e71b6056f711fcded8afe852cedc5a5a4512fdd8d020fc062bf3a7fe0833fb2a5e7843c17a9fc4cf105b343adf8b1cdcec75c7908c311a0f4db1f6ec5abf46abf70c6fb38ca387a3f0534a0bd22ac7cfe069ca34f9a7f149ddfcc8af8c2b672b5c2ab42aa59c32ee181c9e2bccb49fdae3c1d75a3e97a808f5eb3d0b546486d754b8d22786c72c76a03215c2827001385f7af5095165899586e5604107b8af9cbf66ef027893e2b7ecb5e19d075a6d12dfc2d776518692d8bbde4f0ab9654c30d884ed00b0ce07400f238cec3d66d7e37785edfc157de20864923d2ed7533a6ceeb6c559ae3ce584fcb8c91bd80cfe359be33fda6bc07a1f89cf87f52d49669bed296973b2dda682d656236248c01009247ae32338ae46ebf67bf1841e10d53c316175e1c8747b8d7c6b105cc8256b831fda96731320c286f971b813f4ad6f0afc0ed7bc0b73aa59d9c3e13d4ac6f2fe5beb6bfd42d59af2dc4ae5d91c018936b13b4ee5e300f4a00e57e0d7c71d07e110f1faeb4baa5ad9c7e32d49e6bc8f4b9e4b4b542e36979110a81d39e40ef8ae93c01e3ed13c1ff00127e266a9757d6eb6b7da9d80b7f2034af74cf65114540a09766ec1734fb6f829e2a6f0e78b3c3f7171a0ff66f8b6feeee26bc40e66b686e0e19163236960bc062719e707a5655ff00ecafac689e286d5f40bfd3a33a5ead697fa6595c86f29e286c85ab472b0190c47cc18038205007a6f84be2ee8fe35f125f682a97da7eb367089e5b1beb730ccd0b1c0917aab293c641383c1c1ae1bf69ef8b5a0e8bf08fc61a0adaea7a83c7a5dc5b5c7d874d96e2dec99a13b44ae8a553a83ec0e4e2baaf087c3cd4e5f8993f8bb5d6b18af8e9cba6dbdb5992e9147bf7bb33b00598b63a00001deb8f9fe0b78df4ff0009f8bbc2f677ba0dd68fe2196f24b5beb932adddb0b92c59645518936963820af000ed401a1aa7ed07e0bf859e0bd174dd76e84d7169a65acb736f0db9b86b38fcb50249001f28ebee40380706a4f8297d69a9fc72f89375672c734374da6cc8c9d1d5ad4156fc462b33c21f003c41e04d5aee68d7c2de208f56b6b6fb49d46dd8496b711c2913ec600ee89b6ee0a7041cf3cd75ff000bfe19ea9e0ef1cf89b56d42f2c6e175b4b34892da13108cc30ec638c9c027a01d0014018971fb62f84535482cededfc4d7f35cdc4f6711b5d16e24592784912441b6e0b0da4f1918ef5a6bf1ffc29e25d0745beb4fb6ead26ad0fdb6cecaded19eeb606da5d90e3cbdac0a9dc47231cf4acdf0ffc06d434ab8d06492eac77691af5feaec1108de938942a8f71e60cfd2b9ff863fb3b78a7e0c6b36b7da65d68bab9b9d396cefe1ba2f0889d259644789806f94f9a415206700d0053f1b7c4df0cf8ff00e25f86bc456fa83c567a7691ad24cef6ae66b196148da40d115c874dbbb6919381d735d437ed37e13d1f417b37ff008497c41fd99a7c1777b245a44d72c90c91ef59652abb465464ff002ac59bf659d5bedb35e2ea9a7b5d6a50eb126a198488dee2f6248d420ed1a045073c9c13df15d1782be035e785b46f155abde59bb6bda45ae9b118e32bb0c36ad0966fa96cf1d85006b4ff001ebc37a758daa587dbb5466b18af85bd85b34d2416ce8191dc71b72bc853f31ec0d745e0cf17e93f12bc3563ade933c57d6374a5e09b6608e704608cab020820e082315e5bf0e7e0778b3e0fea377fd993e83abdbea963651ce6f0bc4f6d716f6c90129b41dd1b6c0db4e0824f35e89f083e1d2fc2bf0059e8cb3fdaa485a49a6942ed579647691ca8ecbb98e0761401bdaae990eb5a6cf697082482e50c7229ee08c1afc3eff82bcffc13f2ebf67df8abac78bf4ed2df54f07f8870dad59c2a7732ff000dca63f897f8b1d87d6bf726b93f8c5f07747f8d7e0eb9d1f58b68e68e642a8e57263247f2f6ae9c2e2a5427ceb55b35d1aea8c311878d68724be4fb33f97ff1ae99a1f83b4ab5baf0bf87f4fb46b9460b7968f21178871c02c4ed61dc7506bda3e19fc68f06cfe24d363bcf0e69f1e9f0e98e6488bbac7f6dc2e2e3ef7dfddce3a6474ed5f427edd1ff000487f13fc04f10ea1a97832c63d4341be732cfa5caa4da4c7aee898731bf3dbf4af877c55e008b45bd92cf508efbc3775bb06d75885963241fe0980c30cf435f27c53c3598632b3c66512e78cb570bda71f7546caed2696ad59a7ab32c3e261457b3c5ab7695ae9eb7d74d0fb0be0cfc59f82b1fc3ab4bbf1cfc3dd17c4babdf6a6f6e9777b713a48b1b39d8084914051f4ef5e7ff0010be27fc3bd2be2c6b023f0868e9e195b2912c34e064f26da6dcfb5d5b7ef2c3208c922be721e12ba6b8591752d1e4dbf764fed48f6af7e016c8fcaade9fe168752bf5864d426d62ee4385b4d1e17bc99ffe058dabf53915e04729e2ac4d3a383a542508d2774e4f952f9deeeddfcec9234fac6060dcdc949be895dfe4466f74cf8a7e23d26297c236b79b5ae1b5192e24741228244724cc1be55518e463207735f4a7fc13cbf61c3fb55fc53d374dd1b4b5b4f0068d7a2ea56da426ad72a7ef0dc49f25074c9feb5bbfb1dffc12f7c69fb4a6ab676b7fa4cde1ef0a48e1e5b246267bb03f8ae66f4ff641c7618afda5fd96ff00659f0ffecbfe01b6d2748b5852758c2c92a2e3000036afa015fa5e5d4ffb3307f57f69ed2abde4af68ab25cb1beba2495f4391519622a2a9523cb05b2eafcdff0091db7c3df03597c37f07d8e8f611aa5bd9c6101031b8f73f8d6d5145731e981e45733f06fe1dff00c2a5f85da2786fed6d7dfd8f6c2dfed0c9b0cb824e71938eb5d35140051451400514514005145140051451400514514005145140051451400514514015f55d22d75cb192d6f2de2b9b79061a3917729af98ff6a8fd893e1deada14f79268abb9882630418d8923b107d68a28db5407c837ff00b07fc2f3aa337fc23365bb775f2a3ffe26be96fd94ff00618f86f15aacc9a2ac7b5b1b136a236003c80a28a2b4a956724949b7f3338d38a7748faebc31e11d37c19a6259e97656f656f18c048971f9fad69514566681451450014514500145145001451450014514500145145001451450014514500145145007ffd9	\\xffd8ffe000104a46494600010101006000600000ffec00114475636b79000100040000003c0000ffdb0043000201010201010202020202020202030503030303030604040305070607070706070708090b0908080a0807070a0d0a0a0b0c0c0c0c07090e0f0d0c0e0b0c0c0cffdb004301020202030303060303060c0807080c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0cffc0001108001e004a03012200021101031101ffc4001f0000010501010101010100000000000000000102030405060708090a0bffc400b5100002010303020403050504040000017d01020300041105122131410613516107227114328191a1082342b1c11552d1f02433627282090a161718191a25262728292a3435363738393a434445464748494a535455565758595a636465666768696a737475767778797a838485868788898a92939495969798999aa2a3a4a5a6a7a8a9aab2b3b4b5b6b7b8b9bac2c3c4c5c6c7c8c9cad2d3d4d5d6d7d8d9dae1e2e3e4e5e6e7e8e9eaf1f2f3f4f5f6f7f8f9faffc4001f0100030101010101010101010000000000000102030405060708090a0bffc400b51100020102040403040705040400010277000102031104052131061241510761711322328108144291a1b1c109233352f0156272d10a162434e125f11718191a262728292a35363738393a434445464748494a535455565758595a636465666768696a737475767778797a82838485868788898a92939495969798999aa2a3a4a5a6a7a8a9aab2b3b4b5b6b7b8b9bac2c3c4c5c6c7c8c9cad2d3d4d5d6d7d8d9dae2e3e4e5e6e7e8e9eaf2f3f4f5f6f7f8f9faffda000c03010002110311003f00fd38fdbbff006cff0088d6df15acfe09fecfda358eb7f142eed9750d6b56bf68bfb3bc2564cacc19c4924624b9755cac4096559226650922bafce3a2fc1efda36e354d32f3c3dfb4af8fb58f1e6a465b9b4b2bbd43c3977e1bd49e3dc64892c61996e561565719fb63b00bc972083f255a7edddf133c37fb457ed11756fa4e9b75a7f8b3c4977a2dddf5f5b3090c6ede425b4772248dd3901515181dcd85e48aee74df851f143e157c10b5f89d6bf1b34dd7757d73549352d5be1f7913c0d7934b3acf7319314e37cc865467851576064e400a2b4ce33ac3e4af0f46a54a0fdbcb955ea2e652bc535349fbab5fb5a2ddf9f4f0de0729ce29e2ea637135284e824d2f66f9669a935c8eceefddd5697d127afbbfa5dfb027ed9de2af8bdaa6b5f0cfe2f787edbc1bf1b3c15179faae9f6d224b67ab59170b1dfdabc6ee85087877a86ca348bb96324c69078a3f6edf895f0d3e2af82bc17e26f81eebaf7c46b8d4e2f0fc5a378c2daf2329636a6e9cdd492c50ac2cd18c00a5c6e20671961f9ddfb3b7edb5f133e207fc1567e09ea5e2fd06c74386f20974026cf4a3a7acb1485228d5b04ee08aec154f1c83fc22bf4ebf68cf845e23f1c7ed9ffb3bf8934cd3daebc3de0db9f10cbaf4eb7eb07911dce94d6f0ab4641370af2b28f2c63055642dfbbdadd998e1e14aada9ce134f5bd3929c2fe525a7f96c7899563a38aa1ed22a495edefae597cd3fe9ee75df087f6acf0bfc45f0ea36a5adf83f45f1243a736afa86891789ecb509f4cb30c479f2bc2e57cbc004c83e419c6e35d5f857e32784fc7306952e8be26f0f6b116b8b3369cf65a94370b7e20204c622ac7ccf2c901f6e7692338afcf8d3ff00e09fde22f85fff0004ecfd967c3b71e0fd363bef033e936be3df0c78535a7f0b6a1e23261291f91a9d93c2c6e20d41adef76c932477134196915f638e8aeff0064ad4bc39e1d6f88df06fe175fe8fe3bf01f8fa3d7f448fc4bf10ae356d53c6f23a1d2f5cb0b9bab87bb3650cb6f122f13481e6d3eda49231f678f3e79e91f70789be38f837c15a1b6a9acf8b3c33a4e98b78fa79bbbcd520820fb4c6e63921deec17cc5756565ce432904022be6abef8d47e017fc148be306a5e20bef166a9e19bef09fc3ab0b0b2b4867bdb7d2ee752d5f5eb0f356253b2388bc51bcb36070b8cb30553e3cbfb0cdc69bf196cedfc41a0f8c7e2a78275cf0185b2ff8433c79a878524b2d4a5bbb8b8d70cb611de5a457106a1717c9334cf23c819447226d0af517893f636f1d43e26b9d3e0f00e93e10d16e6c3e10e8ba0699378b9f58fb247e1df155e6af796eb7138decb0e9dbc92c39789950c80ee600fbc5bf68df87f1f8e2d7c32de3af06af892f6692dadf4a3ad5b7dba7963731c91a43bf7b32bab29503218104641aed03035f03c3fb016a707c009907823c2f1f8dae3f6965f1fbdfadb5a2de3e96bf10bfb4d6e5ee000e641a6e40058c9b488ba7ca3eeec7a6efca803f99bff828afecf37ffb25ff00c15abc48d7babea1a2e9f7ba95debf6ad3adb5d5b5c4575bc5b45696f711b2b4d3c72de43e6bfcb1b8924521ed5037a76adff050e961d2af3c39336ed2ece58aeb4ed264b50191d5dade5bcb79d5967dd73889dd6e9582950b1184868e7fd91fdbf3fe09d7e0ff00dbfbc0f6b0ea777a87877c4b69018b4dd6ec0813451310ed6f329e248188f99320e795656c30f8734aff008218fc6bbd67f09de7c76917c2535d4b2cb6897d78d13ef468a490c185cb344cc87f7a1b692bbc8273e6e3f2bc262eacaa6230b0aea71e56a6edca9fc5caf965f13f79c97bd74924ad77cfcd88a6d2a53714b6b75ed7d56db5b63ccbfe099720fdb9bfe0a73e1bf116819baf087c38b56d50dcb49b900558d481b46d0c2e0a44a83a88e73bbf76037ec67c54f075f78cafbc2f1dacaf0dada6a6d3ea2c9318dcdb9b2bb8c6304127ce92123fba406fe1ae2bf62ffd8abc27fb0ffc2d1e1af0cc9a86a17578c936a5ab6a12ac979a94aabb54bb2a8015470aaa0051ee493ebc88acd8f9ba7ad193e4f85cab054f2ec127ece9ab2beeeedb6dedab6fb1d756bd4af5255ab7c52dedb6c97e9f79e6527ecbe9716d671dd789b5fbff00ecf4b3483ed171232afd99e3756281c233bf951ef7605895ca94c9cddd17f67a5d02dae23b7f116b9b646b974f364f30afda2e56e24573c798bb832f3f362597e6cbe47a1c0abe671bbf1353919af4883cc7c29fb3559f865db3aceb3781acdec7f7b70fc44d242e1546edb185108402354cab1ddbdbe6aa56dfb2b5bc5a6d8dab78abc4d3c7a7a388667b9dd3e5ad1ed8e5cf271e6348b8008777392188af5ba280388f097c1a8bc27e22378bab6ab776eb2bcb15acf3968e12d8257afccbb86f0083876241c600edb60a5a2803ffd9	1	Claudia Loyola	San Martín Nº 570	297-4963040	municamarones@gmail.com												2016-11-15 11:46:49.50503	10
\.


--
-- TOC entry 5638 (class 0 OID 5283712)
-- Dependencies: 177
-- Data for Name: muni_oficina; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.muni_oficina (ofi_id, nombre, resp, sec_id, part_id, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5993 (class 0 OID 5286983)
-- Dependencies: 621
-- Data for Name: muni_sec; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.muni_sec (cod, nombre, part_id, part_id2, part_id3, fchmod, usrmod) FROM stdin;
1	Gobierno	0	0	0	2017-12-19 10:42:31.68983	100
2	Hacienda	0	0	0	2017-12-19 10:42:31.68983	100
3	Obras y Servicios Públicos	0	0	0	2017-12-19 10:42:31.68983	100
4	Deportes	0	0	0	2017-12-19 10:42:31.68983	100
5	Cultura y Educación	0	0	0	2017-12-19 10:42:31.68983	100
\.


--
-- TOC entry 5999 (class 0 OID 5287188)
-- Dependencies: 627
-- Data for Name: rpt_audit; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.rpt_audit (cod, niv1, niv2, tipo, nombre, report, padre, fecha, usuario, caja, tcambio, cant, detalle) FROM stdin;
10	1	0		Caja		0	0	0	0	0	0	Auditorías de caja
20	2	0		CtaCte		0	0	0	0	0	0	Auditorías de cuenta corriente
30	3	0		Planes		0	0	0	0	0	0	
40	4	0		DDJJ		0	0	0	0	0	0	
50	5	0		Usuarios		0	0	0	0	0	0	
11	1	1	CajaAnulado	Operaciones Anuladas	AuditCajaAnula	10	1	0	0	0	0	Operaciones y/o tickets anulados por caja
12	1	2	CajaSinMov	Cajas sin movimiento	AuditCajaSinMov	10	1	0	0	0	0	Cajas sin movimiento según fechas
16	1	6	CajaExtConMov	Cajas Externas con Movimientos	AuditCajaExt	10	1	0	1	0	0	Detalle de los días que estuvo operable una caja externa
22	2	2	CCCompensa	Compensaciones	AuditCCCompensa	20	1	0	0	0	0	Detalle de compensaciones realizadas
13	1	3	PagosAnt	Registro de pagos anteriores	AuditPagosAnt	10	1	0	0	0	0	Informe de pagos anteriores registrados en el sistema
14	1	4	CajaConMov	Cajas con movimientos	AuditCajaConMov	10	1	0	0	0	0	Cajas que operaron según fechas
15	1	5	CajaFecha	Fechas que operó una caja	AuditCajaFecha	10	1	0	1	0	0	Detalle de los días que estuvo operable una caja
21	2	1	CCAjuste	Ajustes Manuales	AuditCCAjuste	20	1	0	0	0	0	Detalle de ajustes manuales de cuenta corriente
23	2	3	CCCbioEst	Cambios de Estado	AuditCCCbioEst	20	1	0	0	1	0	Detalle de cambios de estado realizados
24	2	4	ExcepRec	Excepción de Recargo 	AuditExcep	20	1	0	0	0	0	Excepciones de recargo/descuento cargadas.
25	2	5	ExcepMulta	Excepción de Multa	AuditExcep	20	1	0	0	0	0	Excepciones de pago de multa cargadas
31	3	1	PlanQuita	Planes con quitas especiales	AuditPlan	30	1	0	0	0	0	Planes con quitas de nominal, accesorio y/o multa
32	3	2	PlanFecha	Planes con cambio de Fecha	AuditPlan	30	1	0	0	0	0	Planes con cambio de fecha de alta anterior
51	5	1	UsuSinActiv	Usuarios sin actividad	AuditUsuSinActi	50	1	0	0	0	0	Detalle de usuarios sin actividad
53	5	3	UsuAccFecha	Accesos por Fecha	AuditUsu	50	1	0	0	0	0	Usuarios que accedieron en un rango de fechas
54	5	4	UsuAccFechaDet	Accesos por Fecha Detallado	AuditUsuAcc	50	1	0	0	0	0	Detalle de usuarios que accedieron en un rango de fechas
55	5	5	UsuAcc	Accesos por usuario	AuditUsuAcc	50	1	1	0	0	0	Detalle de accesos por usuario
56	5	6	UsuAccErr	Accesos Fallidos por usuario	AuditUsuAcc	50	1	1	0	0	0	Detalle de accesos fallidos por usuario
57	5	7	UsuAccErrEsta	Estadística de accesos fallidos	AuditUsu	50	1	0	0	0	0	Estadística de accesos fallidos
58	5	8	UsuEsta	Estadística producción por usuario	AuditEsta	50	1	0	0	0	0	Estadística de Producción por Usuario
41	4	1	DDJJFecha	Cambio de Fecha de presentación	AuditDDJJFecha	40	1	0	0	0	0	DDJJ con cambio de fecha de presentación anterior
59	5	9	UsuAccErrCant	Usuarios con Cantidad de accesos fallidos	AuditUsu	50	1	0	0	0	1	Estadística de Usuarios con más de N cantidad de accesos fallidos
\.


--
-- TOC entry 6000 (class 0 OID 5287196)
-- Dependencies: 628
-- Data for Name: rpt_caja; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.rpt_caja (cod, niv1, niv2, tipo, nombre, report, padre, tesoreria, caja, sup, fecha, desdehasta, trib, cta, item, anio, anio_mes, detalle) FROM stdin;
10	1	0		Comprobantes		0	0	0	0	0	0	0	0	0	0	0	Comprobantes
20	2	0		Medios de Pago		0	0	0	0	0	0	0	0	0	0	0	Medios de Pago
30	3	0		Cuenta		0	0	0	0	0	0	0	0	0	0	0	Cuenta
40	4	0		Tributo		0	0	0	0	0	0	0	0	0	0	0	Tributo
50	5	0		Fecha		0	0	0	0	0	0	0	0	0	0	0	Fecha
60	6	0		Presupuestario		0	0	0	0	0	0	0	0	0	0	0	Presupuestario
70	7	0		Ingresos Anuales		0	0	0	0	0	0	0	0	0	0	0	Ingresos Anuales
83	8	3	Item	Totales por Item y fecha para un tributo e items	CajaItem	80	1	0	0	0	1	1	0	1	0	0	Totales por Item y fecha para un tributo e items
90	9	0		Caja Externa		0	0	0	0	0	0	0	0	0	0	0	Caja Externa
80	8	0		Item		0	0	0	0	0	0	0	0	0	0	0	Item
82	8	2	ItemBoletoCol	Totales por Item y fecha para un sell/bol (col)	CajaItemCol	80	1	0	0	0	1	1	0	0	0	0	Totales por Item y fecha para un sell/bol (col)
11	1	1	CompCob	Comprobantes cobrados por caja	CajaComp	10	1	1	0	1	0	0	0	0	0	0	Comprobantes cobrados por caja
12	1	2	CompCobAnul	Comprobantes cobrados y anulados por caja	CajaComp	10	1	1	0	1	0	0	0	0	0	0	Comprobantes cobrados y anulados por caja
13	1	3	CompAnul	Comprobantes anulados por caja	CajaCompAnula	10	1	1	0	1	0	0	0	0	0	0	Comprobantes anulados por caja
14	1	4	CompAnulSup	Comprobantes anulados por Supervisor	CajaCompAnula	10	1	0	1	0	1	0	0	0	0	0	Comprobantes anulados por Supervisor
16	1	6	CompTrib	Comprobantes para un tributo	CajaCompTrib	10	1	0	0	0	1	1	0	0	0	0	Comprobantes para un tributo
21	2	1	Mdp	Totales por Caja y Fecha	CajaMdp	20	1	1	0	1	0	0	0	0	0	0	Totales por Caja y Fecha
22	2	3	MdpEspecial	Detalle de Medios de pago especiales	CajaMdpEspecial	20	1	1	0	1	0	0	0	0	0	0	Detalle de Medios de pago especiales
23	2	4	MdpArqueo	Arqueo de Caja	CajaMdpArqueo	20	1	1	0	1	0	0	0	0	0	0	Arqueo de Caja
25	2	5	MdpEspecial2	Detalle para un medio de pago	CajaMdpEspecial	20	1	0	0	0	1	0	0	0	0	0	Detalle para un Medio de pago cobrados
31	3	1	Cta	Totales por cuenta	CajaCta	30	1	0	0	0	1	0	0	0	0	0	Totales por cuenta
32	3	2	CtaCaja	Totales por cuenta para una caja	CajaCta	30	1	1	0	1	0	0	0	0	0	0	Totales por cuenta para una caja
33	3	3	CtaGrupo	Totales por grupo de cuentas	CajaCtaGrupo	30	1	0	0	0	1	0	0	0	0	0	Totales por grupo de cuentas
34	3	4	CtaTribFch	Totales por fecha y tributo para una cuenta	CajaCtaTribFch	30	1	0	0	0	1	0	1	0	0	0	Totales por fecha y tributo para una cuenta
41	4	1	Trib	Totales por tributo	CajaTrib	40	1	0	0	0	1	0	0	0	0	0	Totales por tributo
42	4	2	TribCaja	Totales por tributo para una caja	CajaTrib	40	1	1	0	1	0	0	0	0	0	0	Totales por tributo para una caja
43	4	3	TribCajaFaci	Totales por tributo para una caja (c/Faci)	CajaTrib	40	1	1	0	1	0	0	0	0	0	0	Totales por tributo para una caja (c/Facilidad)
51	5	1	Fecha	Totales por fecha	CajaFecha	50	1	0	0	0	1	0	0	0	0	0	Totales por fecha
62	6	2	PresMes	Presupuestario Mensual	CajaPresMes	60	1	0	0	0	1	0	0	0	0	0	Presupuestario Mensual
91	9	1	CompCob	Comprobantes por Fecha	CajaComp	90	0	1	0	1	0	0	0	0	0	0	Comprobantes por Fecha
84	8	4	ItemAgrupa	Totales por Item para un tributo e items	CajaItem	80	1	0	0	0	1	1	0	1	0	0	Totales por Item para un tributo e items
81	8	1	ItemBoleto	Totales por Item y fecha para un sell/bol	CajaItem	80	1	0	0	0	1	1	0	0	0	0	Totales por Item y fecha para un sell/bol
72	7	2	AnualCta	Totales por mes y cuenta	CajaAnualCta	70	1	0	0	0	0	0	0	0	1	0	Totales por mes y cuenta
71	7	1	Anual	Totales por mes	CajaAnual	70	1	0	0	0	0	0	0	0	1	0	Totales por mes
64	6	4	PresCtaMes	Presupuestario con cuentas Mensual	CajaPresMes	60	1	0	0	0	1	0	0	0	0	0	Presupuestario con cuentas Mensual
63	6	3	PresCta	Presupuestario con cuentas	CajaPres	60	1	0	0	0	1	0	0	0	0	0	Presupuestario con cuentas
94	9	4	ComisionExt	Resumen por agente de cobro	CajaComisionExt	90	0	0	0	0	1	0	0	0	0	0	Resumen de los cobros de cada agente externo.
93	9	3	ResumenExt	Resumen por Fecha	CajaResumenExt	90	0	1	0	0	1	0	0	0	0	0	Resumen por Fecha
17	1	7	CompUnaCta	Comprobantes para una cuenta	CajaComp	10	1	0	0	1	0	0	1	0	0	0	Comprobantes para una cuenta
15	1	5	CompCta	Comprobantes según cuenta por caja	CajaCompCta	10	1	1	0	1	0	0	0	0	0	0	Comprobantes según cuenta por caja
65	6	5	PresDia	Presupuestario por Día		60	1	0	0	0	0	0	0	0	0	1	Presupuestario por Día para un Mes
66	6	6	PresDiaCta	Presupuestario por Día con Cta		60	1	0	0	0	0	0	0	0	0	1	Presupuestario por Día para un Mes con Cuentas
35	3	5	CtaBco	Totales a Depositar por cuenta bancaria	CajaCtaBco	30	1	0	0	0	1	0	0	0	0	0	Totales por Fecha y Tesorería para Cta. Bancaria
24	2	2	MdpFecha	Totales entre Fechas	CajaMdp	20	1	0	0	0	1	0	0	0	0	0	Totales de Medios de Pago para un rango de fechas
61	6	1	Pres	Presupuestario	CajaPres	60	1	0	0	0	1	0	0	0	0	0	Presupuestario
67	6	7	PresCaja	Presupuestario por Caja	CajaPres	60	1	0	0	0	0	0	0	0	0	1	Presupuestario por Caja
36	3	6	CtaBcoCaja	Ingreso Efectivo por Caja y Cta. Bancaria	CajaCta	30	1	1	0	0	1	0	0	0	0	0	Totales de Cuenta Bancaria por Caja
\.


--
-- TOC entry 6001 (class 0 OID 5287202)
-- Dependencies: 629
-- Data for Name: rpt_esta; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.rpt_esta (cod, niv1, niv2, tipo, nombre, report, padre, trib1, trib2, item, tobj, per1, per2, per3, rangomontos, rangofechas, rangodeuda, fchconsolida, objetosbaja, progresbar, ordenobjeto, ordennombre, ordenticket, detalle) FROM stdin;
40	4	0		Deuda a Cobrar		0	0	0	0	0				0	0	0	0	0	0	0	0	0	\N
12	1	2	xPerPagos	Pagos por Período Agrup. por fecha	EstaPagos	10	1	0	0	0	Período:			0	1	0	0	0	0	0	0	0	Informe de pagos realizados para un tributo-período, agrupados por fecha.
13	1	3	xPerPagosDet	Pagos por Período Detallado	EstaPagosDet	10	1	0	0	0	Período:			0	1	0	0	0	0	0	0	0	Detalle de pagos realizados para un tributo-período.
14	1	4	EmiRangos	Rango de Montos por Período	EstaEmiRango	10	1	0	0	0	Período:			1	0	0	0	0	0	0	0	0	Estadística de emisión para un período. Permite definir la tabla de clasificaciones de montos a utilizar.
70	7	0	DeudaPlan	Deuda Convenio de Pago	EstaDeudaPlan	40	0	0	0	0				0	1	0	1	1	0	0	0	0	Informe de la cantidad de planes vencidos agrupados por mes.
21	2	1	ComparaEmi	Comparación de Períodos de Emisión	Estadistica2	20	1	0	0	0	Período a comparar:	Período contra el que se compara:		0	0	0	0	0	0	0	0	0	Compara la variación de emisión entre dos períodos, identificando los que bajan, se mantienen o aumentan.
22	2	2	ComparaDJ	Comparación de Períodos de DDJJ	EstadisticaDJ	20	0	0	0	0	Período 1:	Período 2:	Período 3:	0	0	0	0	0	0	0	0	0	Compara la base imponible y monto de las DJ presentadas, comparando 3 períodos que se ngresan como parámetro.
30	3	0		Situación Contribuyentes		0	0	0	0	0				0	0	0	0	0	0	0	0	0	\N
20	2	0		Comparaciones		0	0	0	0	0				0	0	0	0	0	0	0	0	0	\N
10	1	0		Analisis de un Período		0	0	0	0	0				0	0	0	0	0	0	0	0	0	\N
60	8	0		Expedientes Obras Particulares		0	0	0	0	0				0	0	0	0	0	0	0	0	0	
23	2	3	Evolucion	Evolución entre Períodos	EstaEvolucion	20	1	0	0	0	Período Desde:	Período Hasta:		0	0	0	0	0	0	0	0	0	Informa la evolución del importe emitido y pagado, dentro de un rango de períodos
41	4	1	DeudaEmiDet	Deuda - Detallada por Período	EstaDeuda	40	1	0	0	0	Desde:	Hasta:		0	0	0	1	1	0	0	0	0	Informe de deuda agrupada por período, incluyendo nominal, accesorios y multa.
42	4	2	DeudaEmiAnio	Deuda - Agrupado por Año	EstaDeuda	40	1	0	0	0	Desde:	Hasta:		0	0	0	1	1	0	0	0	0	Informe de deuda agrupada por año, incluyendo nominal, accesorios y multa.
43	4	3	DeudaEventDet	Deuda - Detallada por Mes (Liq.Eventual)	EstaDeuda	40	1	0	0	0				0	1	0	1	1	0	0	0	0	Informe de deuda agrupada por mes, incluyendo nominal, accesorios y multa. Es utilizado para liquidaciones eventuales.
44	4	4	DeudaEventAnio	Deuda - Agrupado por Año (Liq.Eventual)	EstaDeuda	40	1	0	0	0				0	1	0	1	1	0	0	0	0	Informe de deuda agrupada por año, incluyendo nominal, accesorios y multa. Es utilizado para liquidaciones eventuales.
67	6	7	DeudaCemArren	Cementerio - Arrendamientos Vencidos	EstaDeuda	40	0	0	0	0				0	1	0	1	0	0	0	0	0	Informe de arrendamientos vencidos a una determinada fecha que se indica como parámetro, agrupados por año.
11	1	1	xPeriodoGral	Informe General por Período	Estadistica	10	1	0	0	0	Período:			0	0	0	0	0	0	0	0	0	Estadística de un período, detallando totales por item, evolución y pagos.
24	2	4	ComparaPago	Pago Primer Período y Adeuda el segundo	IntiResumen2	20	1	0	0	0	Período 1:	Período 2:		0	0	0	0	0	0	0	0	0	Detalle de casos que realizaron el pago del primer período que se informa, pero adeudan el segundo período.
25	2	5	ComparaPresenta	Presentado Primer Período y no el segundo	IntiResumen2	20	1	0	0	0	Período 1:	Período 2:		0	0	0	0	0	0	0	0	0	Detalle de casos que presentaron la DJ del primer período que se informa, pero no presentaron para el segundo período.
68	4	8	DeudaBarrio	Deuda Inm - Agrupada por Barrio	EstaDeudaBarrio	40	1	0	0	0	Desde:	Hasta:		0	0	0	0	0	0	0	0	0	Informe de Deuda de inmuebles agrupada por barrios
61	8	1	Expe_x_Uso	Cant. de expedientes por Uso	ObrasPrivEsta1	60	0	0	0	0				0	1	0	0	0	0	0	0	0	Cantidad de Expedientes por Uso
62	8	2	Expe_x_Obra	Cant. de expedientes por Tipo de Obra	ObrasPrivEsta1	60	0	0	0	0				0	1	0	0	0	0	0	0	0	Cantidad de Expedientes por Tipo de Obra
63	8	3	Mejoras_x_Dest	Superficies por Destino	ObrasPrivEsta2	60	0	0	0	0				0	1	0	0	0	0	0	0	0	Superficies de Mejoras por Destino
64	8	4	Mejoras_x_Obra	Superficies por Estado de Obra	ObrasPrivEsta2	60	0	0	0	0				0	1	0	0	0	0	0	0	0	Superficies de Mejoras por Estado de Obra
65	8	5	Cant_Hab_Plazas	Habitac. y Plazas por Est. de Obra	ObrasPrivEsta2	60	0	0	0	0				0	1	0	0	0	0	0	0	0	Habitaciones y plazas por Estado de Obra
45	4	5	DeudaPeriodo	Deuda - Detallada por Período	EstaDeudaTrib	40	1	0	0	0	Período Desde:	Período Hasta:		0	0	1	0	0	1	0	0	0	Detalle de deudores de acuerdo a períodos adeudados e importe de deuda.
46	4	6	DeudaObjeto	Deuda - Detallada por Objeto		40	1	0	0	0	Período Desde:	Período Hasta:		0	0	0	1	1	1	1	0	0	Detalle de Objetos deudores.
57	9	0		Rodados		0	0	0	0	0				0	0	0	0	0	0	0	0	0	
58	9	1	Rodado_Flota	Contribuyentes con flota		57	1	0	0	0	Período Desde:	Período Hasta:	Cant. Rodados mayor a:	0	0	0	0	0	0	0	0	0	Contribuyentes con flota de rodados
71	10	0		Liquidaciones		0	0	0	0	0				0	0	0	0	0	0	0	0	0	
72	10	1	LiqItemXAnio	Liquidación para cada ítem agrupado por año	EstaLiqItem	71	1	0	1	0				0	1	0	0	0	0	0	0	0	Liquidación para cada ítem agrupado por año para un tributo dado
27	2	7	ComparaInm	Comparación Avaluos de Inm		20	1	1	0	0		Período 1:	Período 2:	0	0	0	0	0	0	0	0	0	Informe de cambios de avalúos y tasa inmobiliaria 
26	2	6	ComparaPagoNP	Pago Primer Período y No Presenta el segundo	IntiResumen2	20	1	0	0	0	Período 1:	Período 2:		0	0	0	0	0	0	0	0	0	Detalle de casos que realizaron el pago del primer período que se informa, pero no presentaron para el segundo período.
80	11	0		Agente de Retención		0	0	0	0	0				0	0	0	0	0	0	0	0	0	
81	11	1	DDJJxAgente	DDJJ presentadas por Agente		0	0	0	0	0	Año/Mes:			0	0	0	0	0	0	0	0	0	lista los agentes y las dj presentada por cada uno
56	5	6	DeudaDj	DDJJ sin Presentación	EstaDeudaDJ	40	1	0	0	0	Desde:	Hasta:		0	0	0	1	0	0	0	0	0	Informe de DJ sin presentación por año y período.
31	3	1	BuenosPag	Estadística de Buenos Pagadores por Tributo	EstaBuenP	30	1	0	0	0	Período Desde:	Período Hasta:		0	0	0	0	0	1	0	0	0	Detalle de Buenos pagadores de acuerdo a los parámetros ingresados.
32	3	2	BuenosPTotal	Estadística de Buenos Pagadores	EstaBuenPTotal	30	0	0	0	0	Período Desde:	Período Hasta:		0	0	0	0	0	1	0	0	0	Detalle de Buenos pagadores de acuerdo a los parámetros ingresados.
15	1	5	EmiError	Errores de Emisión	EmiError	10	1	0	0	0	Período Desde:	Período Hasta:		0	0	0	0	0	0	0	0	0	Estadística de errores de emisión por tributo y periodos.
\.


--
-- TOC entry 6002 (class 0 OID 5287213)
-- Dependencies: 630
-- Data for Name: rpt_fiscaliza; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.rpt_fiscaliza (cod, nombre, report, detalle, existefirma, firma_id, existetexto, texto_id) FROM stdin;
9	Anexo 4	FiscalizaAnexo4	Cuestionario	0	0	0	0
12	Anexo 7	FiscalizaAnexo7	Información Complementaria	0	0	0	0
1	Acta de Constatación	FiscalizaConstata	Acta de Constatación	0	0	1	20
2	Intimación de Pago	FiscalizaIntima	Intimación de Pago	1	100	1	17
3	Inicio de Inspección	FiscalizaInicio	Acta de Inicio de Inspección	0	0	1	18
4	Retiro de Información	FiscalizaRetiro	Acta de Retiro de Información	0	0	1	19
5	Anexo 1A	FiscalizaAnexo1A	Montos de Ingresos Netos de Contabilidad (Libros I.V.A. Ventas, etc.) por actividad	0	0	0	0
6	Anexo 1B	FiscalizaAnexo1B	Montos de Ingresos Netos de Contabilidad (Libros I.V.A. Ventas, etc.) por actividad	0	0	0	0
7	Anexo 2	FiscalizaAnexo2	Distribución de Ingresos y coeficientes aplicados a la Provincia según el Convenio Multilateral - DDJJ CM03 y CM05	0	0	0	0
8	Anexo 3	FiscalizaAnexo3	Acogimiento a Regimenes de Regularización y otras Prestaciones de Reconocimiento de Deuda	0	0	0	0
10	Anexo 5	FiscalizaAnexo5	Discriminación de Ingresos y Gastos computables para la Jurisdicción de la Provincia	0	0	0	0
11	Anexo 6	FiscalizaAnexo6	Conciliación de Ventas e Ingresos con Balances comerciales	0	0	0	0
\.


--
-- TOC entry 6003 (class 0 OID 5287221)
-- Dependencies: 631
-- Data for Name: sis_clave_blanqueo; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.sis_clave_blanqueo (usr_id, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 6005 (class 0 OID 5287227)
-- Dependencies: 633
-- Data for Name: sis_grupo; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.sis_grupo (gru_id, nombre, usradm1, usradm2, fchmod, usrmod) FROM stdin;
1	ADMINISTRACIÓN	10	11	2017-11-24 09:55:11.950819	11
\.


--
-- TOC entry 6010 (class 0 OID 5287248)
-- Dependencies: 638
-- Data for Name: sis_sistema; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.sis_sistema (sis_id, nombre, fchmod, usrmod) FROM stdin;
3	SAM - Tributario	2006-03-28 10:20:05	100
1	SAM - Seguridad	2006-03-28 10:13:58	100
7	SAM - Servicio Web	2016-03-28 15:32:09.652388	100
\.


--
-- TOC entry 6007 (class 0 OID 5287236)
-- Dependencies: 635
-- Data for Name: sis_modulo; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.sis_modulo (mod_id, nombre, sis_id, detalle, fchmod, usrmod) FROM stdin;
11	Muni	1	Datos Municipales	2011-02-17 13:25:51.306	100
13	Usuarios	1	Usuarios y Grupos	2011-02-17 13:25:51.306	100
23	Actividades	3	Actividades Comerciales	2011-02-17 13:25:51.306	100
24	Presupuesto	3	Presupuesto y Cuentas	2011-02-17 13:25:51.306	100
25	Accesorios - UCM	3	Interes, Descuento y Multas	2011-02-17 13:25:51.306	100
63	Tributo-Acc	3	Acciones para Tributos	2011-02-17 13:25:51.306	100
26	Objeto	3	Objeto	2011-02-17 13:25:51.306	100
27	Inmueble	3	Inmueble	2011-02-17 13:25:51.306	100
28	Comercio	3	Comercio	2011-02-17 13:25:51.306	100
29	Persona	3	Personas	2011-02-17 13:25:51.306	100
30	Cementerio	3	Cementerio	2011-02-17 13:25:51.306	100
32	Imponibles	3	Otros Imponibles	2011-02-17 13:25:51.306	100
51	CtaCte	3	Cuenta Corriente	2011-02-17 13:25:51.306	100
53	Liquidaciones	3	Liquidaciones	2011-02-17 13:25:51.306	100
55	Planes	3	Planes de Pago	2011-02-17 13:25:51.306	100
34	Rodado	3	Rodados	2011-02-17 13:25:51.306	100
64	Facilidades	3	Facilidades de Pago	2011-02-17 13:25:51.306	100
71	Oficinas	3	Oficinas	2011-02-17 13:25:51.306	100
72	Domicilios	3	Domicilios	2011-02-17 13:25:51.306	100
31	Cuenta OSM	3	Cuentas y Medidores de OSM	2011-02-17 13:25:51.306	100
73	Textos	3	Textos	2011-02-17 13:25:51.306	100
75	Habilitaciones	3	Habilitaciones Comerciales	2011-02-17 13:25:51.306	100
20	Sistema	3	Sistema	2011-02-17 13:25:51.306	100
58	Judiciales	3	Apremios Judiciales	2011-02-17 13:25:51.306	100
12	Procesos	1	Módulos y Procesos	2011-02-17 13:25:51.306	100
14	Auditoria	1	Reportes de Auditorías	2011-02-17 13:25:51.306	100
57	Fiscalización	3	Fiscalización de Tasa Higiene	2011-02-17 13:25:51.306	100
61	Estadísticas	3	Estadísticas e indicadores de Gestión	2011-02-17 13:25:51.306	100
52	Emisión	3	Emisión Masiva	2011-02-17 13:25:51.306	100
54	DDJJ	3	Declaración Jurada Higiene	2011-02-17 13:25:51.306	100
66	Débito	3	Débito Automático	2011-02-17 13:25:51.306	100
35	Publicidad	3	Publicidad	2011-02-18 08:46:04	100
33	Transporte	3	Transporte de Pasajeros	2012-04-19 12:16:53.374134	100
36	Auditoria	3	Reportes de Auditoría	2012-07-03 08:53:41.880306	100
22	Tributos - Resoluc.	3	Tributos y Resoluciones	2011-02-17 13:25:51.306	100
62	Caja	3	Caja	2011-02-17 13:25:51.306	100
60	Calles y GIS	3	Ejes de Calles - GIS	2011-02-17 13:25:51.306	100
80	Mail	7	Mail al municipio	2016-03-28 15:32:12.471881	100
81	Reclamos	7	Formular reclamos al municipio	2016-03-28 15:32:12.471881	100
82	Boleta	7	Imprimir Boleta adeudadas	2016-03-28 15:32:12.471881	100
83	Expediente	7	Seguimiento de Expedientes	2016-03-28 15:32:12.471881	100
84	Licitaciones	7	concursos de Precios y Licitaciones	2016-03-28 15:32:12.471881	100
85	Contribuyente	7	Datos Persona y Cuenta Corriente	2016-03-28 15:32:12.471881	100
86	DDJJ	7	Acceder a las DDJJ	2016-03-28 15:32:12.471881	100
87	Proveedor	7	Datos proveedor, sus saldos y OC/OP	2016-03-28 15:32:12.471881	100
88	Ag. Rete.	7	Formulario de Agente de Retención	2016-03-28 15:32:12.471881	100
89	Usuario Web	7	Usuarios Servicio Web	2016-03-28 15:32:12.471881	100
\.


--
-- TOC entry 6008 (class 0 OID 5287240)
-- Dependencies: 636
-- Data for Name: sis_proceso; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.sis_proceso (pro_id, nombre, mod_id, detalle, uso_web, fchmod, usrmod) FROM stdin;
1024	Usuario - Edita General	13	Edición de Usuarios todos los Grupos	S	2016-07-09 09:09:50.170852	100
3323	Liquida - Modifica	53	Modificación de Liquidaciones Menores	S	2016-08-11 08:34:08.247764	100
3436	CtaCte - Elimina Liq./Baj	55	Eliminar liq. posteriores a la baja	S	2016-08-12 10:11:05.843561	100
3358	Plan - Edición de Datos	51	Edición de responsable y observaciones	S	2015-05-29 14:26:24.573322	100
3359	Plan - Cambiar Cuotas	51	Establecer el número de cuota inicial	S	2015-05-29 14:26:24.573322	100
1000	Muni - Consulta	11	Consulta de Datos Municipales	S	2015-05-29 14:26:24.573322	100
1001	Muni - Edita	11	Edición de Datos Municipales	S	2015-05-29 14:26:24.573322	100
1010	Proceso - Consulta	12	Consulta de los Permisos de sistema	S	2015-05-29 14:26:24.573322	100
1020	Usuarios - Consulta	13	Consulta de Usuarios	S	2015-05-29 14:26:24.573322	100
1021	Usuario - Edita	13	Edición de Usuarios	S	2015-05-29 14:26:24.573322	100
1022	Usuario - Limpiar Clave	13	Limpiar la Clave de Usuario	S	2015-05-29 14:26:24.573322	100
1023	Usuario - Eliminar Acceso	13	Eliminar el último acceso del Usuario	S	2015-05-29 14:26:24.573322	100
1040	Auditoría	14	Consulta de auditoría	S	2015-05-29 14:26:24.573322	100
3010	Trib - Consulta	22	Consulta de Tributos o Items	S	2015-05-29 14:26:24.573322	100
3011	Trib - Edicion	22	Edición de Tributos o Items	S	2015-05-29 14:26:24.573322	100
3012	Resol - Consulta	22	Consulta de Resolución	S	2015-05-29 14:26:24.573322	100
3013	Resol - Edita	22	Edición de Resolución	S	2015-05-29 14:26:24.573322	100
3014	Resol - Edita Datos	22	Edición de Tabla de alicuotas	S	2015-05-29 14:26:24.573322	100
3016	Trib - Venc. Consulta	22	Consulta de Vencimientos	S	2015-05-29 14:26:24.573322	100
3017	Trib - Venc. Edita	22	Edición de Vencimientos	S	2015-05-29 14:26:24.573322	100
3018	Resol - Estructura	22	Edición de Estructura de Tabla	S	2015-05-29 14:26:24.573322	100
3019	Resol - Formula	22	Edición de Fórmula de Resolución	S	2015-05-29 14:26:24.573322	100
3030	Rubros - Consulta	23	Consulta de Rubros Económicos	S	2015-05-29 14:26:24.573322	100
3031	Rubros - Edita	23	Edición de Rubros Económicos	S	2015-05-29 14:26:24.573322	100
3040	PlanCuenta - Consulta	24	Consulta de Plan de Cuentas	S	2015-05-29 14:26:24.573322	100
3041	PlanCuenta - Edita	24	Edición de Plan de Cuentas	S	2015-05-29 14:26:24.573322	100
3051	Accesorio - Interés	25	Definición de parámetros de interés	S	2015-05-29 14:26:24.573322	100
3052	Accesorio - Descuento	25	Definición de parámetros de descuento	S	2015-05-29 14:26:24.573322	100
3053	Accesorio - Multa	25	Definición de parámetros de multa	S	2015-05-29 14:26:24.573322	100
3054	Configuración - Edita	25	Edición de Configuraciones Varias	S	2015-05-29 14:26:24.573322	100
3055	Accesorio - MM	25	Edición de Módulos Municipales	S	2015-05-29 14:26:24.573322	100
3060	Objeto - Tipo Cons	26	Consulta de Tipos de Objeto	S	2015-05-29 14:26:24.573322	100
3061	Objeto - Tipo Edita	26	Edición de Tipos de Objeto	S	2015-05-29 14:26:24.573322	100
3063	Objeto - TAccion Edita	26	Editar Tipos de Acciones sobre Objetos	S	2015-05-29 14:26:24.573322	100
3064	Objeto - TBaja Cons	26	Consulta de Tipos de Baja	S	2015-05-29 14:26:24.573322	100
3065	Objeto - TBaja Edita	26	Edición de Tipos de Baja	S	2015-05-29 14:26:24.573322	100
3070	Inmueble - Cons	27	Consulta de Inmuebles	S	2015-05-29 14:26:24.573322	100
3071	Inmueble - Edita	27	Edición de Inmuebles	S	2015-05-29 14:26:24.573322	100
3072	Inmueble - Alta	27	Alta de Inmueble	S	2015-05-29 14:26:24.573322	100
3073	Inmueble - Baja	27	Baja de Inmueble	S	2015-05-29 14:26:24.573322	100
3075	Inmueble - Dom Post.-Dist	27	Edición de Domicilio Postal y Distribuid	S	2015-05-29 14:26:24.573322	100
3076	Inmueble - Dom Parcela	27	Edición de Domicilio Parcelario	S	2015-05-29 14:26:24.573322	100
3078	Inmueble - Variables	27	Edición de variables del inmueble	N	2015-05-29 14:26:24.573322	100
3079	Inmueble - Imprime	27	Imprimir datos del inmueble	S	2015-05-29 14:26:24.573322	100
3080	Inmueble - Aux. Cons	27	Consulta de auxiliares de inmueble	S	2015-05-29 14:26:24.573322	100
3081	Inmueble - Aux. Edita	27	Edición de auxiliares de Inmueble	S	2015-05-29 14:26:24.573322	100
3082	Inmueble - Unificar	27	Unificar Inmuebles	S	2015-05-29 14:26:24.573322	100
3083	Inmueble - Exencion	27	Inmuebles - Aplicar Exención	S	2015-05-29 14:26:24.573322	100
3092	Inmueble - Revalúo	27	Proceso de revalúo de Inmueble	S	2015-05-29 14:26:24.573322	100
3093	Inmueble - Estadísticas	27	Consulta estadísticas de inmuebles	S	2015-05-29 14:26:24.573322	100
3094	Inmueble - Edita Aval	27	Edición de avalúos de inmuebles	S	2015-05-29 14:26:24.573322	100
3104	Inmueble - Transferencia	27	Transferencia de Inmuebles	S	2015-05-29 14:26:24.573322	100
3105	Inmueble - Otras Acciones	27	Otras Acciones sobre Inmueble	S	2015-05-29 14:26:24.573322	100
3200	Comercio - Cons	28	Consulta de comercio	S	2015-05-29 14:26:24.573322	100
3201	Comercio - Edita	28	Edición de comercio	S	2015-05-29 14:26:24.573322	100
3202	Comercio - Alta	28	Alta de comercio	S	2015-05-29 14:26:24.573322	100
3203	Comercio - Baja	28	Baja de Comercio	S	2015-05-29 14:26:24.573322	100
3204	Comercio - Denominación	28	Edición de Denominación del Comercio	S	2015-05-29 14:26:24.573322	100
3205	Comercio - Domicilio	28	Edición de Domicilio de Comercio	S	2015-05-29 14:26:24.573322	100
3206	Comercio - Inmueble	28	Edición del Vínculo con Inmueble	S	2015-05-29 14:26:24.573322	100
3207	Comercio - Variables	28	Edición de Variables del comercio	N	2015-05-29 14:26:24.573322	100
3214	Comercio - Habilitación	28	Edición de Habilitaciones	S	2015-05-29 14:26:24.573322	100
3208	Comercio - Histórico	28	Edición de Históricos del Comercio	S	2015-05-29 14:26:24.573322	100
3209	Comercio - Imprime	28	Imprimir detalles del Comercio	S	2015-05-29 14:26:24.573322	100
3210	Comercio - Aux. Cons	28	Consulta de Auxiliares de comercio	S	2015-05-29 14:26:24.573322	100
3211	Comercio - Aux. Edita	28	Edición de Auxiliares de comercio	S	2015-05-29 14:26:24.573322	100
3212	Comercio - Unificar	28	Unificar Comercios	S	2015-05-29 14:26:24.573322	100
3213	Comercio - Exencion	28	Comercios - Aplicar Exención	S	2015-05-29 14:26:24.573322	100
3216	Comercio - Porc Rubro	28	Asignar Porc a aplicar al rubro del com	N	2015-05-29 14:26:24.573322	100
3217	Comercio - Transferencia	28	Transferencia de Comercio	S	2015-05-29 14:26:24.573322	100
3218	Comercio - Otras Acciones	28	Otras Acciones sobre Comercio	S	2015-05-29 14:26:24.573322	100
3219	Comercio - Edita Rubro	28	Edición de los Rubros de Comercios	S	2015-05-29 14:26:24.573322	100
3220	Persona - Cons	29	Consulta de Persona	S	2015-05-29 14:26:24.573322	100
3221	Persona - Edita	29	Edición de Persona	S	2015-05-29 14:26:24.573322	100
3050	site-config	25	Consulta de Configuraciones	S	2015-05-29 14:26:24.573322	100
3222	Persona - Alta	29	Alta de Presona	S	2015-05-29 14:26:24.573322	100
3223	Persona - Baja	29	Baja de Presona	S	2015-05-29 14:26:24.573322	100
3224	Persona - Aux. Cons	29	Consulta de auxiliar de Presona	S	2015-05-29 14:26:24.573322	100
3225	Persona - Aux. Edita	29	Consulta Conducta tributaria por Persona	S	2015-05-29 14:26:24.573322	100
3227	Persona - Reemplaza	29	Reemplazar Persona	S	2015-05-29 14:26:24.573322	100
3228	Persona - Exencion	29	Contribuyentes - Aplicar Exención	S	2015-05-29 14:26:24.573322	100
3229	Persona - Imprime	29	Contribuyentes - Impresión	S	2015-05-29 14:26:24.573322	100
3601	Persona - Otras Acc	29	Otras Acciones sobre Personas	S	2015-05-29 14:26:24.573322	100
3602	Persona - Dom Postal	29	Edición de Domicilio Postal	S	2015-05-29 14:26:24.573322	100
3603	Persona - Dom Legal	29	Edición de Domicilio Legal	S	2015-05-29 14:26:24.573322	100
3604	Persona - Histórico	29	Consulta de Histórico	S	2015-05-29 14:26:24.573322	100
3230	Cementerio - Cons	30	Consulta de parcela Cementerio	S	2015-05-29 14:26:24.573322	100
3231	Cementerio - Edita	30	Edición de parcela Cementerio	S	2015-05-29 14:26:24.573322	100
3232	Cementerio - Alta	30	Alta de parcela Cementerio	S	2015-05-29 14:26:24.573322	100
3233	Cementerio - Baja	30	Baja de parcela Cementerio	S	2015-05-29 14:26:24.573322	100
3234	Cementerio - Arrendam.	30	Arrendamiento de Cementerio	S	2015-05-29 14:26:24.573322	100
3236	Cementerio - Variables	30	Edición de variables del cementerio	N	2015-05-29 14:26:24.573322	100
3237	Cementerio - Histórico	30	Consulta de Histórico	S	2015-05-29 14:26:24.573322	100
3238	Cementerio - Imprime	30	Impreción de datos de Cementerio	S	2015-05-29 14:26:24.573322	100
3239	Cementerio - Aux. Cons	30	Consulta de Auxiliar de Cementerio	S	2015-05-29 14:26:24.573322	100
3240	Cementerio - Aux. Edita	30	Edición de Auxiliar de Cementerio	S	2015-05-29 14:26:24.573322	100
3241	Cementerio - Unificar	30	Cementerio - Unificar	S	2015-05-29 14:26:24.573322	100
3242	Cementerio - Exencion	30	Cementerio - Aplicar Exencion	S	2015-05-29 14:26:24.573322	100
3243	Cementerio - Dom Postal	30	Edición de Domicilio Postal	S	2015-05-29 14:26:24.573322	100
3244	Cementerio - Transf	30	Transferencia de Cementerio	S	2015-05-29 14:26:24.573322	100
3245	Cementerio - Otras Acc	30	Otras Acciones sobre Cementerio	S	2015-05-29 14:26:24.573322	100
3280	Rodado - Cons	34	Consulta de Rodado	S	2015-05-29 14:26:24.573322	100
3281	Rodado - Edita	34	Edición de Rodado	S	2015-05-29 14:26:24.573322	100
3282	Rodado - Alta	34	Alta de Rodado	S	2015-05-29 14:26:24.573322	100
3283	Rodado  - Baja	34	Baja de Rodado	S	2015-05-29 14:26:24.573322	100
3284	Rodado - Historicos	34	Consulta de Histórico	S	2015-05-29 14:26:24.573322	100
3285	Rodado - Aux Cons	34	Consulta de Auxiliares de Rodados	S	2015-05-29 14:26:24.573322	100
3286	Rodado - Aux Edita	34	Edición de Auxiliares de Rodados	S	2015-05-29 14:26:24.573322	100
3287	Rodado - Transferencia	34	Transferencia de Rodados	S	2015-05-29 14:26:24.573322	100
3288	Rodado - Exencion	34	Rodados - Aplicar Exención	S	2015-05-29 14:26:24.573322	100
3289	Rodado - Dom Postal	34	Edición de Domicilio Postal	S	2015-05-29 14:26:24.573322	100
3290	Rodado - Otras Acciones	34	Otras Acciones sobre Rodados	S	2015-05-29 14:26:24.573322	100
3291	Rodado - Cambio Cha/Mot	34	Edición de Chasis / Motor	S	2015-05-29 14:26:24.573322	100
3450	Auditoría - Consulta	36	Consulta de reportes de auditoría	S	2015-05-29 14:26:24.573322	100
3451	Auditoría - Imprime	36	Impresión de reportes de auditoría	S	2015-05-29 14:26:24.573322	100
3300	CtaCte - Cons	51	Consultar la CtaCte	S	2015-05-29 14:26:24.573322	100
3301	CtaCte - Imprime Resumen	51	Imprimir resúmenes de CtaCte	S	2015-05-29 14:26:24.573322	100
3302	CtaCte - Prescribir	51	Marcar Períodos Prescriptos	S	2015-05-29 14:26:24.573322	100
3303	CtaCte - Ajuste Cons	51	Consulta de Ajustes en la CtaCte	S	2015-05-29 14:26:24.573322	100
3305	CtaCte - Aux. Cons	51	Auxiliares de Cta Cte - Consulta	S	2015-05-29 14:26:24.573322	100
3306	CtaCte - Aux. Edita	51	Auxiliares de Cta Cte - Edición	S	2015-05-29 14:26:24.573322	100
3307	CtaCte - Excep Recargo	51	Aplicar Excepción de Recargo	S	2015-05-29 14:26:24.573322	100
3308	CtaCte - Ajuste Manual	51	Ajuste manual de la CtaCte	S	2015-05-29 14:26:24.573322	100
3309	Ctacte - Libre Deuda	51	Emisión de Libre de Deuda	S	2015-05-29 14:26:24.573322	100
3315	CtaCte - Cambio de Estado	51	Cambiar Estado a los Períodos	S	2015-05-29 14:26:24.573322	100
3316	CtaCte - Lista Saldos Neg	51	Permite Listar Saldos en Negativo	S	2015-05-29 14:26:24.573322	100
3317	CtaCte - Condona	51	Condonar Períodos en la cuenta corriente	S	2015-05-29 14:26:24.573322	100
3318	CtaCte - Pagos a Cuenta	51	Pagos a Cuenta	S	2015-05-29 14:26:24.573322	100
3430	CtaCte - Ajuste Anula	51	Anular Ajuste de CtaCte	S	2015-05-29 14:26:24.573322	100
3432	CtaCte - Prescri.Conv.Dec	51	Prescribir Períodos de Conv. Decaídos	S	2015-05-29 14:26:24.573322	100
3433	CtaCte - Imprimir Comprob	51	Permite imprimir comprobante pago	S	2015-05-29 14:26:24.573322	100
3500	Compensa/Rete - Cons	51	Consulta de Retenciones	S	2015-05-29 14:26:24.573322	100
3501	Compensa/Rete - Edita	51	Edición de Retenciones	S	2015-05-29 14:26:24.573322	100
3502	Compensa/Rete - Borra	51	Borra Retenciones aplicadas	S	2015-05-29 14:26:24.573322	100
3310	Emisión - Cons	52	Consulta de Emisión Masiva	S	2015-05-29 14:26:24.573322	100
3311	Emisión - Emitir	52	Realizar Emsión Masiva	S	2015-05-29 14:26:24.573322	100
3312	Emisión - Baja	52	Borrar Emisión Masiva	S	2015-05-29 14:26:24.573322	100
3313	Emisión - Aprobar	52	Aprobar Emisión Masiva	S	2015-05-29 14:26:24.573322	100
3314	Emisión - Reliquidar	52	Reliquidar Objeto x tributo-año-cuota	S	2015-05-29 14:26:24.573322	100
3434	Emisión - Reliquidar Pago	52	Reliquidar período aunque este pago	S	2015-05-29 14:26:24.573322	100
3435	Emisión - Baja Ultima	52	Elimina última reliq. de un período	S	2015-05-29 14:26:24.573322	100
3546	Textos - Adh. Débito	52	Edita Textos para adhesión al débito	S	2015-05-29 14:26:24.573322	100
3320	Liquida - Cons	53	Consulta de Liquidaciones Menores	S	2015-05-29 14:26:24.573322	100
3321	Liquida - Alta	53	Alta de Liquidaciones Menores	S	2015-05-29 14:26:24.573322	100
3322	Liquida - Baja	53	Baja de Liquidaciones Menores	S	2015-05-29 14:26:24.573322	100
3330	DDJJ - Cons	54	Consulta de DJ	S	2015-05-29 14:26:24.573322	100
3331	DDJJ - Alta	54	Alta de DJ	S	2015-05-29 14:26:24.573322	100
3332	DDJJ - Baja	54	Baja de DJ	S	2015-05-29 14:26:24.573322	100
3333	DDJJ - Fecha	54	Cambiar Fecha por presentación DJ	S	2015-05-29 14:26:24.573322	100
3334	DDJJ - Generar Faltan	54	Generar DJ Faltantes en CtaCte	S	2015-05-29 14:26:24.573322	100
3335	DDJJ - Aprobar	54	Aprobar DJ Externa	N	2015-05-29 14:26:24.573322	100
3336	DDJJ - Exportar	54	Exportar para DJ Externa	N	2015-05-29 14:26:24.573322	100
3340	Plan - Cons	55	Consultar Convenios de Pago	S	2015-05-29 14:26:24.573322	100
3341	Plan - Alta	55	Efectuar Alta de Convenios de Pago	S	2015-05-29 14:26:24.573322	100
3342	Plan - Alta con CDF	55	Efectuar Alta de Convenios por CDF	S	2015-05-29 14:26:24.573322	100
3343	Plan - Imputa	55	Imputación de Convenios de Pago	S	2015-05-29 14:26:24.573322	100
3344	Plan - Decae	55	Decaimiento de Convenios de Pago	S	2015-05-29 14:26:24.573322	100
3345	Plan - Anula Decae/Imputa	55	Anula Decaimiento/Imputación	S	2015-05-29 14:26:24.573322	100
3347	Plan - Alta Especial	55	Efectuar Alta con Definición de parámetr	N	2015-05-29 14:26:24.573322	100
3349	Plan - Alta Viejos	55	Alta de Planes de Pago Viejos	S	2015-05-29 14:26:24.573322	100
3350	Plan - Config Cons	55	Consultar Configuración de Convenios	S	2015-05-29 14:26:24.573322	100
3351	Plan - Config Edita	55	Modificar Configuración de Convenios	S	2015-05-29 14:26:24.573322	100
3353	Plan - Alta con Fecha	55	Modificar la fecha de Alta del Plan	S	2015-05-29 14:26:24.573322	100
3354	Plan - Correr Venc	55	Correr Fecha de Vencimiento de Cuotas	N	2015-05-29 14:26:24.573322	100
3380	Judicial - Cons	58	Consulta de Planillas Judiciales	S	2015-05-29 14:26:24.573322	100
3381	Judicial - Alta	58	Alta de Planillas Judiciales	S	2015-05-29 14:26:24.573322	100
3382	Judicial - Baja	58	Baja de Planillas Judiciales	S	2015-05-29 14:26:24.573322	100
3383	Judicial - Seguimiento	58	Seguimiento de Planillas Judiciales	S	2015-05-29 14:26:24.573322	100
3385	Judicial - Imprime	58	Impresión de Planillas Judiciales	S	2015-05-29 14:26:24.573322	100
3538	Textos - Obras Priv.	73	Edita Textos de Obras Priv	S	2015-05-29 14:26:24.573322	100
3470	Calles - Cons	60	Consulta de calles	S	2015-05-29 14:26:24.573322	100
3471	Calles - Edita	60	Edición de calles	S	2015-05-29 14:26:24.573322	100
3472	Calles - Aux Cons	60	Consulta de auxiliares de calles	S	2015-05-29 14:26:24.573322	100
3473	Calles - Aux Edita	60	Edición auxiliares de calles	S	2015-05-29 14:26:24.573322	100
3410	Estadísitica - Cons	61	Consulta de Estadísticas y Tablero	S	2015-05-29 14:26:24.573322	100
3411	Estadística - Imprime	61	Impresión de Estadísticas y Tablero	S	2015-05-29 14:26:24.573322	100
3412	Caja - Cons	62	Efectuar consultas de Caja	S	2015-05-29 14:26:24.573322	100
3413	Caja - Cajero	62	Realizar cobros On Line	S	2015-05-29 14:26:24.573322	100
3414	Caja - Externa	62	Operar con Cajas Externas	S	2015-05-29 14:26:24.573322	100
3415	Caja - Apertura	62	Apertura de Caja de Supervisor	S	2015-05-29 14:26:24.573322	100
3416	Caja - Anular	62	Confirmar/Rechazar Anulaciones de Caja	S	2015-05-29 14:26:24.573322	100
3417	Caja - Config Edita	62	Edición Configuración Caja  y Auxiliares	S	2015-05-29 14:26:24.573322	100
3419	Caja - Prueba	62	Prueba de Caja	S	2015-05-29 14:26:24.573322	100
3493	Caja - Home Banking	62	Liquidaciones para Home Banking	S	2015-05-29 14:26:24.573322	100
3372	Fiscaliza - Actualizar	57	Actualizar Fiscalización en datos reales	S	2015-05-29 14:26:24.573322	100
3373	Fiscaliza - Aux	57	Edición de Auxiliares de Fiscalización	S	2015-05-29 14:26:24.573322	100
3370	Fiscaliza - Cons	57	Consulta de Fiscalización	S	2015-05-29 14:26:24.573322	100
3371	Fiscaliza - Edita	57	Edición de Fiscalización	S	2015-05-29 14:26:24.573322	100
3494	Caja - Pagos Viejos	62	Registro de Pagos Viejos	S	2015-05-29 14:26:24.573322	100
3495	Caja - ReImprimir Ticket	62	Reimpresión de Tickets	S	2015-05-29 14:26:24.573322	100
3496	Caja - Recibo Consulta	62	Consulta de Recibos	S	2015-05-29 14:26:24.573322	100
3504	Caja - Consultar Ticket	62	Consulta un Ticket u Operación	S	2015-05-29 14:26:24.573322	100
3570	Caja - Reportes Cajeros	62	Emisión de Reportes de Cajeros	S	2015-05-29 14:26:24.573322	100
3572	Caja - Reportes Avanzado	62	Emisión de Reportes de Caja Avanzado	S	2015-05-29 14:26:24.573322	100
3575	Caja - Config Cons	62	Consulta Configuración Caja y Aux	S	2015-05-29 14:26:24.573322	100
3576	Caja - Reportes Externa	62	Emisión de Reportes de Caja Externa	S	2015-05-29 14:26:24.573322	100
3577	Caja - Cheque Cartera	62	Registro de Cheques en Cartera	S	2015-05-29 14:26:24.573322	100
3578	Caja - Arqueo Edita	62	Edición de arqueo de caja	S	2015-05-29 14:26:24.573322	100
3420	Asigna - Cons	63	Consulta de Asignaciones	S	2015-05-29 14:26:24.573322	100
3421	Asigna - Edita	63	Edición de Asignaciones	S	2015-05-29 14:26:24.573322	100
3422	Asigna - Alta	63	Alta de Asignaciones	S	2015-05-29 14:26:24.573322	100
3423	Asigna - Baja	63	Baja de Asignaciones	S	2015-05-29 14:26:24.573322	100
3424	Eximisiones - Cons	63	Consulta de Eximisiones	S	2015-05-29 14:26:24.573322	100
3425	Eximisiones - Edita	63	Edición de Eximisiones	S	2015-05-29 14:26:24.573322	100
3426	Eximisiones - Alta	63	Alta de Eximisiones	S	2015-05-29 14:26:24.573322	100
3427	Eximisiones - Baja	63	Baja de Eximisiones	S	2015-05-29 14:26:24.573322	100
3428	Eximisiones - Aux Edita	63	Edición de Auxiliares de Eximisiones	S	2015-05-29 14:26:24.573322	100
3429	Eximisiones - Aprobar	63	Aprobar de solicitud de eximisión	S	2015-05-29 14:26:24.573322	100
3440	Facilidad - Cons.	64	Consulta de Facilidades de Pago	S	2015-05-29 14:26:24.573322	100
3441	Facilidad - Alta	64	Alta de Facilidades de Pago	S	2015-05-29 14:26:24.573322	100
3442	Facilidad - FchVencAnt	64	Elegir Fecha de Venc. Anterior Actual	S	2015-05-29 14:26:24.573322	100
3460	Debito - Cons	66	Consulta de Débito Automático	S	2015-05-29 14:26:24.573322	100
3461	Debito - Adhesión	66	Carga de Adhesiones a Débito Automático	S	2015-05-29 14:26:24.573322	100
3462	Debito - Edición	66	Edición de Débito Automático	S	2015-05-29 14:26:24.573322	100
3520	Oficina - Consulta	71	Consulta de Oficinas	S	2015-05-29 14:26:24.573322	100
3521	Oficina - Edición	71	Edición de Oficinas	S	2015-05-29 14:26:24.573322	100
1030	Pais-Prov-Loc - Consulta	72	Consulta Paises-Provincias-Localidades	S	2015-05-29 14:26:24.573322	100
1031	Pais-Prov-Loc - Edita	72	Editar Paises, Provincias y Localidades	S	2015-05-29 14:26:24.573322	100
3530	Textos - Consulta	73	Consulta de Textos	S	2015-05-29 14:26:24.573322	100
3531	Textos - Planes	73	Edita Textos de Planes	S	2015-05-29 14:26:24.573322	100
3532	Textos - Facilidades	73	Edita Textos de Facilidades	S	2015-05-29 14:26:24.573322	100
3533	Textos - Rescate Contrib.	73	Edita Textos de Rescates Contributivos	S	2015-05-29 14:26:24.573322	100
3534	Textos - Intima	73	Edita Textos de Intimaciones	S	2015-05-29 14:26:24.573322	100
3535	Textos - Emisión	73	Edita Textos de Emisión	S	2015-05-29 14:26:24.573322	100
3536	Textos - Judiciales	73	Edita Textos de Planillas Judiciales	S	2015-05-29 14:26:24.573322	100
3537	Textos - CtaCte	73	Edita Textos de Cuenta Corriente	S	2015-05-29 14:26:24.573322	100
3539	Textos - Libre Deuda	73	Edita Textos de Libre Deuda	S	2015-05-29 14:26:24.573322	100
3540	Textos - Mejoras	73	Edita Textos de Notificación de Mejoras	S	2015-05-29 14:26:24.573322	100
3541	Textos - Cementerio	73	Edita Textos de Contrato de Cementerio	S	2015-05-29 14:26:24.573322	100
3542	Textos - Fiscaliza	73	Edita Textos de Fiscalización	S	2015-05-29 14:26:24.573322	100
3543	Textos - Alquiler	73	Edita Textos para Alquiler	S	2015-05-29 14:26:24.573322	100
3544	Textos - Juzgado	73	Edita Textos para Juzgado	S	2015-05-29 14:26:24.573322	100
3545	Textos - DDJJ	73	Edita Textos para Declarac. Jurada	S	2015-05-29 14:26:24.573322	100
3418	Caja - Imprime	62	Impresión de reportes de caja	S	2015-06-03 07:20:18.168654	100
3077	Inmueble - Histórico	27	Consulta de Histórico	S	2015-05-29 14:26:24.573322	100
3386	Judicial - Aux Cons	58	Consulta Auxiliares Planillas Judiciales	S	2015-06-15 12:12:10	100
3384	Judicial - Aux Edita	58	Edición Auxiliar de Planillas Judiciales	S	2015-05-29 14:26:24.573322	100
3579	Caja - Anular Opera. Ant.	62	Permite anular operación de fecha ant.	N	2015-09-22 08:38:50	100
3605	Tabla Aux. - Cons	12	Consulta de Lista Tablas Auxiliares	S	2015-10-19 16:31:02	100
3497	Caja - Recibo Edita	62	Alta de Recibos	S	2015-05-29 14:26:24.573322	100
3408	Estad. Caja - Cons	61	Consulta de Estadísticas de Caja	S	2015-12-03 10:42:48.613838	100
3409	Estad. Caja - Imprime	61	Impresión de Estadísticas de Caja	S	2015-12-03 10:42:48.613838	100
3074	Inmueble - Denuncia Imp.	27	Denuncia Impositiva de Inmueble	S	2016-03-03 11:29:17.150039	100
3215	Comercio - Denuncia Imp.	28	Denuncia Impositiva de Comercio	S	2016-03-03 11:29:17.150039	100
3235	Cementerio - Denunc.Imp.	30	Denuncia Impositiva de Cementerio	S	2016-03-03 11:29:17.150039	100
3292	Rodado - Denuncia Imp.	34	Denuncia Impositiva de Rodado	S	2016-03-03 11:29:17.150039	100
3701	Usuario Web - Cons	89	Consultar usuarios web	S	2016-03-28 15:32:14.516451	100
3702	Usuario Web - Edita	89	Editar usuarios web	S	2016-03-28 15:32:14.516451	100
3703	Mail - Cons	80	Consultar mail enviados al municipio	S	2016-03-28 15:32:14.516451	100
3704	Mail - Resp	80	Responder mail enviados al municipio	S	2016-03-28 15:32:14.516451	100
3066	Objeto - Cert. Baja	26	Certificado de Baja de Objeto	S	2016-03-28 15:33:08.078715	100
3606	Persona - IB Edita	29	Inscripción en Ingresos Brutos	S	2016-08-25 05:56:39.305877	100
3607	Persona - IB Alta	29	Inscripción en Ingresos Brutos	S	2016-08-25 05:59:28.345648	100
3608	Persona - IB Baja	29	Baja en Ingresos Brutos	S	2016-08-25 05:59:28.446283	100
3609	Persona - IB Imprime	29	Impresión constancia Ingresos Brutos	S	2016-08-25 05:59:28.545276	100
3319	CtaCte-Edita Periodo	51	Permite editar un periodo	S	2017-02-15 13:55:30.8356	100
3106	Escribano - Consulta	27	Consulta de Gestión de Escribano	S	2017-05-18 14:32:34.409098	100
3107	Escribano - Edición	27	Edición de Gestión de Escribano	S	2017-05-18 14:32:34.5431	100
3547	Textos - Mail Emisión	52	Edita Textos para Mail de Emisión	S	2017-09-19 14:36:51.854827	100
3548	Textos - Legajo	73	Edita Textos para Legajos	S	2017-10-09 14:25:06.885343	100
\.


--
-- TOC entry 6006 (class 0 OID 5287232)
-- Dependencies: 634
-- Data for Name: sis_grupo_proceso; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.sis_grupo_proceso (gru_id, pro_id, fchmod, usrmod) FROM stdin;
1	3321	2017-11-24 09:55:11.950819	11
1	3322	2017-11-24 09:55:11.950819	11
1	3320	2017-11-24 09:55:11.950819	11
1	3323	2017-11-24 09:55:11.950819	11
1	3066	2017-11-24 09:55:11.950819	11
1	3063	2017-11-24 09:55:11.950819	11
1	3064	2017-11-24 09:55:11.950819	11
1	3065	2017-11-24 09:55:11.950819	11
1	3060	2017-11-24 09:55:11.950819	11
1	3061	2017-11-24 09:55:11.950819	11
1	3520	2017-11-24 09:55:11.950819	11
1	3521	2017-11-24 09:55:11.950819	11
1	3222	2017-11-24 09:55:11.950819	11
1	3224	2017-11-24 09:55:11.950819	11
1	3225	2017-11-24 09:55:11.950819	11
1	3223	2017-11-24 09:55:11.950819	11
1	3220	2017-11-24 09:55:11.950819	11
1	3603	2017-11-24 09:55:11.950819	11
1	3602	2017-11-24 09:55:11.950819	11
1	3221	2017-11-24 09:55:11.950819	11
1	3228	2017-11-24 09:55:11.950819	11
1	3604	2017-11-24 09:55:11.950819	11
1	3607	2017-11-24 09:55:11.950819	11
1	3608	2017-11-24 09:55:11.950819	11
1	3606	2017-11-24 09:55:11.950819	11
1	1040	2017-11-24 09:55:11.950819	11
1	1000	2017-11-24 09:55:11.950819	11
1	1001	2017-11-24 09:55:11.950819	11
1	1010	2017-11-24 09:55:11.950819	11
1	3605	2017-11-24 09:55:11.950819	11
1	1021	2017-11-24 09:55:11.950819	11
1	1024	2017-11-24 09:55:11.950819	11
1	1023	2017-11-24 09:55:11.950819	11
1	1022	2017-11-24 09:55:11.950819	11
1	1020	2017-11-24 09:55:11.950819	11
1	3703	2017-11-24 09:55:11.950819	11
1	3704	2017-11-24 09:55:11.950819	11
1	3701	2017-11-24 09:55:11.950819	11
1	3702	2017-11-24 09:55:11.950819	11
1	3052	2017-11-24 09:55:11.950819	11
1	3051	2017-11-24 09:55:11.950819	11
1	3055	2017-11-24 09:55:11.950819	11
1	3053	2017-11-24 09:55:11.950819	11
1	3054	2017-11-24 09:55:11.950819	11
1	3050	2017-11-24 09:55:11.950819	11
1	3030	2017-11-24 09:55:11.950819	11
1	3031	2017-11-24 09:55:11.950819	11
1	3450	2017-11-24 09:55:11.950819	11
1	3451	2017-11-24 09:55:11.950819	11
1	3416	2017-11-24 09:55:11.950819	11
1	3579	2017-11-24 09:55:11.950819	11
1	3415	2017-11-24 09:55:11.950819	11
1	3578	2017-11-24 09:55:11.950819	11
1	3413	2017-11-24 09:55:11.950819	11
1	3577	2017-11-24 09:55:11.950819	11
1	3575	2017-11-24 09:55:11.950819	11
1	3417	2017-11-24 09:55:11.950819	11
1	3412	2017-11-24 09:55:11.950819	11
1	3504	2017-11-24 09:55:11.950819	11
1	3414	2017-11-24 09:55:11.950819	11
1	3493	2017-11-24 09:55:11.950819	11
1	3418	2017-11-24 09:55:11.950819	11
1	3494	2017-11-24 09:55:11.950819	11
1	3419	2017-11-24 09:55:11.950819	11
1	3496	2017-11-24 09:55:11.950819	11
1	3497	2017-11-24 09:55:11.950819	11
1	3495	2017-11-24 09:55:11.950819	11
1	3572	2017-11-24 09:55:11.950819	11
1	3570	2017-11-24 09:55:11.950819	11
1	3576	2017-11-24 09:55:11.950819	11
1	3472	2017-11-24 09:55:11.950819	11
1	3473	2017-11-24 09:55:11.950819	11
1	3470	2017-11-24 09:55:11.950819	11
1	3471	2017-11-24 09:55:11.950819	11
1	3232	2017-11-24 09:55:11.950819	11
1	3234	2017-11-24 09:55:11.950819	11
1	3239	2017-11-24 09:55:11.950819	11
1	3240	2017-11-24 09:55:11.950819	11
1	3233	2017-11-24 09:55:11.950819	11
1	3230	2017-11-24 09:55:11.950819	11
1	3235	2017-11-24 09:55:11.950819	11
1	3243	2017-11-24 09:55:11.950819	11
1	3231	2017-11-24 09:55:11.950819	11
1	3242	2017-11-24 09:55:11.950819	11
1	3237	2017-11-24 09:55:11.950819	11
1	3238	2017-11-24 09:55:11.950819	11
1	3245	2017-11-24 09:55:11.950819	11
1	3244	2017-11-24 09:55:11.950819	11
1	3241	2017-11-24 09:55:11.950819	11
1	3236	2017-11-24 09:55:11.950819	11
1	3202	2017-11-24 09:55:11.950819	11
1	3210	2017-11-24 09:55:11.950819	11
1	3211	2017-11-24 09:55:11.950819	11
1	3203	2017-11-24 09:55:11.950819	11
1	3200	2017-11-24 09:55:11.950819	11
1	3204	2017-11-24 09:55:11.950819	11
1	3215	2017-11-24 09:55:11.950819	11
1	3205	2017-11-24 09:55:11.950819	11
1	3201	2017-11-24 09:55:11.950819	11
1	3219	2017-11-24 09:55:11.950819	11
1	3213	2017-11-24 09:55:11.950819	11
1	3214	2017-11-24 09:55:11.950819	11
1	3208	2017-11-24 09:55:11.950819	11
1	3209	2017-11-24 09:55:11.950819	11
1	3206	2017-11-24 09:55:11.950819	11
1	3218	2017-11-24 09:55:11.950819	11
1	3216	2017-11-24 09:55:11.950819	11
1	3217	2017-11-24 09:55:11.950819	11
1	3212	2017-11-24 09:55:11.950819	11
1	3207	2017-11-24 09:55:11.950819	11
1	3502	2017-11-24 09:55:11.950819	11
1	3500	2017-11-24 09:55:11.950819	11
1	3501	2017-11-24 09:55:11.950819	11
1	3430	2017-11-24 09:55:11.950819	11
1	3303	2017-11-24 09:55:11.950819	11
1	3308	2017-11-24 09:55:11.950819	11
1	3305	2017-11-24 09:55:11.950819	11
1	3306	2017-11-24 09:55:11.950819	11
1	3315	2017-11-24 09:55:11.950819	11
1	3317	2017-11-24 09:55:11.950819	11
1	3300	2017-11-24 09:55:11.950819	11
1	3319	2017-11-24 09:55:11.950819	11
1	3307	2017-11-24 09:55:11.950819	11
1	3301	2017-11-24 09:55:11.950819	11
1	3433	2017-11-24 09:55:11.950819	11
1	3309	2017-11-24 09:55:11.950819	11
1	3316	2017-11-24 09:55:11.950819	11
1	3318	2017-11-24 09:55:11.950819	11
1	3302	2017-11-24 09:55:11.950819	11
1	3432	2017-11-24 09:55:11.950819	11
1	3359	2017-11-24 09:55:11.950819	11
1	3358	2017-11-24 09:55:11.950819	11
1	3331	2017-11-24 09:55:11.950819	11
1	3335	2017-11-24 09:55:11.950819	11
1	3332	2017-11-24 09:55:11.950819	11
1	3330	2017-11-24 09:55:11.950819	11
1	3336	2017-11-24 09:55:11.950819	11
1	3333	2017-11-24 09:55:11.950819	11
1	3334	2017-11-24 09:55:11.950819	11
1	3461	2017-11-24 09:55:11.950819	11
1	3460	2017-11-24 09:55:11.950819	11
1	3462	2017-11-24 09:55:11.950819	11
1	1030	2017-11-24 09:55:11.950819	11
1	1031	2017-11-24 09:55:11.950819	11
1	3313	2017-11-24 09:55:11.950819	11
1	3312	2017-11-24 09:55:11.950819	11
1	3435	2017-11-24 09:55:11.950819	11
1	3310	2017-11-24 09:55:11.950819	11
1	3311	2017-11-24 09:55:11.950819	11
1	3314	2017-11-24 09:55:11.950819	11
1	3434	2017-11-24 09:55:11.950819	11
1	3546	2017-11-24 09:55:11.950819	11
1	3547	2017-11-24 09:55:11.950819	11
1	3408	2017-11-24 09:55:11.950819	11
1	3409	2017-11-24 09:55:11.950819	11
1	3410	2017-11-24 09:55:11.950819	11
1	3411	2017-11-24 09:55:11.950819	11
1	3441	2017-11-24 09:55:11.950819	11
1	3440	2017-11-24 09:55:11.950819	11
1	3442	2017-11-24 09:55:11.950819	11
1	3372	2017-11-24 09:55:11.950819	11
1	3373	2017-11-24 09:55:11.950819	11
1	3370	2017-11-24 09:55:11.950819	11
1	3371	2017-11-24 09:55:11.950819	11
1	3106	2017-11-24 09:55:11.950819	11
1	3107	2017-11-24 09:55:11.950819	11
1	3072	2017-11-24 09:55:11.950819	11
1	3080	2017-11-24 09:55:11.950819	11
1	3081	2017-11-24 09:55:11.950819	11
1	3073	2017-11-24 09:55:11.950819	11
1	3070	2017-11-24 09:55:11.950819	11
1	3074	2017-11-24 09:55:11.950819	11
1	3076	2017-11-24 09:55:11.950819	11
1	3075	2017-11-24 09:55:11.950819	11
1	3071	2017-11-24 09:55:11.950819	11
1	3094	2017-11-24 09:55:11.950819	11
1	3093	2017-11-24 09:55:11.950819	11
1	3083	2017-11-24 09:55:11.950819	11
1	3077	2017-11-24 09:55:11.950819	11
1	3079	2017-11-24 09:55:11.950819	11
1	3105	2017-11-24 09:55:11.950819	11
1	3092	2017-11-24 09:55:11.950819	11
1	3104	2017-11-24 09:55:11.950819	11
1	3082	2017-11-24 09:55:11.950819	11
1	3078	2017-11-24 09:55:11.950819	11
1	3381	2017-11-24 09:55:11.950819	11
1	3386	2017-11-24 09:55:11.950819	11
1	3384	2017-11-24 09:55:11.950819	11
1	3382	2017-11-24 09:55:11.950819	11
1	3380	2017-11-24 09:55:11.950819	11
1	3385	2017-11-24 09:55:11.950819	11
1	3383	2017-11-24 09:55:11.950819	11
1	3609	2017-11-24 09:55:11.950819	11
1	3229	2017-11-24 09:55:11.950819	11
1	3601	2017-11-24 09:55:11.950819	11
1	3227	2017-11-24 09:55:11.950819	11
1	3436	2017-11-24 09:55:11.950819	11
1	3341	2017-11-24 09:55:11.950819	11
1	3342	2017-11-24 09:55:11.950819	11
1	3353	2017-11-24 09:55:11.950819	11
1	3347	2017-11-24 09:55:11.950819	11
1	3349	2017-11-24 09:55:11.950819	11
1	3345	2017-11-24 09:55:11.950819	11
1	3350	2017-11-24 09:55:11.950819	11
1	3351	2017-11-24 09:55:11.950819	11
1	3340	2017-11-24 09:55:11.950819	11
1	3354	2017-11-24 09:55:11.950819	11
1	3344	2017-11-24 09:55:11.950819	11
1	3343	2017-11-24 09:55:11.950819	11
1	3040	2017-11-24 09:55:11.950819	11
1	3041	2017-11-24 09:55:11.950819	11
1	3282	2017-11-24 09:55:11.950819	11
1	3285	2017-11-24 09:55:11.950819	11
1	3286	2017-11-24 09:55:11.950819	11
1	3283	2017-11-24 09:55:11.950819	11
1	3291	2017-11-24 09:55:11.950819	11
1	3280	2017-11-24 09:55:11.950819	11
1	3292	2017-11-24 09:55:11.950819	11
1	3289	2017-11-24 09:55:11.950819	11
1	3281	2017-11-24 09:55:11.950819	11
1	3288	2017-11-24 09:55:11.950819	11
1	3284	2017-11-24 09:55:11.950819	11
1	3290	2017-11-24 09:55:11.950819	11
1	3287	2017-11-24 09:55:11.950819	11
1	3543	2017-11-24 09:55:11.950819	11
1	3541	2017-11-24 09:55:11.950819	11
1	3530	2017-11-24 09:55:11.950819	11
1	3537	2017-11-24 09:55:11.950819	11
1	3545	2017-11-24 09:55:11.950819	11
1	3535	2017-11-24 09:55:11.950819	11
1	3532	2017-11-24 09:55:11.950819	11
1	3542	2017-11-24 09:55:11.950819	11
1	3534	2017-11-24 09:55:11.950819	11
1	3536	2017-11-24 09:55:11.950819	11
1	3544	2017-11-24 09:55:11.950819	11
1	3548	2017-11-24 09:55:11.950819	11
1	3539	2017-11-24 09:55:11.950819	11
1	3540	2017-11-24 09:55:11.950819	11
1	3538	2017-11-24 09:55:11.950819	11
1	3531	2017-11-24 09:55:11.950819	11
1	3533	2017-11-24 09:55:11.950819	11
1	3422	2017-11-24 09:55:11.950819	11
1	3423	2017-11-24 09:55:11.950819	11
1	3420	2017-11-24 09:55:11.950819	11
1	3421	2017-11-24 09:55:11.950819	11
1	3426	2017-11-24 09:55:11.950819	11
1	3429	2017-11-24 09:55:11.950819	11
1	3428	2017-11-24 09:55:11.950819	11
1	3427	2017-11-24 09:55:11.950819	11
1	3424	2017-11-24 09:55:11.950819	11
1	3425	2017-11-24 09:55:11.950819	11
1	3012	2017-11-24 09:55:11.950819	11
1	3013	2017-11-24 09:55:11.950819	11
1	3014	2017-11-24 09:55:11.950819	11
1	3018	2017-11-24 09:55:11.950819	11
1	3019	2017-11-24 09:55:11.950819	11
1	3010	2017-11-24 09:55:11.950819	11
1	3011	2017-11-24 09:55:11.950819	11
1	3016	2017-11-24 09:55:11.950819	11
1	3017	2017-11-24 09:55:11.950819	11
\.


--
-- TOC entry 6009 (class 0 OID 5287244)
-- Dependencies: 637
-- Data for Name: sis_proceso_accion; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.sis_proceso_accion (pro_id, accion, fchmod, usrmod) FROM stdin;
3415	caja-cajacobro-apertura	2018-02-16 09:48:53.190632	100
3504	caja-cajacobro-comprobante	2018-02-16 09:48:53.190632	100
3578	caja-cajacobro-grabararqueo	2018-02-16 09:48:53.190632	100
3412	caja-cajacobro-imprimirarqueo	2018-02-16 09:48:53.190632	100
3418	caja-cajacobro-imprimirmdpe	2018-02-16 09:48:53.190632	100
3504	caja-cajacobro-mdpespecial	2018-02-16 09:48:53.190632	100
3413	caja-cajacobro-view	2018-02-16 09:48:53.190632	100
3417	caja-caja-create	2018-02-16 09:48:53.190632	100
3417	caja-caja-delete	2018-02-16 09:48:53.190632	100
3575	caja-caja-index	2018-02-16 09:48:53.190632	100
3416	caja-cajaticket-anula	2018-02-16 09:48:53.190632	100
3415	caja-cajaticket-apertcie	2018-02-16 09:48:53.190632	100
3504	caja-cajaticket-buscar	2018-02-16 09:48:53.190632	100
3577	caja-cajaticket-chequecartera	2018-02-16 09:48:53.190632	100
3497	caja-cajaticket-createrecibomanual	2018-02-16 09:48:53.190632	100
3497	caja-cajaticket-deleterecibomanual	2018-02-16 09:48:53.190632	100
3412	caja-cajaticket-estadocaja	2018-02-16 09:48:53.190632	100
3496	caja-cajaticket-imprimirrecibomanual	2018-02-16 09:48:53.190632	100
3412	caja-cajaticket-listado	2018-02-16 09:48:53.190632	100
3577	caja-cajaticket-listchequecartera	2018-02-16 09:48:53.190632	100
3412	caja-cajaticket-list_op	2018-02-16 09:48:53.190632	100
3494	caja-cajaticket-listregpagoant	2018-02-16 09:48:53.190632	100
3504	caja-cajaticket-opera	2018-02-16 09:48:53.190632	100
3494	caja-cajaticket-pagoant	2018-02-16 09:48:53.190632	100
3419	caja-cajaticket-prueba	2018-02-16 09:48:53.190632	100
3496	caja-cajaticket-recibomanual_list	2018-02-16 09:48:53.190632	100
3496	caja-cajaticket-recibomanualresult	2018-02-16 09:48:53.190632	100
3415	caja-cajaticket-supervision	2018-02-16 09:48:53.190632	100
3504	caja-cajaticket-ticket	2018-02-16 09:48:53.190632	100
3497	caja-cajaticket-updaterecibomanual	2018-02-16 09:48:53.190632	100
3496	caja-cajaticket-viewrecibomanual	2018-02-16 09:48:53.190632	100
3417	caja-caja-update	2018-02-16 09:48:53.190632	100
3075	caja-caja-view	2018-02-16 09:48:53.190632	100
3461	caja-debito-adhesion	2018-02-16 09:48:53.190632	100
3461	caja-debito-certificado	2018-02-16 09:48:53.190632	100
3460	caja-debito-debitocons	2018-02-16 09:48:53.190632	100
3460	caja-debito-exportar	2018-02-16 09:48:53.190632	100
3460	Caja-debito-generarreporte	2018-02-16 09:48:53.190632	100
3461	caja-debito-imprimiradhe	2018-02-16 09:48:53.190632	100
3460	caja-debito-liquida	2018-02-16 09:48:53.190632	100
3460	caja-debito-liquidacion	2018-02-16 09:48:53.190632	100
3462	caja-debito-view	2018-02-16 09:48:53.190632	100
3460	caja-debito-view	2018-02-16 09:48:53.190632	100
3417	config-bancocuenta-bancocuentaabm	2018-02-16 09:48:53.190632	100
3417	config-bancocuenta-index	2018-02-16 09:48:53.190632	100
3240	config-cemfalltserv-cemfalltservabm	2018-02-16 09:48:53.190632	100
3240	config-cemfalltserv-delete	2018-02-16 09:48:53.190632	100
3239	config-cemfalltserv-index	2018-02-16 09:48:53.190632	100
3239	config-cemfalltserv-view	2018-02-16 09:48:53.190632	100
3239	config-cemnom-index	2018-02-16 09:48:53.190632	100
3240	config-cemnom-update	2018-02-16 09:48:53.190632	100
3240	config-cemtalq-cemalqabm	2018-02-16 09:48:53.190632	100
3239	config-cemtalq-index	2018-02-16 09:48:53.190632	100
3050	config-config-index	2018-02-16 09:48:53.190632	100
3054	config-config-modificarconfig	2018-02-16 09:48:53.190632	100
3041	config-cuenta-eliminacuenta	2018-02-16 09:48:53.190632	100
3040	config-cuenta-indexcuenta	2018-02-16 09:48:53.190632	100
3054	config-feriado-create	2018-02-16 09:48:53.190632	100
3054	config-feriado-delete	2018-02-16 09:48:53.190632	100
3050	config-feriado-index	2018-02-16 09:48:53.190632	100
3054	config-feriado-update	2018-02-16 09:48:53.190632	100
3080	config-inmnom-index	2018-02-16 09:48:53.190632	100
3081	config-inmnom-update	2018-02-16 09:48:53.190632	100
3080	config-inmsecciones-grabarseccion	2018-02-16 09:48:53.190632	100
3080	config-inmsecciones-index	2018-02-16 09:48:53.190632	100
1030	config-localidad-index	2018-02-16 09:48:53.190632	100
1031	config-localidad-index	2018-02-16 09:48:53.190632	100
1001	config-muni-imagen	2018-02-16 09:48:53.190632	100
1000	config-muni-index	2018-02-16 09:48:53.190632	100
1001	config-muni-update	2018-02-16 09:48:53.190632	100
3063	config-objetotaccion-index	2018-02-16 09:48:53.190632	100
3063	config-objetotaccion-objetotaccionabm	2018-02-16 09:48:53.190632	100
3064	config-objetotbaja-index	2018-02-16 09:48:53.190632	100
3065	config-objetotbaja-objetotbajaabm	2018-02-16 09:48:53.190632	100
3060	config-objetotipo-index	2018-02-16 09:48:53.190632	100
3286	config-rodadoval-create	2018-02-16 09:48:53.190632	100
3286	config-rodadoval-delete	2018-02-16 09:48:53.190632	100
3286	config-rodadoval-update	2018-02-16 09:48:53.190632	100
3285	config-rodadoval-valor	2018-02-16 09:48:53.190632	100
3031	config-rubro-create	2018-02-16 09:48:53.190632	100
3031	config-rubro-delete	2018-02-16 09:48:53.190632	100
3030	config-rubro-index	2018-02-16 09:48:53.190632	100
3030	config-rubro-index_vig_general	2018-02-16 09:48:53.190632	100
3031	config-rubro-update	2018-02-16 09:48:53.190632	100
3030	config-rubro-view	2018-02-16 09:48:53.190632	100
3031	config-rubrovigencia-create	2018-02-16 09:48:53.190632	100
3031	config-rubrovigencia-delete	2018-02-16 09:48:53.190632	100
3030	config-rubrovigencia-index	2018-02-16 09:48:53.190632	100
3031	config-rubrovigencia-update	2018-02-16 09:48:53.190632	100
3030	config-rubrovigencia-view	2018-02-16 09:48:53.190632	100
3030	config-rubro-vig_general	2018-02-16 09:48:53.190632	100
3542	config-texto-create	2018-02-16 09:48:53.190632	100
3541	config-texto-create	2018-02-16 09:48:53.190632	100
3539	config-texto-create	2018-02-16 09:48:53.190632	100
3544	config-texto-create	2018-02-16 09:48:53.190632	100
3536	config-texto-create	2018-02-16 09:48:53.190632	100
3534	config-texto-create	2018-02-16 09:48:53.190632	100
3532	config-texto-create	2018-02-16 09:48:53.190632	100
3535	config-texto-create	2018-02-16 09:48:53.190632	100
3545	config-texto-create	2018-02-16 09:48:53.190632	100
3537	config-texto-create	2018-02-16 09:48:53.190632	100
3546	config-texto-create	2018-02-16 09:48:53.190632	100
3543	config-texto-create	2018-02-16 09:48:53.190632	100
3540	config-texto-create	2018-02-16 09:48:53.190632	100
3538	config-texto-create	2018-02-16 09:48:53.190632	100
3531	config-texto-create	2018-02-16 09:48:53.190632	100
3533	config-texto-create	2018-02-16 09:48:53.190632	100
3540	config-texto-delete	2018-02-16 09:48:53.190632	100
3538	config-texto-delete	2018-02-16 09:48:53.190632	100
3546	config-texto-delete	2018-02-16 09:48:53.190632	100
3531	config-texto-delete	2018-02-16 09:48:53.190632	100
3543	config-texto-delete	2018-02-16 09:48:53.190632	100
3533	config-texto-delete	2018-02-16 09:48:53.190632	100
3534	config-texto-delete	2018-02-16 09:48:53.190632	100
3542	config-texto-delete	2018-02-16 09:48:53.190632	100
3532	config-texto-delete	2018-02-16 09:48:53.190632	100
3535	config-texto-delete	2018-02-16 09:48:53.190632	100
3545	config-texto-delete	2018-02-16 09:48:53.190632	100
3537	config-texto-delete	2018-02-16 09:48:53.190632	100
3544	config-texto-delete	2018-02-16 09:48:53.190632	100
3536	config-texto-delete	2018-02-16 09:48:53.190632	100
3539	config-texto-delete	2018-02-16 09:48:53.190632	100
3541	config-texto-delete	2018-02-16 09:48:53.190632	100
3530	config-texto-index	2018-02-16 09:48:53.190632	100
3540	config-texto-update	2018-02-16 09:48:53.190632	100
3546	config-texto-update	2018-02-16 09:48:53.190632	100
3543	config-texto-update	2018-02-16 09:48:53.190632	100
3541	config-texto-update	2018-02-16 09:48:53.190632	100
3537	config-texto-update	2018-02-16 09:48:53.190632	100
3545	config-texto-update	2018-02-16 09:48:53.190632	100
3535	config-texto-update	2018-02-16 09:48:53.190632	100
3532	config-texto-update	2018-02-16 09:48:53.190632	100
3542	config-texto-update	2018-02-16 09:48:53.190632	100
3534	config-texto-update	2018-02-16 09:48:53.190632	100
3536	config-texto-update	2018-02-16 09:48:53.190632	100
3544	config-texto-update	2018-02-16 09:48:53.190632	100
3539	config-texto-update	2018-02-16 09:48:53.190632	100
3538	config-texto-update	2018-02-16 09:48:53.190632	100
3531	config-texto-update	2018-02-16 09:48:53.190632	100
3533	config-texto-update	2018-02-16 09:48:53.190632	100
3530	config-texto-view	2018-02-16 09:48:53.190632	100
3092	config-valcoefmej-index	2018-02-16 09:48:53.190632	100
3094	config-valcoefmej-valcoefmejabm	2018-02-16 09:48:53.190632	100
3092	config-valmej-delete	2018-02-16 09:48:53.190632	100
3092	config-valmej-index	2018-02-16 09:48:53.190632	100
3092	config-valmej-update	2018-02-16 09:48:53.190632	100
3094	config-valmej-valmejabm	2018-02-16 09:48:53.190632	100
3092	config-valmej-view	2018-02-16 09:48:53.190632	100
3030	config-vigenciageneral-index	2018-02-16 09:48:53.190632	100
3030	config-vigenciageneral-index_vig_general	2018-02-16 09:48:53.190632	100
3030	config-vigenciageneral-view	2018-02-16 09:48:53.190632	100
3031	config-vigenciageneral-viggeneralabm	2018-02-16 09:48:53.190632	100
3303	ctacte-ajustes-cargar	2018-02-16 09:48:53.190632	100
3308	ctacte-ajustes-create	2018-02-16 09:48:53.190632	100
3430	ctacte-ajustes-delete	2018-02-16 09:48:53.190632	100
3430	ctacte-ajustes-deletecuenta	2018-02-16 09:48:53.190632	100
3303	ctacte-ajustes-imprimir	2018-02-16 09:48:53.190632	100
3303	ctacte-ajustes-listado	2018-02-16 09:48:53.190632	100
3308	ctacte-ajustes-nuevacuenta	2018-02-16 09:48:53.190632	100
3303	ctacte-ajustes-view	2018-02-16 09:48:53.190632	100
3052	ctacte-calcdesc-create	2018-02-16 09:48:53.190632	100
3052	ctacte-calcdesc-delete	2018-02-16 09:48:53.190632	100
3052	ctacte-calcdesc-index	2018-02-16 09:48:53.190632	100
3052	ctacte-calcdesc-update	2018-02-16 09:48:53.190632	100
3051	ctacte-calcinteres-create	2018-02-16 09:48:53.190632	100
3051	ctacte-calcinteres-delete	2018-02-16 09:48:53.190632	100
3051	ctacte-calcinteres-index	2018-02-16 09:48:53.190632	100
3051	ctacte-calcinteres-update	2018-02-16 09:48:53.190632	100
3055	ctacte-calcmm-create	2018-02-16 09:48:53.190632	100
3055	ctacte-calcmm-delete	2018-02-16 09:48:53.190632	100
3055	ctacte-calcmm-index	2018-02-16 09:48:53.190632	100
3055	ctacte-calcmm-update	2018-02-16 09:48:53.190632	100
3053	ctacte-calcmulta-create	2018-02-16 09:48:53.190632	100
3053	ctacte-calcmulta-delete	2018-02-16 09:48:53.190632	100
3053	ctacte-calcmulta-index	2018-02-16 09:48:53.190632	100
3053	ctacte-calcmulta-update	2018-02-16 09:48:53.190632	100
3315	ctacte-cestado-view	2018-02-16 09:48:53.190632	100
3500	ctacte-comp-buscar	2018-02-16 09:48:53.190632	100
3501	ctacte-comp-create	2018-02-16 09:48:53.190632	100
3502	ctacte-comp-delete	2018-02-16 09:48:53.190632	100
3500	ctacte-comp-imprimir	2018-02-16 09:48:53.190632	100
3500	ctacte-comp-listado	2018-02-16 09:48:53.190632	100
3500	ctacte-listadocomp-index	2018-02-16 09:48:53.190632	100
3501	ctacte-comp-update	2018-02-16 09:48:53.190632	100
3500	ctacte-comp-view	2018-02-16 09:48:53.190632	100
3345	ctacte-convenio-anulaimputadecaeplan	2018-02-16 09:48:53.190632	100
3341	ctacte-convenio-borrarplan	2018-02-16 09:48:53.190632	100
3340	ctacte-convenio-buscarplan	2018-02-16 09:48:53.190632	100
3350	ctacte-convenio-configdecae	2018-02-16 09:48:53.190632	100
3350	ctacte-convenio-configusr	2018-02-16 09:48:53.190632	100
3344	ctacte-convenio-decaerplan	2018-02-16 09:48:53.190632	100
3354	ctacte-convenio-editarvenccuota	2018-02-16 09:48:53.190632	100
3341	ctacte-convenio-eliminaradelantacuota	2018-02-16 09:48:53.190632	100
3340	ctacte-convenio-imprimircomprobante	2018-02-16 09:48:53.190632	100
3340	ctacte-convenio-imprimircomprobantevalida	2018-02-16 09:48:53.190632	100
3340	ctacte-convenio-imprimircontrato	2018-02-16 09:48:53.190632	100
3340	ctacte-convenio-imprimircontratopdf	2018-02-16 09:48:53.190632	100
3340	ctacte-convenio-imprimircuotasplanant	2018-02-16 09:48:53.190632	100
3340	ctacte-convenio-imprimirpreliminarperidos	2018-02-16 09:48:53.190632	100
3340	ctacte-convenio-imprimirresumen	2018-02-16 09:48:53.190632	100
3343	ctacte-convenio-imputarplan	2018-02-16 09:48:53.190632	100
3340	ctacte-convenio-list_op	2018-02-16 09:48:53.190632	100
3340	ctacte-convenio-list_res	2018-02-16 09:48:53.190632	100
3358	ctacte-convenio-modificarplan	2018-02-16 09:48:53.190632	100
3340	ctacte-convenio-plan	2018-02-16 09:48:53.190632	100
3341	ctacte-convenio-plannuevo	2018-02-16 09:48:53.190632	100
3349	ctacte-convenio-plannuevoant	2018-02-16 09:48:53.190632	100
3349	ctacte-convenio-plannuevoantgrabar	2018-02-16 09:48:53.190632	100
3341	ctacte-convenio-plannuevograbar	2018-02-16 09:48:53.190632	100
3300	ctacte-ctacte-aplicar	2018-02-16 09:48:53.190632	100
3433	ctacte-ctacte-constanciapago	2018-02-16 09:48:53.190632	100
3300	ctacte-ctacte-ctactedet	2018-02-16 09:48:53.190632	100
3301	ctacte-ctacte-cuponpago	2018-02-16 09:48:53.190632	100
3334	ctacte-ctacte-ddjjfaltante	2018-02-16 09:48:53.190632	100
3319	ctacte-ctacte-editarperiodo	2018-02-16 09:48:53.190632	100
3308	ctacte-ctacte-editarperiodo	2018-02-16 09:48:53.190632	100
3312	ctacte-ctacte-eliminarliq	2018-02-16 09:48:53.190632	100
3300	ctacte-ctacte-exportarcompleto	2018-02-16 09:48:53.190632	100
3300	ctacte-ctacte-exportarresumen	2018-02-16 09:48:53.190632	100
3441	ctacte-ctacte-generaracilida	2018-02-16 09:48:53.190632	100
3300	ctacte-ctacte-imprimircompleto	2018-02-16 09:48:53.190632	100
3433	ctacte-ctacte-imprimircomprobante	2018-02-16 09:48:53.190632	100
3300	ctacte-ctacte-imprimirlistper	2018-02-16 09:48:53.190632	100
3300	ctacte-ctacte-imprimirperimpagos	2018-02-16 09:48:53.190632	100
3301	ctacte-ctacte-imprimirresumen	2018-02-16 09:48:53.190632	100
3300	ctacte-ctacte-index	2018-02-16 09:48:53.190632	100
3340	ctacte-ctacte-irplan	2018-02-16 09:48:53.190632	100
3440	ctacte-ctacte-irplan	2018-02-16 09:48:53.190632	100
3341	ctacte-ctacte-plannuevo	2018-02-16 09:48:53.190632	100
3314	ctacte-ctacte-reliquidar	2018-02-16 09:48:53.190632	100
3300	ctacte-ctacte-topera	2018-02-16 09:48:53.190632	100
3331	ctacte-ddjj-agregaranual	2018-02-16 09:48:53.190632	100
3332	ctacte-ddjj-borrar	2018-02-16 09:48:53.190632	100
3330	ctacte-ddjj-buscar	2018-02-16 09:48:53.190632	100
3330	ctacte-ddjj-cargarrete	2018-02-16 09:48:53.190632	100
3330	ctacte-ddjj-compara	2018-02-16 09:48:53.190632	100
3330	ctacte-ddjj-ddjjanual	2018-02-16 09:48:53.190632	100
3331	ctacte-ddjj-generaranual	2018-02-16 09:48:53.190632	100
3330	ctacte-ddjj-imprimir	2018-02-16 09:48:53.190632	100
3330	ctacte-ddjj-index	2018-02-16 09:48:53.190632	100
3330	ctacte-ddjj-listadj	2018-02-16 09:48:53.190632	100
3330	ctacte-ddjj-listado	2018-02-16 09:48:53.190632	100
3330	ctacte-ddjj-list_res	2018-02-16 09:48:53.190632	100
3330	ctacte-ddjj-view	2018-02-16 09:48:53.190632	100
3441	ctacte-facilida-activar	2018-02-16 09:48:53.190632	100
3441	ctacte-facilida-baja	2018-02-16 09:48:53.190632	100
3441	ctacte-facilida-bajavencida	2018-02-16 09:48:53.190632	100
3440	ctacte-facilida-buscar	2018-02-16 09:48:53.190632	100
3440	ctacte-facilida-imprimir	2018-02-16 09:48:53.190632	100
3440	ctacte-facilida-listado	2018-02-16 09:48:53.190632	100
3440	ctacte-facilida-list_res	2018-02-16 09:48:53.190632	100
3441	ctacte-facilida-view	2018-02-16 09:48:53.190632	100
3440	ctacte-facilida-view	2018-02-16 09:48:53.190632	100
3370	ctacte-fiscaliza-buscar	2018-02-16 09:48:53.190632	100
3372	ctacte-fiscaliza-create	2018-02-16 09:48:53.190632	100
3371	ctacte-fiscaliza-delete	2018-02-16 09:48:53.190632	100
3370	ctacte-fiscaliza-imprimir	2018-02-16 09:48:53.190632	100
3370	ctacte-fiscaliza-imprimirformulario	2018-02-16 09:48:53.190632	100
3370	ctacte-fiscaliza-listado	2018-02-16 09:48:53.190632	100
3370	ctacte-fiscaliza-list_res	2018-02-16 09:48:53.190632	100
3371	ctacte-fiscaliza-update	2018-02-16 09:48:53.190632	100
3370	ctacte-fiscaliza-view	2018-02-16 09:48:53.190632	100
3011	ctacte-item-create	2018-02-16 09:48:53.190632	100
3011	ctacte-item-delete	2018-02-16 09:48:53.190632	100
3010	ctacte-item-index	2018-02-16 09:48:53.190632	100
3011	ctacte-item-update	2018-02-16 09:48:53.190632	100
3017	ctacte-itemvigencia-asoc	2018-02-16 09:48:53.190632	100
3017	ctacte-itemvigencia-asocdelete	2018-02-16 09:48:53.190632	100
3017	ctacte-itemvigencia-create	2018-02-16 09:48:53.190632	100
3017	ctacte-itemvigencia-delete	2018-02-16 09:48:53.190632	100
3017	ctacte-itemvigencia-update	2018-02-16 09:48:53.190632	100
3380	ctacte-judi-buscar	2018-02-16 09:48:53.190632	100
3381	ctacte-judi-create	2018-02-16 09:48:53.190632	100
3381	ctacte-judi-etapamas	2018-02-16 09:48:53.190632	100
3381	ctacte-judi-etapamenos	2018-02-16 09:48:53.190632	100
3385	ctacte-judi-imprimir	2018-02-16 09:48:53.190632	100
3385	ctacte-judi-imprimircertif	2018-02-16 09:48:53.190632	100
3385	ctacte-judi-imprimirexpe	2018-02-16 09:48:53.190632	100
3380	ctacte-judi-listado	2018-02-16 09:48:53.190632	100
3380	ctacte-judi-list_res	2018-02-16 09:48:53.190632	100
3381	ctacte-judi-updateobs	2018-02-16 09:48:53.190632	100
3380	ctacte-judi-view	2018-02-16 09:48:53.190632	100
3309	ctacte-libredeuda-accion	2018-02-16 09:48:53.190632	100
3309	ctacte-libredeuda-delete	2018-02-16 09:48:53.190632	100
3309	ctacte-libredeuda-imprimir	2018-02-16 09:48:53.190632	100
3309	ctacte-libredeuda-index	2018-02-16 09:48:53.190632	100
3309	ctacte-libredeuda-list_op	2018-02-16 09:48:53.190632	100
3320	ctacte-liquida-buscar	2018-02-16 09:48:53.190632	100
3322	ctacte-liquida-cancelar	2018-02-16 09:48:53.190632	100
3321	ctacte-liquida-create	2018-02-16 09:48:53.190632	100
3322	ctacte-liquida-delete	2018-02-16 09:48:53.190632	100
3320	ctacte-liquida-imprimircomprobante	2018-02-16 09:48:53.190632	100
3320	ctacte-liquida-list_op	2018-02-16 09:48:53.190632	100
3320	ctacte-liquida-list_res	2018-02-16 09:48:53.190632	100
3323	ctacte-liquida-update	2018-02-16 09:48:53.190632	100
3320	ctacte-liquida-view	2018-02-16 09:48:53.190632	100
3318	ctacte-pagocta-buscar	2018-02-16 09:48:53.190632	100
3318	ctacte-pagocta-create	2018-02-16 09:48:53.190632	100
3318	ctacte-pagocta-delete	2018-02-16 09:48:53.190632	100
3318	ctacte-pagocta-deletevencidas	2018-02-16 09:48:53.190632	100
3318	ctacte-pagocta-imprimir	2018-02-16 09:48:53.190632	100
3318	ctacte-pagocta-listado	2018-02-16 09:48:53.190632	100
3318	ctacte-pagocta-view	2018-02-16 09:48:53.190632	100
3351	ctacte-planconfig-create	2018-02-16 09:48:53.190632	100
3351	ctacte-planconfig-delete	2018-02-16 09:48:53.190632	100
3350	ctacte-planconfig-index	2018-02-16 09:48:53.190632	100
3351	ctacte-planconfig-update	2018-02-16 09:48:53.190632	100
3350	ctacte-planconfig-view	2018-02-16 09:48:53.190632	100
3013	ctacte-resol-create	2018-02-16 09:48:53.190632	100
3012	ctacte-resol-imprimir	2018-02-16 09:48:53.190632	100
3012	ctacte-resol-index	2018-02-16 09:48:53.190632	100
3013	ctacte-resolocal-create	2018-02-16 09:48:53.190632	100
3013	ctacte-resolocal-delete	2018-02-16 09:48:53.190632	100
3013	ctacte-resolocal-update	2018-02-16 09:48:53.190632	100
3012	ctacte-resoltablacol-view	2018-02-16 09:48:53.190632	100
3013	ctacte-resoltabla-create	2018-02-16 09:48:53.190632	100
3014	ctacte-resoltabladato-create	2018-02-16 09:48:53.190632	100
3014	ctacte-resoltabladato-delete	2018-02-16 09:48:53.190632	100
3014	ctacte-resoltabladato-update	2018-02-16 09:48:53.190632	100
3012	ctacte-resoltabladato-view	2018-02-16 09:48:53.190632	100
3013	ctacte-resoltabla-delete	2018-02-16 09:48:53.190632	100
3013	ctacte-resoltabla-update	2018-02-16 09:48:53.190632	100
3012	ctacte-resoltabla-view	2018-02-16 09:48:53.190632	100
3013	ctacte-resol-update	2018-02-16 09:48:53.190632	100
3012	ctacte-resol-view	2018-02-16 09:48:53.190632	100
3422	ctacte-tribacc-asig	2018-02-16 09:48:53.190632	100
3317	ctacte-tribacc-condona	2018-02-16 09:48:53.190632	100
3334	ctacte-tribacc-djfalt	2018-02-16 09:48:53.190632	100
3307	ctacte-tribacc-excep	2018-02-16 09:48:53.190632	100
3422	ctacte-tribacc-imprimirasig	2018-02-16 09:48:53.190632	100
3422	ctacte-tribacc-inscrip	2018-02-16 09:48:53.190632	100
3420	ctacte-tribacc-listado	2018-02-16 09:48:53.190632	100
3302	ctacte-tribacc-prescrip	2018-02-16 09:48:53.190632	100
3011	ctacte-trib-activar	2018-02-16 09:48:53.190632	100
3011	ctacte-trib-create	2018-02-16 09:48:53.190632	100
3011	ctacte-trib-delete	2018-02-16 09:48:53.190632	100
3010	ctacte-trib-imprimir	2018-02-16 09:48:53.190632	100
3010	ctacte-trib-index	2018-02-16 09:48:53.190632	100
3011	ctacte-trib-update	2018-02-16 09:48:53.190632	100
3017	ctacte-tribvenc-create	2018-02-16 09:48:53.190632	100
3017	ctacte-tribvenc-delete	2018-02-16 09:48:53.190632	100
3016	ctacte-tribvenc-index	2018-02-16 09:48:53.190632	100
3017	ctacte-tribvenc-update	2018-02-16 09:48:53.190632	100
3010	ctacte-trib-view	2018-02-16 09:48:53.190632	100
3450	estad-audit	2018-02-16 09:48:53.190632	100
3408	estad-caja	2018-02-16 09:48:53.190632	100
3418	estad-caja	2018-02-16 09:48:53.190632	100
3411	estad-exportar	2018-02-16 09:48:53.190632	100
3451	estad-exportar	2018-02-16 09:48:53.190632	100
3418	estad-exportar	2018-02-16 09:48:53.190632	100
3410	estad-general	2018-02-16 09:48:53.190632	100
3418	estad-imprimir	2018-02-16 09:48:53.190632	100
3411	estad-imprimir	2018-02-16 09:48:53.190632	100
3451	estad-imprimir	2018-02-16 09:48:53.190632	100
3548	legajo-leg-imprimircertificado	2018-02-16 09:48:53.190632	100
3234	objeto-cem-alquiler	2018-02-16 09:48:53.190632	100
3230	objeto-cem-buscar	2018-02-16 09:48:53.190632	100
3230	objeto-cem-buscarfall	2018-02-16 09:48:53.190632	100
3232	objeto-cem-create	2018-02-16 09:48:53.190632	100
3232	objeto-cem-createfall	2018-02-16 09:48:53.190632	100
3233	objeto-cem-delete	2018-02-16 09:48:53.190632	100
3233	objeto-cem-deletefall	2018-02-16 09:48:53.190632	100
3238	objeto-cem-imprimir	2018-02-16 09:48:53.190632	100
3238	objeto-cem-imprimirfall	2018-02-16 09:48:53.190632	100
3230	objeto-cem-listado	2018-02-16 09:48:53.190632	100
3230	objeto-cem-listadofall	2018-02-16 09:48:53.190632	100
3230	objeto-cem-listfall_res	2018-02-16 09:48:53.190632	100
3230	objeto-cem-list_res	2018-02-16 09:48:53.190632	100
3231	objeto-cem-servicios	2018-02-16 09:48:53.190632	100
3231	objeto-cem-traslado	2018-02-16 09:48:53.190632	100
3231	objeto-cem-update	2018-02-16 09:48:53.190632	100
3231	objeto-cem-updatefall	2018-02-16 09:48:53.190632	100
3230	objeto-cem-view	2018-02-16 09:48:53.190632	100
3230	objeto-cem-viewfall	2018-02-16 09:48:53.190632	100
3200	objeto-comer-buscar	2018-02-16 09:48:53.190632	100
3209	objeto-comer-constanciahabil	2018-02-16 09:48:53.190632	100
3209	objeto-comer-constanciaib	2018-02-16 09:48:53.190632	100
3202	objeto-comer-create	2018-02-16 09:48:53.190632	100
3330	objeto-comer-ddjj	2018-02-16 09:48:53.190632	100
3203	objeto-comer-delete	2018-02-16 09:48:53.190632	100
3204	objeto-comer-denominacion	2018-02-16 09:48:53.190632	100
3214	objeto-comer-habilitacion	2018-02-16 09:48:53.190632	100
3209	objeto-comer-imprimir	2018-02-16 09:48:53.190632	100
3200	objeto-comer-listado	2018-02-16 09:48:53.190632	100
3219	objeto-comer-rubro	2018-02-16 09:48:53.190632	100
3201	objeto-comer-update	2018-02-16 09:48:53.190632	100
3200	objeto-comer-view	2018-02-16 09:48:53.190632	100
3106	objeto-escribano-imprimir	2018-02-16 09:48:53.190632	100
3106	objeto-escribano-imprimirventa	2018-02-16 09:48:53.190632	100
3106	objeto-escribano-index	2018-02-16 09:48:53.190632	100
3107	objeto-escribano-informar	2018-02-16 09:48:53.190632	100
3106	objeto-escribano-venta	2018-02-16 09:48:53.190632	100
3105	objeto-inm-accion	2018-02-16 09:48:53.190632	100
3070	objeto-inm-buscar	2018-02-16 09:48:53.190632	100
3079	objeto-inm-certificadovaluacion	2018-02-16 09:48:53.190632	100
3071	objeto-inm-coef	2018-02-16 09:48:53.190632	100
3072	objeto-inm-create	2018-02-16 09:48:53.190632	100
3073	objeto-inm-delete	2018-02-16 09:48:53.190632	100
3079	objeto-inm-imprimir	2018-02-16 09:48:53.190632	100
3070	objeto-inm-irphmadre	2018-02-16 09:48:53.190632	100
3070	objeto-inm-listado	2018-02-16 09:48:53.190632	100
3071	objeto-inm-restricciones	2018-02-16 09:48:53.190632	100
3071	objeto-inm-restriccionesabm	2018-02-16 09:48:53.190632	100
3092	objeto-inm-revaluo	2018-02-16 09:48:53.190632	100
3071	objeto-inm-update	2018-02-16 09:48:53.190632	100
3070	objeto-inm-view	2018-02-16 09:48:53.190632	100
3218	objeto-objeto-accion	2018-02-16 09:48:53.190632	100
3601	objeto-objeto-accion	2018-02-16 09:48:53.190632	100
3245	objeto-objeto-accion	2018-02-16 09:48:53.190632	100
3290	objeto-objeto-accion	2018-02-16 09:48:53.190632	100
3202	objeto-objeto-activar	2018-02-16 09:48:53.190632	100
3072	objeto-objeto-activar	2018-02-16 09:48:53.190632	100
3210	objeto-objeto-auxedit	2018-02-16 09:48:53.190632	100
3243	objeto-objeto-cambiodomi	2018-02-16 09:48:53.190632	100
3205	objeto-objeto-cambiodomi	2018-02-16 09:48:53.190632	100
3075	objeto-objeto-cambiodomi	2018-02-16 09:48:53.190632	100
3289	objeto-objeto-cambiodomi	2018-02-16 09:48:53.190632	100
3076	objeto-objeto-cambiodomi	2018-02-16 09:48:53.190632	100
3066	objeto-objeto-certificadobaja	2018-02-16 09:48:53.190632	100
3074	objeto-objeto-denunciaimpositiva	2018-02-16 09:48:53.190632	100
3292	objeto-objeto-denunciaimpositiva	2018-02-16 09:48:53.190632	100
3235	objeto-objeto-denunciaimpositiva	2018-02-16 09:48:53.190632	100
3215	objeto-objeto-denunciaimpositiva	2018-02-16 09:48:53.190632	100
3228	objeto-objeto-estado	2018-02-16 09:48:53.190632	100
3213	objeto-objeto-estado	2018-02-16 09:48:53.190632	100
3082	objeto-objeto-estado	2018-02-16 09:48:53.190632	100
3083	objeto-objeto-estado	2018-02-16 09:48:53.190632	100
3242	objeto-objeto-estado	2018-02-16 09:48:53.190632	100
3288	objeto-objeto-estado	2018-02-16 09:48:53.190632	100
3241	objeto-objeto-estado	2018-02-16 09:48:53.190632	100
3212	objeto-objeto-estado	2018-02-16 09:48:53.190632	100
3284	objeto-objeto-historico	2018-02-16 09:48:53.190632	100
3604	objeto-objeto-historico	2018-02-16 09:48:53.190632	100
3077	objeto-objeto-historico	2018-02-16 09:48:53.190632	100
3208	objeto-objeto-historico	2018-02-16 09:48:53.190632	100
3237	objeto-objeto-historico	2018-02-16 09:48:53.190632	100
3220	objeto-objeto-miscelaneas	2018-02-16 09:48:53.190632	100
3070	objeto-objeto-miscelaneas	2018-02-16 09:48:53.190632	100
3222	objeto-objeto-miscelaneasabm	2018-02-16 09:48:53.190632	100
3071	objeto-objeto-miscelaneasabm	2018-02-16 09:48:53.190632	100
3104	objeto-objeto-transferencia	2018-02-16 09:48:53.190632	100
3217	objeto-objeto-transferencia	2018-02-16 09:48:53.190632	100
3244	objeto-objeto-transferencia	2018-02-16 09:48:53.190632	100
3287	objeto-objeto-transferencia	2018-02-16 09:48:53.190632	100
3070	objeto-objeto-vinculos	2018-02-16 09:48:53.190632	100
3206	objeto-objeto-vinculos	2018-02-16 09:48:53.190632	100
3606	objeto-persona-activarli	2018-02-16 09:48:53.190632	100
3221	objeto-persona-adjuntos	2018-02-16 09:48:53.190632	100
3703	objeto-persona-ajusteweb	2018-02-16 09:48:53.190632	100
3220	objeto-persona-buscar	2018-02-16 09:48:53.190632	100
3220	objeto-persona-codigocontador	2018-02-16 09:48:53.190632	100
3609	objeto-persona-constanciaib	2018-02-16 09:48:53.190632	100
3703	objeto-persona-consultaweb	2018-02-16 09:48:53.190632	100
3704	objeto-persona-consultawebedit	2018-02-16 09:48:53.190632	100
3222	objeto-persona-create	2018-02-16 09:48:53.190632	100
3223	objeto-persona-delete	2018-02-16 09:48:53.190632	100
3606	objeto-persona-exentoib	2018-02-16 09:48:53.190632	100
3607	objeto-persona-ib	2018-02-16 09:48:53.190632	100
3229	objeto-persona-imprimir	2018-02-16 09:48:53.190632	100
3220	objeto-persona-list_op	2018-02-16 09:48:53.190632	100
3220	objeto-persona-list_res	2018-02-16 09:48:53.190632	100
3227	objeto-persona-reemplaza	2018-02-16 09:48:53.190632	100
3227	objeto-persona-reemplazaanula	2018-02-16 09:48:53.190632	100
3220	objeto-persona-sugerenciacontador	2018-02-16 09:48:53.190632	100
3221	objeto-persona-update	2018-02-16 09:48:53.190632	100
3220	objeto-persona-view	2018-02-16 09:48:53.190632	100
3286	objeto-rodado-aforo	2018-02-16 09:48:53.190632	100
3280	objeto-rodado-buscar	2018-02-16 09:48:53.190632	100
3291	objeto-rodado-cambio	2018-02-16 09:48:53.190632	100
3282	objeto-rodado-create	2018-02-16 09:48:53.190632	100
3283	objeto-rodado-delete	2018-02-16 09:48:53.190632	100
3280	objeto-rodado-imprimir	2018-02-16 09:48:53.190632	100
3285	objeto-rodado-imprimiraforo	2018-02-16 09:48:53.190632	100
3280	objeto-rodado-listado	2018-02-16 09:48:53.190632	100
3280	objeto-rodado-list_res	2018-02-16 09:48:53.190632	100
3280	objeto-rodado-revisiontecnica	2018-02-16 09:48:53.190632	100
3281	objeto-rodado-update	2018-02-16 09:48:53.190632	100
3280	objeto-rodado-view	2018-02-16 09:48:53.190632	100
3511	reclamo-reclamo-create	2018-02-16 09:48:53.190632	100
3513	reclamo-reclamo-deletenovedad	2018-02-16 09:48:53.190632	100
3514	reclamo-reclamo-ejecucion	2018-02-16 09:48:53.190632	100
3510	reclamo-reclamo-estadistica	2018-02-16 09:48:53.190632	100
3511	reclamo-reclamo-generar	2018-02-16 09:48:53.190632	100
3510	reclamo-reclamo-imprimir	2018-02-16 09:48:53.190632	100
3510	reclamo-reclamo-index	2018-02-16 09:48:53.190632	100
3510	reclamo-reclamo-listado	2018-02-16 09:48:53.190632	100
3510	reclamo-reclamo-list_res	2018-02-16 09:48:53.190632	100
3514	reclamo-reclamo-noejecucion	2018-02-16 09:48:53.190632	100
3513	reclamo-reclamo-novedad	2018-02-16 09:48:53.190632	100
3518	reclamo-reclamotipo-create	2018-02-16 09:48:53.190632	100
3518	reclamo-reclamotipo-delete	2018-02-16 09:48:53.190632	100
3516	reclamo-reclamotipo-index	2018-02-16 09:48:53.190632	100
3518	reclamo-reclamotipo-update	2018-02-16 09:48:53.190632	100
3516	reclamo-reclamotipo-view	2018-02-16 09:48:53.190632	100
3519	reclamo-reclamo-update	2018-02-16 09:48:53.190632	100
3515	reclamo-reclamo-verificacion	2018-02-16 09:48:53.190632	100
3285	site-auxedit	2018-02-16 09:48:53.190632	100
3239	site-auxedit	2018-02-16 09:48:53.190632	100
3080	site-auxedit	2018-02-16 09:48:53.190632	100
3224	site-auxedit	2018-02-16 09:48:53.190632	100
3340	site-auxedit	2018-02-16 09:48:53.190632	100
3010	site-auxedit	2018-02-16 09:48:53.190632	100
3386	site-auxedit	2018-02-16 09:48:53.190632	100
3470	site-auxedit	2018-02-16 09:48:53.190632	100
3472	site-auxedit	2018-02-16 09:48:53.190632	100
3305	site-auxedit	2018-02-16 09:48:53.190632	100
1030	site-auxedit	2018-02-16 09:48:53.190632	100
3520	site-auxedit	2018-02-16 09:48:53.190632	100
3575	site-auxedit	2018-02-16 09:48:53.190632	100
3050	site-config	2018-02-16 09:48:53.190632	100
3605	site-taux	2018-02-16 09:48:53.190632	100
1023	usuario-usuario-acceso	2018-02-16 09:48:53.190632	100
1021	usuario-usuario-create	2018-02-16 09:48:53.190632	100
1021	usuario-usuario-grupocreate	2018-02-16 09:48:53.190632	100
1021	usuario-usuario-grupodelete	2018-02-16 09:48:53.190632	100
1021	usuario-usuario-grupoupdate	2018-02-16 09:48:53.190632	100
1020	usuario-usuario-index	2018-02-16 09:48:53.190632	100
1021	usuario-usuario-procesos	2018-02-16 09:48:53.190632	100
1020	usuario-usuario-procesosistema	2018-02-16 09:48:53.190632	100
1021	usuario-usuario-update	2018-02-16 09:48:53.190632	100
1020	usuario-usuario-usuariogrupo	2018-02-16 09:48:53.190632	100
1021	usuario-usuario-usuarioproceso	2018-02-16 09:48:53.190632	100
1020	usuario-usuario-view	2018-02-16 09:48:53.190632	100
1020	usuario-usuario-viewusuario	2018-02-16 09:48:53.190632	100
3701	usuarioweb-usuarioweb-comprobanteusrweb	2018-02-16 09:48:53.190632	100
3701	usuarioweb-usuarioweb-index	2018-02-16 09:48:53.190632	100
3702	usuarioweb-usuarioweb-limpiarclave	2018-02-16 09:48:53.190632	100
3701	usuarioweb-usuarioweb-view	2018-02-16 09:48:53.190632	100
3312	ctacte-ctacte-eliminarreliq	2018-02-16 09:54:18.362514	100
3031	config-rubro-actualizarmasiva	2018-02-16 09:48:58.644234	100
\.


--
-- TOC entry 5634 (class 0 OID 5283664)
-- Dependencies: 173
-- Data for Name: sis_usuario; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.sis_usuario (usr_id, nombre, clave, apenom, domi, tdoc, ndoc, oficina, cargo, legajo, matricula, grupo, est, tel, cel, mail, distrib, inspec_inm, inspec_comer, inspec_op, inspec_juz, inspec_recl, abogado, cajero, fchalta, fchbaja, fchmod, usrmod) FROM stdin;
100	sa	d41d8cd98f00b204e9800998ecf8427e	Administrador Sistema		3	0	0		0	0	1	A				1	0	0	0	0	0	0	1	2019-05-20 14:08:55.898538	\N	2019-05-20 14:08:55.898538	100
\.


--
-- TOC entry 6011 (class 0 OID 5287252)
-- Dependencies: 639
-- Data for Name: sis_usuario_acc; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.sis_usuario_acc (acc_id, usr_id, fchingreso, fchsalida, ip, modo) FROM stdin;
\.


--
-- TOC entry 6013 (class 0 OID 5287259)
-- Dependencies: 641
-- Data for Name: sis_usuario_acc_err; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.sis_usuario_acc_err (acc_id, usr_id, fchintento, ip) FROM stdin;
\.


--
-- TOC entry 6015 (class 0 OID 5287265)
-- Dependencies: 643
-- Data for Name: sis_usuario_proceso; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.sis_usuario_proceso (usr_id, pro_id, fchmod, usrmod) FROM stdin;
100	3321	2018-02-20 14:33:33.656441	100
100	3322	2018-02-20 14:33:33.656441	100
100	3320	2018-02-20 14:33:33.656441	100
100	3323	2018-02-20 14:33:33.656441	100
100	3066	2018-02-20 14:33:33.656441	100
100	3063	2018-02-20 14:33:33.656441	100
100	3064	2018-02-20 14:33:33.656441	100
100	3065	2018-02-20 14:33:33.656441	100
100	3060	2018-02-20 14:33:33.656441	100
100	3061	2018-02-20 14:33:33.656441	100
100	3520	2018-02-20 14:33:33.656441	100
100	3521	2018-02-20 14:33:33.656441	100
100	3222	2018-02-20 14:33:33.656441	100
100	3224	2018-02-20 14:33:33.656441	100
100	3225	2018-02-20 14:33:33.656441	100
100	3223	2018-02-20 14:33:33.656441	100
100	3220	2018-02-20 14:33:33.656441	100
100	3603	2018-02-20 14:33:33.656441	100
100	3602	2018-02-20 14:33:33.656441	100
100	3221	2018-02-20 14:33:33.656441	100
100	3228	2018-02-20 14:33:33.656441	100
100	3604	2018-02-20 14:33:33.656441	100
100	3607	2018-02-20 14:33:33.656441	100
100	3608	2018-02-20 14:33:33.656441	100
100	3606	2018-02-20 14:33:33.656441	100
100	1040	2018-02-20 14:33:33.656441	100
100	1000	2018-02-20 14:33:33.656441	100
100	1001	2018-02-20 14:33:33.656441	100
100	1010	2018-02-20 14:33:33.656441	100
100	3605	2018-02-20 14:33:33.656441	100
100	1021	2018-02-20 14:33:33.656441	100
100	1024	2018-02-20 14:33:33.656441	100
100	1023	2018-02-20 14:33:33.656441	100
100	1022	2018-02-20 14:33:33.656441	100
100	1020	2018-02-20 14:33:33.656441	100
100	3703	2018-02-20 14:33:33.656441	100
100	3704	2018-02-20 14:33:33.656441	100
100	3701	2018-02-20 14:33:33.656441	100
100	3702	2018-02-20 14:33:33.656441	100
100	3052	2018-02-20 14:33:33.656441	100
100	3051	2018-02-20 14:33:33.656441	100
100	3055	2018-02-20 14:33:33.656441	100
100	3053	2018-02-20 14:33:33.656441	100
100	3054	2018-02-20 14:33:33.656441	100
100	3050	2018-02-20 14:33:33.656441	100
100	3030	2018-02-20 14:33:33.656441	100
100	3031	2018-02-20 14:33:33.656441	100
100	3450	2018-02-20 14:33:33.656441	100
100	3451	2018-02-20 14:33:33.656441	100
100	3416	2018-02-20 14:33:33.656441	100
100	3579	2018-02-20 14:33:33.656441	100
100	3415	2018-02-20 14:33:33.656441	100
100	3578	2018-02-20 14:33:33.656441	100
100	3413	2018-02-20 14:33:33.656441	100
100	3577	2018-02-20 14:33:33.656441	100
100	3575	2018-02-20 14:33:33.656441	100
100	3417	2018-02-20 14:33:33.656441	100
100	3412	2018-02-20 14:33:33.656441	100
100	3504	2018-02-20 14:33:33.656441	100
100	3414	2018-02-20 14:33:33.656441	100
100	3493	2018-02-20 14:33:33.656441	100
100	3418	2018-02-20 14:33:33.656441	100
100	3494	2018-02-20 14:33:33.656441	100
100	3419	2018-02-20 14:33:33.656441	100
100	3496	2018-02-20 14:33:33.656441	100
100	3497	2018-02-20 14:33:33.656441	100
100	3495	2018-02-20 14:33:33.656441	100
100	3572	2018-02-20 14:33:33.656441	100
100	3570	2018-02-20 14:33:33.656441	100
100	3576	2018-02-20 14:33:33.656441	100
100	3472	2018-02-20 14:33:33.656441	100
100	3473	2018-02-20 14:33:33.656441	100
100	3470	2018-02-20 14:33:33.656441	100
100	3471	2018-02-20 14:33:33.656441	100
100	3232	2018-02-20 14:33:33.656441	100
100	3234	2018-02-20 14:33:33.656441	100
100	3239	2018-02-20 14:33:33.656441	100
100	3240	2018-02-20 14:33:33.656441	100
100	3233	2018-02-20 14:33:33.656441	100
100	3230	2018-02-20 14:33:33.656441	100
100	3235	2018-02-20 14:33:33.656441	100
100	3243	2018-02-20 14:33:33.656441	100
100	3231	2018-02-20 14:33:33.656441	100
100	3242	2018-02-20 14:33:33.656441	100
100	3237	2018-02-20 14:33:33.656441	100
100	3238	2018-02-20 14:33:33.656441	100
100	3245	2018-02-20 14:33:33.656441	100
100	3244	2018-02-20 14:33:33.656441	100
100	3241	2018-02-20 14:33:33.656441	100
100	3236	2018-02-20 14:33:33.656441	100
100	3202	2018-02-20 14:33:33.656441	100
100	3210	2018-02-20 14:33:33.656441	100
100	3211	2018-02-20 14:33:33.656441	100
100	3203	2018-02-20 14:33:33.656441	100
100	3200	2018-02-20 14:33:33.656441	100
100	3204	2018-02-20 14:33:33.656441	100
100	3215	2018-02-20 14:33:33.656441	100
100	3205	2018-02-20 14:33:33.656441	100
100	3201	2018-02-20 14:33:33.656441	100
100	3219	2018-02-20 14:33:33.656441	100
100	3213	2018-02-20 14:33:33.656441	100
100	3214	2018-02-20 14:33:33.656441	100
100	3208	2018-02-20 14:33:33.656441	100
100	3209	2018-02-20 14:33:33.656441	100
100	3206	2018-02-20 14:33:33.656441	100
100	3218	2018-02-20 14:33:33.656441	100
100	3216	2018-02-20 14:33:33.656441	100
100	3217	2018-02-20 14:33:33.656441	100
100	3212	2018-02-20 14:33:33.656441	100
100	3207	2018-02-20 14:33:33.656441	100
100	3502	2018-02-20 14:33:33.656441	100
100	3500	2018-02-20 14:33:33.656441	100
100	3501	2018-02-20 14:33:33.656441	100
100	3430	2018-02-20 14:33:33.656441	100
100	3303	2018-02-20 14:33:33.656441	100
100	3308	2018-02-20 14:33:33.656441	100
100	3305	2018-02-20 14:33:33.656441	100
100	3306	2018-02-20 14:33:33.656441	100
100	3315	2018-02-20 14:33:33.656441	100
100	3317	2018-02-20 14:33:33.656441	100
100	3300	2018-02-20 14:33:33.656441	100
100	3319	2018-02-20 14:33:33.656441	100
100	3307	2018-02-20 14:33:33.656441	100
100	3301	2018-02-20 14:33:33.656441	100
100	3433	2018-02-20 14:33:33.656441	100
100	3309	2018-02-20 14:33:33.656441	100
100	3316	2018-02-20 14:33:33.656441	100
100	3318	2018-02-20 14:33:33.656441	100
100	3302	2018-02-20 14:33:33.656441	100
100	3432	2018-02-20 14:33:33.656441	100
100	3359	2018-02-20 14:33:33.656441	100
100	3358	2018-02-20 14:33:33.656441	100
100	3331	2018-02-20 14:33:33.656441	100
100	3335	2018-02-20 14:33:33.656441	100
100	3332	2018-02-20 14:33:33.656441	100
100	3330	2018-02-20 14:33:33.656441	100
100	3336	2018-02-20 14:33:33.656441	100
100	3333	2018-02-20 14:33:33.656441	100
100	3334	2018-02-20 14:33:33.656441	100
100	3461	2018-02-20 14:33:33.656441	100
100	3460	2018-02-20 14:33:33.656441	100
100	3462	2018-02-20 14:33:33.656441	100
100	1030	2018-02-20 14:33:33.656441	100
100	1031	2018-02-20 14:33:33.656441	100
100	3313	2018-02-20 14:33:33.656441	100
100	3312	2018-02-20 14:33:33.656441	100
100	3435	2018-02-20 14:33:33.656441	100
100	3310	2018-02-20 14:33:33.656441	100
100	3311	2018-02-20 14:33:33.656441	100
100	3314	2018-02-20 14:33:33.656441	100
100	3434	2018-02-20 14:33:33.656441	100
100	3546	2018-02-20 14:33:33.656441	100
100	3547	2018-02-20 14:33:33.656441	100
100	3408	2018-02-20 14:33:33.656441	100
100	3409	2018-02-20 14:33:33.656441	100
100	3410	2018-02-20 14:33:33.656441	100
100	3411	2018-02-20 14:33:33.656441	100
100	3441	2018-02-20 14:33:33.656441	100
100	3440	2018-02-20 14:33:33.656441	100
100	3442	2018-02-20 14:33:33.656441	100
100	3372	2018-02-20 14:33:33.656441	100
100	3373	2018-02-20 14:33:33.656441	100
100	3370	2018-02-20 14:33:33.656441	100
100	3371	2018-02-20 14:33:33.656441	100
100	3106	2018-02-20 14:33:33.656441	100
100	3107	2018-02-20 14:33:33.656441	100
100	3072	2018-02-20 14:33:33.656441	100
100	3080	2018-02-20 14:33:33.656441	100
100	3081	2018-02-20 14:33:33.656441	100
100	3073	2018-02-20 14:33:33.656441	100
100	3070	2018-02-20 14:33:33.656441	100
100	3074	2018-02-20 14:33:33.656441	100
100	3076	2018-02-20 14:33:33.656441	100
100	3075	2018-02-20 14:33:33.656441	100
100	3071	2018-02-20 14:33:33.656441	100
100	3094	2018-02-20 14:33:33.656441	100
100	3093	2018-02-20 14:33:33.656441	100
100	3083	2018-02-20 14:33:33.656441	100
100	3077	2018-02-20 14:33:33.656441	100
100	3079	2018-02-20 14:33:33.656441	100
100	3105	2018-02-20 14:33:33.656441	100
100	3092	2018-02-20 14:33:33.656441	100
100	3104	2018-02-20 14:33:33.656441	100
100	3082	2018-02-20 14:33:33.656441	100
100	3078	2018-02-20 14:33:33.656441	100
100	3381	2018-02-20 14:33:33.656441	100
100	3386	2018-02-20 14:33:33.656441	100
100	3384	2018-02-20 14:33:33.656441	100
100	3382	2018-02-20 14:33:33.656441	100
100	3380	2018-02-20 14:33:33.656441	100
100	3385	2018-02-20 14:33:33.656441	100
100	3383	2018-02-20 14:33:33.656441	100
100	3609	2018-02-20 14:33:33.656441	100
100	3229	2018-02-20 14:33:33.656441	100
100	3601	2018-02-20 14:33:33.656441	100
100	3227	2018-02-20 14:33:33.656441	100
100	3436	2018-02-20 14:33:33.656441	100
100	3341	2018-02-20 14:33:33.656441	100
100	3342	2018-02-20 14:33:33.656441	100
100	3353	2018-02-20 14:33:33.656441	100
100	3347	2018-02-20 14:33:33.656441	100
100	3349	2018-02-20 14:33:33.656441	100
100	3345	2018-02-20 14:33:33.656441	100
100	3350	2018-02-20 14:33:33.656441	100
100	3351	2018-02-20 14:33:33.656441	100
100	3340	2018-02-20 14:33:33.656441	100
100	3354	2018-02-20 14:33:33.656441	100
100	3344	2018-02-20 14:33:33.656441	100
100	3343	2018-02-20 14:33:33.656441	100
100	3040	2018-02-20 14:33:33.656441	100
100	3041	2018-02-20 14:33:33.656441	100
100	3282	2018-02-20 14:33:33.656441	100
100	3285	2018-02-20 14:33:33.656441	100
100	3286	2018-02-20 14:33:33.656441	100
100	3283	2018-02-20 14:33:33.656441	100
100	3291	2018-02-20 14:33:33.656441	100
100	3280	2018-02-20 14:33:33.656441	100
100	3292	2018-02-20 14:33:33.656441	100
100	3289	2018-02-20 14:33:33.656441	100
100	3281	2018-02-20 14:33:33.656441	100
100	3288	2018-02-20 14:33:33.656441	100
100	3284	2018-02-20 14:33:33.656441	100
100	3290	2018-02-20 14:33:33.656441	100
100	3287	2018-02-20 14:33:33.656441	100
100	3543	2018-02-20 14:33:33.656441	100
100	3541	2018-02-20 14:33:33.656441	100
100	3530	2018-02-20 14:33:33.656441	100
100	3537	2018-02-20 14:33:33.656441	100
100	3545	2018-02-20 14:33:33.656441	100
100	3535	2018-02-20 14:33:33.656441	100
100	3532	2018-02-20 14:33:33.656441	100
100	3542	2018-02-20 14:33:33.656441	100
100	3534	2018-02-20 14:33:33.656441	100
100	3536	2018-02-20 14:33:33.656441	100
100	3544	2018-02-20 14:33:33.656441	100
100	3548	2018-02-20 14:33:33.656441	100
100	3539	2018-02-20 14:33:33.656441	100
100	3540	2018-02-20 14:33:33.656441	100
100	3538	2018-02-20 14:33:33.656441	100
100	3531	2018-02-20 14:33:33.656441	100
100	3533	2018-02-20 14:33:33.656441	100
100	3422	2018-02-20 14:33:33.656441	100
100	3423	2018-02-20 14:33:33.656441	100
100	3420	2018-02-20 14:33:33.656441	100
100	3421	2018-02-20 14:33:33.656441	100
100	3426	2018-02-20 14:33:33.656441	100
100	3429	2018-02-20 14:33:33.656441	100
100	3428	2018-02-20 14:33:33.656441	100
100	3427	2018-02-20 14:33:33.656441	100
100	3424	2018-02-20 14:33:33.656441	100
100	3425	2018-02-20 14:33:33.656441	100
100	3012	2018-02-20 14:33:33.656441	100
100	3013	2018-02-20 14:33:33.656441	100
100	3014	2018-02-20 14:33:33.656441	100
100	3018	2018-02-20 14:33:33.656441	100
100	3019	2018-02-20 14:33:33.656441	100
100	3010	2018-02-20 14:33:33.656441	100
100	3011	2018-02-20 14:33:33.656441	100
100	3016	2018-02-20 14:33:33.656441	100
100	3017	2018-02-20 14:33:33.656441	100
\.


--
-- TOC entry 6016 (class 0 OID 5287269)
-- Dependencies: 644
-- Data for Name: sis_usuario_tesoreria; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.sis_usuario_tesoreria (usr_id, teso_id) FROM stdin;
100	0
100	1
\.


--
-- TOC entry 6017 (class 0 OID 5287272)
-- Dependencies: 645
-- Data for Name: sis_usuario_tmodo; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.sis_usuario_tmodo (cod, nombre, fchmod, usrmod) FROM stdin;
M	Manual	2016-02-17 05:48:09.367975	100
N	Normal	2016-02-17 05:48:09.367975	100
A	Auto	2016-02-17 05:48:09.367975	100
V	Anterior	2016-02-17 05:48:09.367975	100
\.


--
-- TOC entry 6018 (class 0 OID 5287276)
-- Dependencies: 646
-- Data for Name: sis_usuario_trib; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.sis_usuario_trib (usr_id, trib_id) FROM stdin;
100	25
100	26
100	27
100	28
100	29
100	30
100	31
100	32
100	33
100	34
100	35
100	36
100	37
100	38
100	39
100	40
100	41
100	42
100	43
\.


--
-- TOC entry 6020 (class 0 OID 5287281)
-- Dependencies: 648
-- Data for Name: tabla_aux; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.tabla_aux (cod, nombre, mod_id, titulo, frm, link, autoinc, accesocons, accesoedita, tcod, web, fchmod, usrmod) FROM stdin;
31	PaisProvLoc	72	País / Provincia / Localidad	frmPaisProvLoc		0	1030	1031	N	S	2015-06-15 13:56:34.582887	\N
131	partida_grupo	24	Grupos de Cuenta			1	3040	3041	N	S	2015-07-08 09:44:57	\N
45	cem_fall_tserv	30	Servicios Fallecido	frmCementerioServConfig		0	3239	3240	C	N	2015-06-15 13:56:34.582887	\N
133	sam.muni_oficina	71	Oficinas		auxeditoficina	1	3520	3521	N	S	2015-07-08 10:14:12	\N
49	rodado_aforo	34	Tipo de Modelos de Aforo	frmRodadoAforoModelo	auxeditrodaforomod	0	3285	3286	C	S	2015-06-15 13:56:34.582887	\N
137	cem_cuadro	30	Cuadros de Cementerio		auxeditcemcuadro	0	3239	3240	C	S	2015-08-19 13:57:16	100
138	cem_cuerpo	30	Cuerpos de Cementerio		auxeditcemcuerpo	0	3239	3240	C	S	2015-08-19 13:59:15	100
132	objeto_tdistrib	72	Tipo de Distribución			1	1030	0	N	S	2015-07-08 09:58:33	\N
130	domi_calle	72	Calles		auxeditcalle	1	3470	3471	N	S	2015-07-01 17:24:31	\N
128	plan_temple	55	Tipo de Empleado		auxeditplantemple	0	3340	3341	N	S	2015-06-17 16:06:11.389957	\N
17	inm_mej_tform	27	Formulario			0	3080	0	C	S	2015-06-15 13:56:34.582887	\N
8	inm_turbsub	27	Urbano - Subrural			0	3080	0	C	S	2015-06-15 13:56:34.582887	\N
9	inm_ttitularidad	27	Tipos de Titularidad			0	3080	3081	C	S	2015-06-15 13:56:34.582887	\N
10	inm_tregimen	27	Tipos de Régimen			0	3080	0	N	S	2015-06-15 13:56:34.582887	\N
11	inm_tipo	27	Tipos de Inmuebles			0	3080	3081	C	S	2015-06-15 13:56:34.582887	\N
12	inm_tuso	27	Tipos de Uso			1	3080	3081	N	S	2015-06-15 13:56:34.582887	\N
13	inm_tmatric	27	Tipos de Matrículas			0	3080	0	C	S	2015-06-15 13:56:34.582887	\N
14	inm_tpatrimonio	27	Tipo de Patrimonio			1	3080	3081	N	S	2015-06-15 13:56:34.582887	\N
15	inm_trestric	27	Tipos de Restricciones			0	3080	0	N	S	2015-06-15 13:56:34.582887	\N
16	inm_mej_tori	27	Origen del Dato			0	3080	3081	C	S	2015-06-15 13:56:34.582887	\N
18	inm_mej_tdest	27	Destinos de Mejoras			0	3080	3081	N	S	2015-06-15 13:56:34.582887	\N
19	inm_mej_test	27	Tipos de Estados de Conservación			0	3080	0	N	S	2015-06-15 13:56:34.582887	\N
20	inm_mej_tobra	27	Tipos de Obras de Mejoras			0	3080	0	N	S	2015-06-15 13:56:34.582887	\N
21	inm_tzonat	27	Zonas Tributarias			0	3080	3081	C	S	2015-06-15 13:56:34.582887	\N
22	inm_tzonav	27	Zonas Valuatorias	frmInmueblesZonaV		0	3080	3081	N	S	2015-06-15 13:56:34.582887	\N
32	domi_barrio	72	Barrios			1	1030	1031	N	S	2015-06-15 13:56:34.582887	\N
33	domi_tpav	72	Tipos de Pavimento			1	3472	3473	N	S	2015-06-15 13:56:34.582887	\N
34	domi_tcalle	72	Tipos de Calles			1	3472	3473	N	S	2015-06-15 13:56:34.582887	\N
36	cem_tipo	30	Tipos de Cuentas			0	3239	0	C	S	2015-06-15 13:56:34.582887	\N
38	cem_tdeleg	30	Delegaciones			1	3239	3240	N	S	2015-06-15 13:56:34.582887	\N
39	cem_texenta	30	Exenciones			1	3239	3240	N	S	2015-06-15 13:56:34.582887	\N
41	cem_tcausa	30	Causa de Muerte			1	3239	3240	N	S	2015-06-15 13:56:34.582887	\N
43	cem_talq_est	30	Estado Alquiler			0	3239	0	C	S	2015-06-15 13:56:34.582887	\N
47	rodado_marca	34	Marcas de Rodados			1	3285	3286	N	S	2015-06-15 13:56:34.582887	\N
50	rodado_tcat	34	Tipo/Categoría de Rodado	frmRodadoTCat		1	3285	3286	N	S	2015-06-15 13:56:34.582887	\N
52	rodado_tuso	34	Usos de Rodados			1	3285	3286	N	S	2015-06-15 13:56:34.582887	\N
53	rodado_talta	34	Tipo de Alta			1	3285	3286	N	S	2015-06-15 13:56:34.582887	\N
54	rodado_tcombustible	34	Tipo de Combustible			1	3285	3286	N	S	2015-06-15 13:56:34.582887	\N
57	comer_tcontador	28	Contadores			1	3210	3211	N	S	2015-06-15 13:56:34.582887	\N
58	comer_torgjuri	28	Tipo de Organización Jurídica			1	3210	3211	N	S	2015-06-15 13:56:34.582887	\N
59	comer_tipo	28	Tipo de Comercio			1	3210	3211	N	S	2015-06-15 13:56:34.582887	\N
60	comer_tinfrac	28	Tipo de Infracción			1	3210	3211	N	S	2015-06-15 13:56:34.582887	\N
61	comer_tiva	28	Tipo de IVA			0	3210	0	N	S	2015-06-15 13:56:34.582887	\N
62	objeto_trubro	28	Tipo de Rubro			0	3210	0	N	S	2015-06-15 13:56:34.582887	\N
63	comer_tzona	28	Tipo de zona			1	3210	3211	N	S	2015-06-15 13:56:34.582887	\N
64	comer_tliq	28	Tipo de Liquidación Comercio			0	3210	0	C	S	2015-06-15 13:56:34.582887	\N
65	trib_tipo	22	Tipos de Tributos			0	3010	0	N	S	2015-06-15 13:56:34.582887	\N
66	trib_tpago	22	Formas de Pago de Tributos			0	3010	0	N	S	2015-06-15 13:56:34.582887	\N
67	item_tfcalculo	22	Fórmulas de Cálculo de Items			0	3010	0	N	S	2015-06-15 13:56:34.582887	\N
68	item_tipo	22	Tipos de Ítems			0	3010	0	N	S	2015-06-15 13:56:34.582887	\N
70	ctacte_test	51	Estado del Período			0	3305	0	C	S	2015-06-15 13:56:34.582887	\N
71	ctacte_tcta	51	Tipos de Cuentas			0	3305	0	N	S	2015-06-15 13:56:34.582887	\N
72	ctacte_topera	51	Tipos de Operación			0	3305	0	N	S	2015-06-15 13:56:34.582887	\N
73	ddjj_tipo	54	Tipos de DDJJ			0	3030	0	N	S	2015-06-15 13:56:34.582887	\N
75	plan_test	55	Estados de Convenios			0	3340	0	N	S	2015-06-15 13:56:34.582887	\N
76	plan_tsistema	55	Convenio - Sistemas de Financ			0	3340	0	N	S	2015-06-15 13:56:34.582887	\N
77	plan_tpago	55	Tipos de Forma de Pago			0	3340	0	N	S	2015-06-15 13:56:34.582887	\N
78	plan_torigen	55	Origen de Convenios			0	3340	0	N	S	2015-06-15 13:56:34.582887	\N
79	banco_entidad	62	Banco - Entidades			0	3575	3417	N	S	2015-06-15 13:56:34.582887	\N
82	caja_tipo	62	Tipos de Cajas			0	3575	0	N	S	2015-06-15 13:56:34.582887	\N
83	caja_tdestino	62	Destino de Tickets			0	3575	0	N	S	2015-06-15 13:56:34.582887	\N
84	caja_test	62	Estado de la Caja			0	3575	0	N	S	2015-06-15 13:56:34.582887	\N
86	caja_tdisenio	62	Diseño archivo Agente Externo			0	3575	0	N	S	2015-06-15 13:56:34.582887	\N
87	judi_test	58	Estados de Planillas			0	3386	0	N	S	2015-06-15 13:56:34.582887	\N
88	judi_juzgado	58	Juzgados			1	3386	3384	N	S	2015-06-15 13:56:34.582887	\N
89	judi_procurador	58	Procurador			1	3386	3384	N	S	2015-06-15 13:56:34.582887	\N
90	judi_trep	58	Repartición			0	3386	3384	C	S	2015-06-15 13:56:34.582887	\N
91	judi_tetapa	58	Etapa Judicial			0	3386	0	C	S	2015-06-15 13:56:34.582887	\N
92	judi_tembargo	58	Tipos de Embargos			1	3386	3384	N	S	2015-06-15 13:56:34.582887	\N
93	judi_tsupuesto	58	Tipos de Supuestos			1	3386	0	N	S	2015-06-15 13:56:34.582887	\N
94	judi_tdev	58	Devolución de Planillas			1	3386	3384	N	S	2015-06-15 13:56:34.582887	\N
96	hab_tord	75	Ordenanzas			1	3555	3556	N	S	2015-06-15 13:56:34.582887	\N
97	hab_treq	75	Requisitos			1	3555	3556	N	S	2015-06-15 13:56:34.582887	\N
98	hab_tipif	75	Tipificaciones	frmHabTipif		1	3555	3556	N	S	2015-06-15 13:56:34.582887	\N
99	hab_ttramite	75	Trámites			0	3555	0	N	S	2015-06-15 13:56:34.582887	\N
40	cem_fall_test	30	Estado de Fallecidos			0	3239	0	C	S	2015-06-15 13:56:34.582887	\N
51	rodado_tdeleg	34	Delegación de Rodados	frmRodadoDeleg	auxeditroddeleg	1	3285	3286	N	S	2015-06-15 13:56:34.582887	\N
44	cem_talq	30	Configuración Alquileres	frmCementerioAlqConfig		0	3239	3240	N	N	2015-06-15 13:56:34.582887	\N
48	rodado_modelo	34	Modelos de Rodados	frmRodadoModelo	auxeditrodmodelo	1	3285	3286	N	S	2015-06-15 13:56:34.582887	\N
42	cem_tfunebre	30	Empresas Fúnebres			1	3239	3240	N	S	2015-06-15 13:56:34.582887	\N
80	banco	62	Banco - Sucursales	frmBancoSuc	auxeditbancosuc	0	3575	3417	N	S	2015-06-15 13:56:34.582887	\N
85	caja_mdp	62	Medios de Pago	frmCajaMdp	auxeditcajamdp	1	3575	3417	N	S	2015-06-15 13:56:34.582887	\N
81	caja_tesoreria	62	Tesorerías	frmCajaTesoreria	auxeditcajateso	0	3575	3417	N	S	2015-06-15 13:56:34.582887	\N
95	judi_hono	58	Honorarios Judiciales	frmJudiHono	auxeditjudihono	0	3386	3384	C	S	2015-06-15 13:56:34.582887	\N
37	CuadroCuerpo	30	Cuadros y Cuerpos	frmCementerioCuadro	auxeditcemcuacue	0	3239	3240	C	S	2015-06-15 13:56:34.582887	\N
56	rodado_val	34	Valores de Rodado	frmRodadoVal	auxeditrodval	1	3285	3286	N	S	2015-06-15 13:56:34.582887	\N
69	objeto_trib_cat	22	Categoría Inscrip. a Tributos	frmTribInscripCat	auxeditobjtribcat	0	3010	3011	N	S	2015-06-15 13:56:34.582887	\N
46	rodado_torigen	34	Tipo de Origen			0	3285	0	C	S	2015-06-15 13:56:34.582887	\N
55	rodado_tliq	34	Tipo de Liquidación Rodado			0	3285	0	N	S	2015-06-15 13:56:34.582887	\N
100	hab_tfacti	75	Tipo de Factibilidad			0	3555	0	N	S	2015-06-15 13:56:34.582887	\N
121	transporte_empresa	33	Empresas de Transporte	frmTransporteEmpresa		1	3585	3586	N	N	2015-06-15 13:56:34.582887	\N
122	pub_marca_grupo	35	Grupo de Marcas			1	3622	3623	N	N	2015-06-15 13:56:34.582887	\N
123	pub_tarjeta	35	Tarjetas			1	3622	3623	N	N	2015-06-15 13:56:34.582887	\N
124	pub_tipo	35	Tipos de Publicidad			1	3622	3623	C	N	2015-06-15 13:56:34.582887	\N
125	pub_empresa	35	Empresas	frmPubEmpresa		1	3622	3623	N	N	2015-06-15 13:56:34.582887	\N
126	pub_marca	35	Marcas	frmPubMarca		1	3622	3623	N	N	2015-06-15 13:56:34.582887	\N
141	sam.cons_tema	80	Consultas Web - Temas			0	3703	3703	N	S	2016-03-28 15:32:22.803519	100
74	rubro_grupo	54	Grupos de Rubros			0	3030	3031	C	S	2015-06-15 13:56:34.582887	\N
143	rodado_tform	34	Tipos de Formularios			0	3285	3286	C	S	2017-04-04 13:17:29.107166	100
144	persona_tbajaib	34	Tipos de TIPos			0	3285	3286	C	S	2017-04-04 13:18:17.093321	100
139	sam.muni_sec	71	Secretarías		auxeditsecretaria	1	3520	3521	N	S	2015-08-20 11:57:28	100
147	comer_thab	28	Tipos de Habilitación			0	3210	3211	C	S	2017-11-01 13:36:59.022881	100
\.


--
-- TOC entry 5984 (class 0 OID 5286105)
-- Dependencies: 581
-- Data for Name: usuarioweb; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.usuarioweb (usr_id, nombre, clave, obj_id, acc_contrib, acc_dj, acc_proveedor, acc_agrete, acc_escribano, mail, est, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 6021 (class 0 OID 5287304)
-- Dependencies: 651
-- Data for Name: version; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.version (sis_id, version, origen, novedades, fchmod, usrmod) FROM stdin;
3	3.0.2.0	/var/www/html	Versión Inicial	2018-02-16 09:55:58.302584	100
\.


--
-- TOC entry 6022 (class 0 OID 5287312)
-- Dependencies: 652
-- Data for Name: version_archivo; Type: TABLE DATA; Schema: sam; Owner: postgres
--

COPY sam.version_archivo (sis_id, version, archivo, destino, registrar) FROM stdin;
\.


--
-- TOC entry 6023 (class 0 OID 5287316)
-- Dependencies: 653
-- Data for Name: ag_rete; Type: TABLE DATA; Schema: temp; Owner: postgres
--

COPY temp.ag_rete (ag_rete, fila, cuit, fecha, numero, tcomprob, comprob, base, alic, monto, usrmod, fchmod) FROM stdin;
012	2	20273644912	20171101	00004307	FB	000000058581	00000025158	00000000600	00000001509	16	2017-12-06 15:31:35.056328
012	3	27237091987	20171101	00004308	FB	000000058610	00000116969	00000000600	00000007018	16	2017-12-06 15:31:35.056328
012	4	20273644912	20171101	00004309	FB	000000058616	00000074986	00000000600	00000004499	16	2017-12-06 15:31:35.056328
012	5	20273644912	20171107	00004310	FB	000000058829	00000118641	00000000600	00000007118	16	2017-12-06 15:31:35.056328
012	6	27297722927	20171107	00004311	FB	000000058830	00000160529	00000000600	00000009632	16	2017-12-06 15:31:35.056328
012	7	27237091987	20171107	00004312	FB	000000058848	00000094471	00000000600	00000005668	16	2017-12-06 15:31:35.056328
012	8	20273644912	20171108	00004313	FB	000000058995	00000060378	00000000600	00000003623	16	2017-12-06 15:31:35.056328
012	9	27237091987	20171108	00004314	FB	000000058998	00000200381	00000000600	00000012023	16	2017-12-06 15:31:35.056328
012	10	27297722927	20171108	00004315	FB	000000059000	00000088555	00000000600	00000005313	16	2017-12-06 15:31:35.056328
012	11	27237091987	20171108	00004316	FB	000000059054	00000064977	00000000600	00000003899	16	2017-12-06 15:31:35.056328
012	12	27297722927	20171108	00004317	FB	000000059061	00000210614	00000000600	00000012637	16	2017-12-06 15:31:35.056328
012	13	27297722927	20171108	00004318	FB	000000059062	00000015340	00000000600	00000000920	16	2017-12-06 15:31:35.056328
012	14	20273644912	20171108	00004319	FB	000000059063	00000088121	00000000600	00000005287	16	2017-12-06 15:31:35.056328
012	15	20173955449	20171108	00004320	FB	000000059066	00000109945	00000000600	00000006597	16	2017-12-06 15:31:35.056328
012	16	27297722927	20171109	00004321	OT	000000002483	-0000025158	00000000600	-0000001509	16	2017-12-06 15:31:35.056328
012	17	27237091987	20171114	00004322	FB	000000059307	00000121221	00000000600	00000007273	16	2017-12-06 15:31:35.056328
012	18	27297722927	20171114	00004323	FB	000000059312	00000158012	00000000600	00000009481	16	2017-12-06 15:31:35.056328
012	19	20273644912	20171114	00004324	FB	000000059313	00000113728	00000000600	00000006824	16	2017-12-06 15:31:35.056328
012	20	27297722927	20171114	00004325	FB	000000059326	00000032887	00000000600	00000001973	16	2017-12-06 15:31:35.056328
012	21	27297722927	20171114	00004326	FB	000000059342	00000007011	00000000600	00000000421	16	2017-12-06 15:31:35.056328
012	22	27297722927	20171114	00004327	OT	000000002496	-0000001874	00000000598	-0000000112	16	2017-12-06 15:31:35.056328
012	23	27346653502	20171115	00004328	FB	000000059463	00000116984	00000000600	00000007019	16	2017-12-06 15:31:35.056328
012	24	27297722927	20171115	00004329	FB	000000059464	00000118744	00000000600	00000007125	16	2017-12-06 15:31:35.056328
012	25	20273644912	20171115	00004330	FB	000000059465	00000025158	00000000600	00000001509	16	2017-12-06 15:31:35.056328
012	26	27237091987	20171115	00004331	FB	000000059503	00000122937	00000000600	00000007376	16	2017-12-06 15:31:35.056328
012	27	27297722927	20171115	00004332	FB	000000059510	00000158710	00000000600	00000009523	16	2017-12-06 15:31:35.056328
012	28	20273644912	20171115	00004333	FB	000000059511	00000076453	00000000600	00000004587	16	2017-12-06 15:31:35.056328
012	29	20078192144	20171115	00004334	FB	000000059512	00000115200	00000000600	00000006912	16	2017-12-06 15:31:35.056328
012	30	20173955449	20171115	00004335	FB	000000059513	00000095944	00000000600	00000005757	16	2017-12-06 15:31:35.056328
012	31	27237091987	20171121	00004336	FB	000000059714	00000060109	00000000600	00000003607	16	2017-12-06 15:31:35.056328
012	32	20273644912	20171121	00004337	FB	000000059729	00000086614	00000000600	00000005197	16	2017-12-06 15:31:35.056328
012	33	27297722927	20171121	00004338	FB	000000059730	00000166417	00000000600	00000009985	16	2017-12-06 15:31:35.056328
012	34	27297722927	20171121	00004339	FB	000000059731	00000178742	00000000600	00000010725	16	2017-12-06 15:31:35.056328
012	35	27297722927	20171121	00004340	FB	000000059732	00000067044	00000000600	00000004023	16	2017-12-06 15:31:35.056328
012	36	27297722927	20171121	00004341	FB	000000059736	00000032131	00000000600	00000001928	16	2017-12-06 15:31:35.056328
012	37	27237091987	20171122	00004342	FB	000000059871	00000147171	00000000600	00000008830	16	2017-12-06 15:31:35.056328
012	38	20273644912	20171122	00004343	FB	000000059873	00000059749	00000000600	00000003585	16	2017-12-06 15:31:35.056328
012	39	27297722927	20171122	00004344	FB	000000059874	00000066542	00000000600	00000003993	16	2017-12-06 15:31:35.056328
012	40	27237091987	20171122	00004345	FB	000000059923	00000123405	00000000600	00000007404	16	2017-12-06 15:31:35.056328
012	41	20273644912	20171122	00004346	FB	000000059931	00000123080	00000000600	00000007385	16	2017-12-06 15:31:35.056328
012	42	20148499161	20171122	00004347	FB	000000059932	00000040634	00000000600	00000002438	16	2017-12-06 15:31:35.056328
012	43	20078192144	20171122	00004348	FB	000000059934	00000029178	00000000600	00000001751	16	2017-12-06 15:31:35.056328
012	44	27017476144	20171127	00004349	FB	000000060148	00000049269	00000000600	00000002956	16	2017-12-06 15:31:35.056328
012	45	27237091987	20171128	00004350	FB	000000060197	00000100544	00000000600	00000006033	16	2017-12-06 15:31:35.056328
012	46	20273644912	20171128	00004351	FB	000000060205	00000059205	00000000600	00000003552	16	2017-12-06 15:31:35.056328
012	47	27297722927	20171128	00004352	FB	000000060206	00000181406	00000000600	00000010884	16	2017-12-06 15:31:35.056328
012	48	27297722927	20171128	00004353	FB	000000060207	00000004632	00000000600	00000000278	16	2017-12-06 15:31:35.056328
012	49	27237091987	20171129	00004354	FB	000000060314	00000195474	00000000600	00000011728	16	2017-12-06 15:31:35.056328
012	50	20273644912	20171129	00004355	FB	000000060316	00000083649	00000000600	00000005019	16	2017-12-06 15:31:35.056328
012	51	27297722927	20171129	00004356	FB	000000060317	00000178493	00000000600	00000010710	16	2017-12-06 15:31:35.056328
012	52	27237091987	20171129	00004357	FB	000000060359	00000137394	00000000600	00000008244	16	2017-12-06 15:31:35.056328
012	53	27297722927	20171129	00004358	FB	000000060365	00000152339	00000000600	00000009140	16	2017-12-06 15:31:35.056328
012	54	27297722927	20171129	00004359	FB	000000060366	00000148234	00000000600	00000008894	16	2017-12-06 15:31:35.056328
012	55	20273644912	20171129	00004360	FB	000000060367	00000119215	00000000600	00000007153	16	2017-12-06 15:31:35.056328
012	56	20148499161	20171129	00004361	FB	000000060368	00000044004	00000000600	00000002640	16	2017-12-06 15:31:35.056328
012	57	20078192144	20171129	00004362	FB	000000060369	00000017366	00000000600	00000001042	16	2017-12-06 15:31:35.056328
012	58	20078192144	20171129	00004363	FB	000000060370	00000039694	00000000600	00000002382	16	2017-12-06 15:31:35.056328
012	59	20173955449	20171129	00004364	FB	000000060371	00000111591	00000000600	00000006695	16	2017-12-06 15:31:35.056328
012	60	20173955449	20171129	00004365	FB	000000060372	00000018345	00000000600	00000001101	16	2017-12-06 15:31:35.056328
012	61	20273644912	20171130	00004366	OT	000000002539	-0000030189	00000000600	-0000001811	16	2017-12-06 15:31:35.056328
012	62	27188206463	20171101	00004367	FA	000000034535	00000028302	00000000200	00000000566	16	2017-12-06 15:31:35.056328
012	63	27125944707	20171101	00004368	FA	000000034563	00000119551	00000000200	00000002391	16	2017-12-06 15:31:35.056328
012	64	27952625514	20171101	00004369	FA	000000034564	00000270762	00000000200	00000005415	16	2017-12-06 15:31:35.056328
012	65	27952625514	20171101	00004370	FA	000000034565	00000040562	00000000200	00000000811	16	2017-12-06 15:31:35.056328
012	66	27222713140	20171101	00004371	FA	000000034566	00000054238	00000000200	00000001085	16	2017-12-06 15:31:35.056328
012	67	27100281819	20171101	00004372	FA	000000034567	00000138144	00000000200	00000002763	16	2017-12-06 15:31:35.056328
012	68	23163173824	20171101	00004373	FA	000000034568	00000049851	00000000200	00000000997	16	2017-12-06 15:31:35.056328
012	69	27125944707	20171101	00004374	FA	000000034569	00000046850	00000000200	00000000937	16	2017-12-06 15:31:35.056328
012	70	20274035170	20171101	00004375	FA	000000034570	00000142051	00000000200	00000002841	16	2017-12-06 15:31:35.056328
012	71	20261719887	20171101	00004376	FA	000000034571	00000730095	00000000200	00000014602	16	2017-12-06 15:31:35.056328
012	72	20261719887	20171101	00004377	FA	000000034572	00000166222	00000000200	00000003324	16	2017-12-06 15:31:35.056328
012	73	20261719887	20171101	00004378	FA	000000034573	00000265118	00000000200	00000005302	16	2017-12-06 15:31:35.056328
012	74	20261719887	20171101	00004379	FA	000000034574	00000137141	00000000200	00000002743	16	2017-12-06 15:31:35.056328
012	75	23289795464	20171101	00004380	FA	000000034575	00000040274	00000000200	00000000805	16	2017-12-06 15:31:35.056328
012	76	23289795464	20171101	00004381	FA	000000034576	00000089187	00000000200	00000001784	16	2017-12-06 15:31:35.056328
012	77	27056603560	20171101	00004382	FA	000000034577	00000182099	00000000200	00000003642	16	2017-12-06 15:31:35.056328
012	78	20054139285	20171101	00004383	FA	000000034578	00000043691	00000000200	00000000874	16	2017-12-06 15:31:35.056328
012	79	30670364603	20171101	00004384	FA	000000034579	00000289002	00000000200	00000005780	16	2017-12-06 15:31:35.056328
012	80	30670364603	20171101	00004385	FA	000000034580	00000177213	00000000200	00000003544	16	2017-12-06 15:31:35.056328
012	81	20305781720	20171101	00004386	FA	000000034581	00000106370	00000000200	00000002127	16	2017-12-06 15:31:35.056328
012	82	30670362317	20171101	00004387	FA	000000034582	00000033208	00000000200	00000000664	16	2017-12-06 15:31:35.056328
012	83	30670364603	20171101	00004388	FA	000000034539	00000155222	00000000200	00000003104	16	2017-12-06 15:31:35.056328
012	84	27952625514	20171101	00004389	FA	000000034540	00000054424	00000000200	00000001088	16	2017-12-06 15:31:35.056328
012	85	20073261806	20171101	00004390	FA	000000034541	00000140296	00000000200	00000002806	16	2017-12-06 15:31:35.056328
012	86	27313340339	20171101	00004391	FB	000000058580	00000028302	00000000200	00000000566	16	2017-12-06 15:31:35.056328
012	87	27248176607	20171101	00004392	FB	000000058582	00000142140	00000000200	00000002843	16	2017-12-06 15:31:35.056328
012	88	23331976199	20171101	00004393	FB	000000058583	00000137109	00000000200	00000002742	16	2017-12-06 15:31:35.056328
012	89	27363206641	20171101	00004394	FB	000000058608	00000064035	00000000200	00000001281	16	2017-12-06 15:31:35.056328
012	90	27313340339	20171101	00004395	FB	000000058609	00000034183	00000000200	00000000684	16	2017-12-06 15:31:35.056328
012	91	27112378257	20171101	00004396	FB	000000058611	00000079808	00000000200	00000001596	16	2017-12-06 15:31:35.056328
012	92	27215184760	20171101	00004397	FB	000000058612	00000024444	00000000200	00000000489	16	2017-12-06 15:31:35.056328
012	93	27219273431	20171101	00004398	FB	000000058613	00000073589	00000000200	00000001472	16	2017-12-06 15:31:35.056328
012	94	27121002650	20171101	00004399	FB	000000058614	00000041529	00000000200	00000000831	16	2017-12-06 15:31:35.056328
012	95	27044920102	20171101	00004400	FB	000000058615	00000036745	00000000200	00000000735	16	2017-12-06 15:31:35.056328
012	96	20149833510	20171101	00004401	FB	000000058617	00000092220	00000000200	00000001844	16	2017-12-06 15:31:35.056328
012	97	20173955449	20171101	00004402	FB	000000058618	00000006493	00000000200	00000000130	16	2017-12-06 15:31:35.056328
012	98	23331976199	20171101	00004403	FB	000000058619	00000027855	00000000200	00000000557	16	2017-12-06 15:31:35.056328
012	99	27394430655	20171101	00004404	FB	000000058620	00000053263	00000000200	00000001065	16	2017-12-06 15:31:35.056328
012	100	27188206463	20171101	00004405	OT	000000002125	-0000028302	00000000200	-0000000566	16	2017-12-06 15:31:35.056328
012	101	27952625514	20171101	00004406	OT	000000002126	-0000068554	00000000200	-0000001371	16	2017-12-06 15:31:35.056328
012	102	27313340339	20171101	00004407	OT	000000002474	-0000028302	00000000200	-0000000566	16	2017-12-06 15:31:35.056328
012	103	27952625514	20171102	00004408	FA	000000034590	00000108109	00000000200	00000002162	16	2017-12-06 15:31:35.056328
012	104	20274035170	20171103	00004409	OT	000000002137	-0000006426	00000000201	-0000000129	16	2017-12-06 15:31:35.056328
012	105	27952625514	20171106	00004410	FA	000000034735	00000085947	00000000200	00000001719	16	2017-12-06 15:31:35.056328
012	106	27952625514	20171106	00004411	FA	000000034736	00000949395	00000000200	00000018988	16	2017-12-06 15:31:35.056328
012	107	27952625514	20171106	00004412	FA	000000034737	00000394165	00000000200	00000007883	16	2017-12-06 15:31:35.056328
012	108	27952625514	20171106	00004413	FA	000000034738	00000185097	00000000200	00000003702	16	2017-12-06 15:31:35.056328
012	109	27952625514	20171106	00004414	FA	000000034739	00000369087	00000000200	00000007382	16	2017-12-06 15:31:35.056328
012	110	27952625514	20171106	00004415	FA	000000034740	00000031405	00000000200	00000000628	16	2017-12-06 15:31:35.056328
012	111	27056603560	20171107	00004416	FA	000000034759	00000084613	00000000200	00000001692	16	2017-12-06 15:31:35.056328
012	112	30670362317	20171107	00004417	FA	000000034760	00000070857	00000000200	00000001417	16	2017-12-06 15:31:35.056328
012	113	20305781720	20171107	00004418	FA	000000034761	00000054845	00000000200	00000001097	16	2017-12-06 15:31:35.056328
012	114	30670364603	20171107	00004419	FA	000000034762	00000157834	00000000200	00000003157	16	2017-12-06 15:31:35.056328
012	115	30670364603	20171107	00004420	FA	000000034763	00000080021	00000000200	00000001600	16	2017-12-06 15:31:35.056328
012	116	30715278347	20171107	00004421	FA	000000034764	00000065803	00000000200	00000001316	16	2017-12-06 15:31:35.056328
012	117	20054139285	20171107	00004422	FA	000000034765	00000106144	00000000200	00000002123	16	2017-12-06 15:31:35.056328
012	118	27125944707	20171107	00004423	FA	000000034766	00000066326	00000000200	00000001327	16	2017-12-06 15:31:35.056328
012	119	27367571123	20171107	00004424	FA	000000034767	00000091303	00000000200	00000001826	16	2017-12-06 15:31:35.056328
012	120	27367571123	20171107	00004425	FA	000000034768	00000129355	00000000200	00000002587	16	2017-12-06 15:31:35.056328
012	121	27367571123	20171107	00004426	FA	000000034769	00000039321	00000000200	00000000786	16	2017-12-06 15:31:35.056328
012	122	27173955451	20171107	00004427	FA	000000034770	00000029454	00000000200	00000000589	16	2017-12-06 15:31:35.056328
012	123	27222713140	20171107	00004428	FA	000000034771	00000093078	00000000200	00000001862	16	2017-12-06 15:31:35.056328
012	124	27222713140	20171107	00004429	FA	000000034772	00000058736	00000000200	00000001175	16	2017-12-06 15:31:35.056328
012	125	27226803233	20171107	00004430	FB	000000058821	00000155539	00000000200	00000003111	16	2017-12-06 15:31:35.056328
012	126	27226803233	20171107	00004431	FB	000000058822	00000070237	00000000200	00000001405	16	2017-12-06 15:31:35.056328
012	127	27226803233	20171107	00004432	FB	000000058823	00000124982	00000000200	00000002500	16	2017-12-06 15:31:35.056328
012	128	27226803233	20171107	00004433	FB	000000058824	00000036643	00000000200	00000000733	16	2017-12-06 15:31:35.056328
012	129	27226803233	20171107	00004434	FB	000000058825	00000058497	00000000200	00000001170	16	2017-12-06 15:31:35.056328
012	130	27044920102	20171107	00004435	FB	000000058826	00000064477	00000000200	00000001290	16	2017-12-06 15:31:35.056328
012	131	27229348774	20171107	00004436	FB	000000058827	00000055016	00000000200	00000001100	16	2017-12-06 15:31:35.056328
012	132	27041619916	20171107	00004437	FB	000000058828	00000121470	00000000200	00000002429	16	2017-12-06 15:31:35.056328
012	133	23331976199	20171107	00004438	FB	000000058831	00000136195	00000000200	00000002724	16	2017-12-06 15:31:35.056328
012	134	23331976199	20171107	00004439	FB	000000058832	00000024449	00000000200	00000000489	16	2017-12-06 15:31:35.056328
012	135	23331976199	20171107	00004440	FB	000000058833	00000020304	00000000200	00000000406	16	2017-12-06 15:31:35.056328
012	136	27325453465	20171107	00004441	FB	000000058834	00000114492	00000000200	00000002290	16	2017-12-06 15:31:35.056328
012	137	27248176607	20171107	00004442	FB	000000058835	00000125534	00000000200	00000002511	16	2017-12-06 15:31:35.056328
012	138	20388075806	20171107	00004443	FB	000000058836	00000143487	00000000200	00000002870	16	2017-12-06 15:31:35.056328
012	139	20388075806	20171107	00004444	FB	000000058837	00000055274	00000000200	00000001105	16	2017-12-06 15:31:35.056328
012	140	20388075806	20171107	00004445	FB	000000058838	00000025055	00000000200	00000000501	16	2017-12-06 15:31:35.056328
012	141	20082698508	20171107	00004446	FB	000000058839	00000058900	00000000200	00000001178	16	2017-12-06 15:31:35.056328
012	142	27394430655	20171107	00004447	FB	000000058840	00000068404	00000000200	00000001368	16	2017-12-06 15:31:35.056328
012	143	27229348774	20171107	00004448	FB	000000058841	00000010361	00000000200	00000000207	16	2017-12-06 15:31:35.056328
012	144	27121002650	20171107	00004449	FB	000000058842	00000075706	00000000200	00000001514	16	2017-12-06 15:31:35.056328
012	145	27121002650	20171107	00004450	FB	000000058843	00000023744	00000000200	00000000475	16	2017-12-06 15:31:35.056328
012	146	20313340792	20171107	00004451	FB	000000058844	00000034579	00000000200	00000000692	16	2017-12-06 15:31:35.056328
012	147	20313340792	20171107	00004452	FB	000000058845	00000002488	00000000201	00000000050	16	2017-12-06 15:31:35.056328
012	148	27346653502	20171107	00004453	FB	000000058846	00000069286	00000000200	00000001386	16	2017-12-06 15:31:35.056328
012	149	27215184760	20171107	00004454	FB	000000058847	00000019105	00000000200	00000000382	16	2017-12-06 15:31:35.056328
012	150	27313340339	20171107	00004455	FB	000000058850	00000073500	00000000200	00000001470	16	2017-12-06 15:31:35.056328
012	151	27186187666	20171107	00004456	FB	000000058897	00000169307	00000000200	00000003386	16	2017-12-06 15:31:35.056328
012	152	30670364603	20171107	00004457	OT	000000002143	-0000017601	00000000200	-0000000352	16	2017-12-06 15:31:35.056328
012	153	20305781720	20171108	00004458	FA	000000034837	00000295287	00000000200	00000005906	16	2017-12-06 15:31:35.056328
012	154	30670364603	20171108	00004459	FA	000000034838	00000175474	00000000200	00000003509	16	2017-12-06 15:31:35.056328
012	155	27173955451	20171108	00004460	FA	000000034896	00000124986	00000000200	00000002500	16	2017-12-06 15:31:35.056328
012	156	27222713140	20171108	00004461	FA	000000034897	00000082250	00000000200	00000001645	16	2017-12-06 15:31:35.056328
012	157	27222713140	20171108	00004462	FA	000000034898	00000053589	00000000200	00000001072	16	2017-12-06 15:31:35.056328
012	158	27100281819	20171108	00004463	FA	000000034899	00000044276	00000000200	00000000886	16	2017-12-06 15:31:35.056328
012	159	23163173824	20171108	00004464	FA	000000034900	00000022612	00000000200	00000000452	16	2017-12-06 15:31:35.056328
012	160	27125944707	20171108	00004465	FA	000000034901	00000011306	00000000200	00000000226	16	2017-12-06 15:31:35.056328
012	161	20274035170	20171108	00004466	FA	000000034902	00000123163	00000000200	00000002463	16	2017-12-06 15:31:35.056328
012	162	23289795464	20171108	00004467	FA	000000034903	00000054250	00000000200	00000001085	16	2017-12-06 15:31:35.056328
012	163	27056603560	20171108	00004468	FA	000000034904	00000083129	00000000200	00000001663	16	2017-12-06 15:31:35.056328
009	2	23289795464	20171101	00000001	FA	000500006103	00000144091	00000000200	00000002882	13	2017-12-11 11:41:12.19533
009	3	23289795464	20171101	00000001	FA	000500006104	00000019835	00000000200	00000000397	13	2017-12-11 11:41:12.19533
009	4	23289795464	20171101	00000001	FA	000500006105	00000205454	00000000200	00000004109	13	2017-12-11 11:41:12.19533
009	5	23289795464	20171101	00000001	FA	000500006106	00000040290	00000000200	00000000806	13	2017-12-11 11:41:12.19533
009	6	23289795464	20171101	00000001	FA	000500006107	00000020455	00000000200	00000000409	13	2017-12-11 11:41:12.19533
009	7	27394430655	20171101	00000001	FA	000500005843	00000031818	00000000200	00000000636	13	2017-12-11 11:41:12.19533
009	8	27363206641	20171101	00000001	FA	000500005844	00000127809	00000000200	00000002556	13	2017-12-11 11:41:12.19533
009	9	27259753274	20171101	00000001	FA	000500006108	00000061983	00000000200	00000001240	13	2017-12-11 11:41:12.19533
009	10	27236216638	20171101	00000001	FA	000500005845	00000040413	00000000200	00000000808	13	2017-12-11 11:41:12.19533
009	11	27236216638	20171101	00000001	FA	000500005846	00000040413	00000000200	00000000808	13	2017-12-11 11:41:12.19533
009	12	27222713140	20171101	00000001	FA	000500006111	00000056248	00000000200	00000001125	13	2017-12-11 11:41:12.19533
009	13	27280191235	20171101	00000001	FA	000500006112	00000094215	00000000200	00000001884	13	2017-12-11 11:41:12.19533
009	14	20139409591	20171101	00000001	FA	000500005847	00000091363	00000000200	00000001827	13	2017-12-11 11:41:12.19533
009	15	20082698508	20171101	00000001	FA	000500005848	00000052198	00000000200	00000001044	13	2017-12-11 11:41:12.19533
009	16	27056603560	20171101	00000001	FA	000500006114	00000118095	00000000200	00000002362	13	2017-12-11 11:41:12.19533
009	17	27239279908	20171101	00000001	FA	000500005849	00000042809	00000000200	00000000856	13	2017-12-11 11:41:12.19533
009	18	27952625514	20171101	00000001	FA	000500006115	00000239778	00000000200	00000004796	13	2017-12-11 11:41:12.19533
009	19	27952625514	20171101	00000001	FA	000500006117	00000096843	00000000200	00000001937	13	2017-12-11 11:41:12.19533
009	20	27952625514	20171101	00000001	FA	000500006118	00000240494	00000000200	00000004810	13	2017-12-11 11:41:12.19533
009	21	20182383016	20171101	00000001	FA	000500005850	00000070578	00000000200	00000001412	13	2017-12-11 11:41:12.19533
009	22	20305781720	20171101	00000001	FA	000500006119	00000146115	00000000200	00000002922	13	2017-12-11 11:41:12.19533
012	164	20054139285	20171108	00004469	FA	000000034905	00000061008	00000000200	00000001220	16	2017-12-06 15:31:35.056328
012	165	27266077632	20171108	00004470	FA	000000034906	00000138908	00000000200	00000002778	16	2017-12-06 15:31:35.056328
012	166	20305781720	20171108	00004471	FA	000000034907	00000160707	00000000200	00000003214	16	2017-12-06 15:31:35.056328
012	167	20305781720	20171108	00004472	FA	000000034908	00000043452	00000000200	00000000869	16	2017-12-06 15:31:35.056328
012	168	30670362317	20171108	00004473	FA	000000034909	00000053054	00000000200	00000001061	16	2017-12-06 15:31:35.056328
012	169	27952625514	20171108	00004474	FA	000000034910	00000373414	00000000200	00000007468	16	2017-12-06 15:31:35.056328
012	170	27952625514	20171108	00004475	FA	000000034911	00000213882	00000000200	00000004278	16	2017-12-06 15:31:35.056328
012	171	23289795464	20171108	00004476	FA	000000034912	00000169849	00000000200	00000003397	16	2017-12-06 15:31:35.056328
012	172	23289795464	20171108	00004477	FA	000000034913	00000004966	00000000199	00000000099	16	2017-12-06 15:31:35.056328
012	173	20261719887	20171108	00004478	FA	000000034914	00000172165	00000000200	00000003443	16	2017-12-06 15:31:35.056328
012	174	20261719887	20171108	00004479	FA	000000034915	00000217856	00000000200	00000004357	16	2017-12-06 15:31:35.056328
012	175	20261719887	20171108	00004480	FA	000000034916	00000232602	00000000200	00000004652	16	2017-12-06 15:31:35.056328
012	176	20261719887	20171108	00004481	FA	000000034917	00000325125	00000000200	00000006503	16	2017-12-06 15:31:35.056328
012	177	20261719887	20171108	00004482	FA	000000034918	00000029608	00000000200	00000000592	16	2017-12-06 15:31:35.056328
012	178	30670364603	20171108	00004483	FA	000000034919	00000187678	00000000200	00000003754	16	2017-12-06 15:31:35.056328
012	179	30670364603	20171108	00004484	FA	000000034920	00000142803	00000000200	00000002856	16	2017-12-06 15:31:35.056328
012	180	20261719887	20171108	00004485	FA	000000034921	00000066480	00000000200	00000001330	16	2017-12-06 15:31:35.056328
012	181	27248176607	20171108	00004486	FB	000000058993	00000050944	00000000200	00000001019	16	2017-12-06 15:31:35.056328
012	182	20313340792	20171108	00004487	FB	000000058996	00000028302	00000000200	00000000566	16	2017-12-06 15:31:35.056328
012	183	27346653502	20171108	00004488	FB	000000058997	00000092454	00000000200	00000001849	16	2017-12-06 15:31:35.056328
012	184	27363206641	20171108	00004489	FB	000000059050	00000064164	00000000200	00000001283	16	2017-12-06 15:31:35.056328
012	185	27363206641	20171108	00004490	FB	000000059051	00000102983	00000000200	00000002060	16	2017-12-06 15:31:35.056328
012	186	27363206641	20171108	00004491	FB	000000059052	00000107933	00000000200	00000002159	16	2017-12-06 15:31:35.056328
012	187	27313340339	20171108	00004492	FB	000000059053	00000077703	00000000200	00000001554	16	2017-12-06 15:31:35.056328
012	188	20313340792	20171108	00004493	FB	000000059055	00000037380	00000000200	00000000748	16	2017-12-06 15:31:35.056328
012	189	27112378257	20171108	00004494	FB	000000059056	00000084950	00000000200	00000001699	16	2017-12-06 15:31:35.056328
012	190	27215184760	20171108	00004495	FB	000000059057	00000049852	00000000200	00000000997	16	2017-12-06 15:31:35.056328
012	191	27219273431	20171108	00004496	FB	000000059058	00000097052	00000000200	00000001941	16	2017-12-06 15:31:35.056328
012	192	27121002650	20171108	00004497	FB	000000059059	00000072952	00000000200	00000001459	16	2017-12-06 15:31:35.056328
012	193	27044920102	20171108	00004498	FB	000000059060	00000037512	00000000200	00000000750	16	2017-12-06 15:31:35.056328
012	194	23331976199	20171108	00004499	FB	000000059064	00000022199	00000000200	00000000444	16	2017-12-06 15:31:35.056328
012	195	27248176607	20171108	00004500	FB	000000059065	00000127970	00000000200	00000002559	16	2017-12-06 15:31:35.056328
012	196	27394430655	20171108	00004501	FB	000000059067	00000054460	00000000200	00000001089	16	2017-12-06 15:31:35.056328
012	197	20201331790	20171108	00004502	FB	000000059013	00000067698	00000000200	00000001354	16	2017-12-06 15:31:35.056328
012	198	23241217264	20171109	00004503	FA	000000034934	00000362505	00000000200	00000007250	16	2017-12-06 15:31:35.056328
012	199	30670364603	20171109	00004504	FA	000000034944	00000032017	00000000200	00000000640	16	2017-12-06 15:31:35.056328
012	200	27188206463	20171114	00004505	FA	000000035109	00000127864	00000000200	00000002557	16	2017-12-06 15:31:35.056328
012	201	27173955451	20171114	00004506	FA	000000035110	00000123360	00000000200	00000002467	16	2017-12-06 15:31:35.056328
012	202	27222713140	20171114	00004507	FA	000000035111	00000049318	00000000200	00000000986	16	2017-12-06 15:31:35.056328
012	203	27222713140	20171114	00004508	FA	000000035112	00000049379	00000000200	00000000988	16	2017-12-06 15:31:35.056328
012	204	27367571123	20171114	00004509	FA	000000035113	00000127997	00000000200	00000002560	16	2017-12-06 15:31:35.056328
012	205	27367571123	20171114	00004510	FA	000000035114	00000086751	00000000200	00000001735	16	2017-12-06 15:31:35.056328
012	206	27125944707	20171114	00004511	FA	000000035115	00000076012	00000000200	00000001520	16	2017-12-06 15:31:35.056328
012	207	27064350809	20171114	00004512	FA	000000035116	00000138589	00000000200	00000002772	16	2017-12-06 15:31:35.056328
012	208	27064350809	20171114	00004513	FA	000000035117	00000114941	00000000200	00000002299	16	2017-12-06 15:31:35.056328
012	209	27064350809	20171114	00004514	FA	000000035118	00000061276	00000000200	00000001226	16	2017-12-06 15:31:35.056328
012	210	27056603560	20171114	00004515	FA	000000035119	00000065412	00000000200	00000001308	16	2017-12-06 15:31:35.056328
012	211	27952625514	20171114	00004516	FA	000000035120	00000434878	00000000200	00000008698	16	2017-12-06 15:31:35.056328
012	212	27952625514	20171114	00004517	FA	000000035121	00000255935	00000000200	00000005119	16	2017-12-06 15:31:35.056328
012	213	27952625514	20171114	00004518	FA	000000035122	00000389922	00000000200	00000007798	16	2017-12-06 15:31:35.056328
012	214	27952625514	20171114	00004519	FA	000000035123	00000082925	00000000200	00000001659	16	2017-12-06 15:31:35.056328
012	215	20054139285	20171114	00004520	FA	000000035124	00000053541	00000000200	00000001071	16	2017-12-06 15:31:35.056328
012	216	30670362317	20171114	00004521	FA	000000035125	00000139381	00000000200	00000002788	16	2017-12-06 15:31:35.056328
012	217	30670364603	20171114	00004522	FA	000000035126	00000201146	00000000200	00000004023	16	2017-12-06 15:31:35.056328
012	218	30670364603	20171114	00004523	FA	000000035127	00000159310	00000000200	00000003186	16	2017-12-06 15:31:35.056328
012	219	30670364603	20171114	00004524	FA	000000035128	00000192985	00000000200	00000003860	16	2017-12-06 15:31:35.056328
012	220	30670364603	20171114	00004525	FA	000000035129	00000113169	00000000200	00000002263	16	2017-12-06 15:31:35.056328
012	221	27952625514	20171114	00004526	FA	000000035130	00000013716	00000000200	00000000274	16	2017-12-06 15:31:35.056328
012	222	20305781720	20171114	00004527	FA	000000035131	00000063671	00000000200	00000001273	16	2017-12-06 15:31:35.056328
012	223	30670362317	20171114	00004528	FA	000000035153	00000005982	00000000201	00000000120	16	2017-12-06 15:31:35.056328
012	224	27215184760	20171114	00004529	FB	000000059306	00000031888	00000000200	00000000638	16	2017-12-06 15:31:35.056328
011	2	20367604302	20171102	50027955	FA	000500027955	00000055931	00000000200	00000001119	15	2017-12-04 10:44:40.26174
011	3	27942269566	20171102	50027956	FA	000500027956	00000037158	00000000200	00000000743	15	2017-12-04 10:44:40.26174
011	4	20173955740	20171102	50018154	FA	000500018154	00000064979	00000000200	00000001300	15	2017-12-04 10:44:40.26174
011	5	27346653502	20171102	50027959	FA	000500027959	00000130291	00000000200	00000002606	15	2017-12-04 10:44:40.26174
011	6	27293985311	20171102	50027960	FA	000500027960	00000026466	00000000200	00000000529	15	2017-12-04 10:44:40.26174
011	7	27346653340	20171102	50027961	FA	000500027961	00000065940	00000000200	00000001319	15	2017-12-04 10:44:40.26174
011	8	20226157833	20171102	50018157	FA	000500018157	00000066115	00000000200	00000001322	15	2017-12-04 10:44:40.26174
011	9	27044920102	20171102	50027965	FA	000500027965	00000027832	00000000200	00000000557	15	2017-12-04 10:44:40.26174
011	10	20313340792	20171102	50027972	FA	000500027972	00000047684	00000000200	00000000954	15	2017-12-04 10:44:40.26174
011	11	27394430655	20171102	50027973	FA	000500027973	00000073696	00000000200	00000001474	15	2017-12-04 10:44:40.26174
011	12	27044920382	20171102	50027975	FA	000500027975	00000027978	00000000200	00000000560	15	2017-12-04 10:44:40.26174
011	13	27368604483	20171102	50018172	FA	000500018172	00000107599	00000000200	00000002152	15	2017-12-04 10:44:40.26174
011	14	20943149942	20171102	50027977	FA	000500027977	00000040419	00000000200	00000000808	15	2017-12-04 10:44:40.26174
011	15	27259774328	20171102	50027978	FA	000500027978	00000051475	00000000200	00000001029	15	2017-12-04 10:44:40.26174
011	16	20226158074	20171102	50027979	FA	000500027979	00000044319	00000000200	00000000886	15	2017-12-04 10:44:40.26174
011	17	27259753274	20171102	50018180	FA	000500018180	00000014882	00000000200	00000000298	15	2017-12-04 10:44:40.26174
011	18	20388075806	20171102	50027981	FA	000500027981	00000056504	00000000200	00000001130	15	2017-12-04 10:44:40.26174
011	19	27214583661	20171102	50027983	FA	000500027983	00000031612	00000000200	00000000632	15	2017-12-04 10:44:40.26174
011	20	27952625514	20171102	50018182	FA	000500018182	00000517909	00000000200	00000010358	15	2017-12-04 10:44:40.26174
011	21	20215586139	20171104	50028012	FA	000500028012	00000043795	00000000200	00000000876	15	2017-12-04 10:44:40.26174
011	22	27952625514	20171104	50018238	FA	000500018238	00000149760	00000000200	00000002995	15	2017-12-04 10:44:40.26174
011	23	27346653502	20171106	50028111	FA	000500028111	00000118428	00000000200	00000002369	15	2017-12-04 10:44:40.26174
011	24	20222014574	20171106	50028112	FA	000500028112	00000055652	00000000200	00000001113	15	2017-12-04 10:44:40.26174
011	25	27346653340	20171106	50028113	FA	000500028113	00000093100	00000000200	00000001862	15	2017-12-04 10:44:40.26174
011	26	27044920102	20171106	50028118	FA	000500028118	00000059375	00000000200	00000001188	15	2017-12-04 10:44:40.26174
011	27	20267029114	20171106	50028120	FA	000500028120	00000022315	00000000200	00000000446	15	2017-12-04 10:44:40.26174
011	28	27313340754	20171106	50028126	FA	000500028126	00000055751	00000000200	00000001115	15	2017-12-04 10:44:40.26174
011	29	20313340792	20171106	50028127	FA	000500028127	00000054849	00000000200	00000001097	15	2017-12-04 10:44:40.26174
011	30	27394430655	20171106	50028128	FA	000500028128	00000116171	00000000200	00000002323	15	2017-12-04 10:44:40.26174
011	31	27044920382	20171106	50028130	FA	000500028130	00000035224	00000000200	00000000704	15	2017-12-04 10:44:40.26174
011	32	27368604483	20171106	50018290	FA	000500018290	00000071615	00000000200	00000001432	15	2017-12-04 10:44:40.26174
011	33	20943149942	20171106	50028133	FA	000500028133	00000064191	00000000200	00000001284	15	2017-12-04 10:44:40.26174
011	34	20226158074	20171106	50028135	FA	000500028135	00000022315	00000000200	00000000446	15	2017-12-04 10:44:40.26174
011	35	20388075806	20171106	50028137	FA	000500028137	00000218127	00000000200	00000004363	15	2017-12-04 10:44:40.26174
011	36	27214583661	20171106	50028139	FA	000500028139	00000054120	00000000200	00000001082	15	2017-12-04 10:44:40.26174
011	37	27952625514	20171106	50018297	FA	000500018297	00001331173	00000000200	00000026623	15	2017-12-04 10:44:40.26174
011	38	27952625514	20171106	50018298	FA	000500018298	00000465975	00000000200	00000009319	15	2017-12-04 10:44:40.26174
011	39	20290347158	20171107	50028142	FA	000500028142	00000022315	00000000200	00000000446	15	2017-12-04 10:44:40.26174
011	40	20367604302	20171109	50028319	FA	000500028319	00000213424	00000000200	00000004268	15	2017-12-04 10:44:40.26174
011	41	20173955740	20171109	50018407	FA	000500018407	00000103283	00000000200	00000002066	15	2017-12-04 10:44:40.26174
011	42	27346653502	20171109	50028323	FA	000500028323	00000072208	00000000200	00000001444	15	2017-12-04 10:44:40.26174
011	43	27293985311	20171109	50028324	FA	000500028324	00000017612	00000000200	00000000352	15	2017-12-04 10:44:40.26174
011	44	27346653340	20171109	50028326	FA	000500028326	00000075545	00000000200	00000001511	15	2017-12-04 10:44:40.26174
011	45	20226157833	20171109	50018411	FA	000500018411	00000111328	00000000200	00000002227	15	2017-12-04 10:44:40.26174
011	46	27943434803	20171109	50028328	FA	000500028328	00000183166	00000000200	00000003663	15	2017-12-04 10:44:40.26174
011	47	20267029114	20171109	50028334	FA	000500028334	00000022315	00000000200	00000000446	15	2017-12-04 10:44:40.26174
011	48	27226803233	20171109	50028338	FA	000500028338	00000183676	00000000200	00000003674	15	2017-12-04 10:44:40.26174
011	49	27313340754	20171109	50028342	FA	000500028342	00000120049	00000000200	00000002401	15	2017-12-04 10:44:40.26174
011	50	27394430655	20171109	50028343	FA	000500028343	00000066344	00000000200	00000001327	15	2017-12-04 10:44:40.26174
011	51	27368604483	20171109	50018423	FA	000500018423	00000125336	00000000200	00000002507	15	2017-12-04 10:44:40.26174
011	52	20943149942	20171109	50028349	FA	000500028349	00000085001	00000000200	00000001700	15	2017-12-04 10:44:40.26174
011	53	27259774328	20171109	50028350	FA	000500028350	00000018620	00000000200	00000000372	15	2017-12-04 10:44:40.26174
011	54	20226158074	20171109	50028352	FA	000500028352	00000022315	00000000200	00000000446	15	2017-12-04 10:44:40.26174
011	55	20388075806	20171109	50028354	FA	000500028354	00000143561	00000000200	00000002871	15	2017-12-04 10:44:40.26174
011	56	27214583661	20171109	50028356	FA	000500028356	00000055834	00000000200	00000001117	15	2017-12-04 10:44:40.26174
011	57	27952625514	20171109	50018433	FA	000500018433	00000168862	00000000200	00000003377	15	2017-12-04 10:44:40.26174
011	58	20224184515	20171109	50028357	FA	000500028357	00000099740	00000000200	00000001995	15	2017-12-04 10:44:40.26174
011	59	20215586139	20171111	50028405	FA	000500028405	00000050106	00000000200	00000001002	15	2017-12-04 10:44:40.26174
011	60	27215184760	20171113	50028463	FA	000500028463	00000032241	00000000200	00000000645	15	2017-12-04 10:44:40.26174
011	61	27346653502	20171113	50028467	FA	000500028467	00000127813	00000000200	00000002556	15	2017-12-04 10:44:40.26174
011	62	27044920102	20171113	50028470	FA	000500028470	00000077038	00000000200	00000001541	15	2017-12-04 10:44:40.26174
011	63	20267029114	20171113	50028472	FA	000500028472	00000022315	00000000200	00000000446	15	2017-12-04 10:44:40.26174
011	64	27226803233	20171113	50028477	FA	000500028477	00000085849	00000000200	00000001717	15	2017-12-04 10:44:40.26174
011	65	27342957760	20171113	50028480	FA	000500028480	00000039281	00000000200	00000000786	15	2017-12-04 10:44:40.26174
011	66	27313340754	20171113	50028482	FA	000500028482	00000092545	00000000200	00000001851	15	2017-12-04 10:44:40.26174
011	67	20313340792	20171113	50028483	FA	000500028483	00000053545	00000000200	00000001071	15	2017-12-04 10:44:40.26174
011	68	27394430655	20171113	50028484	FA	000500028484	00000223368	00000000200	00000004467	15	2017-12-04 10:44:40.26174
011	69	27044920382	20171113	50028485	FA	000500028485	00000034745	00000000200	00000000695	15	2017-12-04 10:44:40.26174
011	70	20943149942	20171113	50028487	FA	000500028487	00000105052	00000000200	00000002101	15	2017-12-04 10:44:40.26174
011	71	20226158074	20171113	50028489	FA	000500028489	00000055420	00000000200	00000001108	15	2017-12-04 10:44:40.26174
011	72	27305781342	20171113	50028490	FA	000500028490	00000114616	00000000200	00000002292	15	2017-12-04 10:44:40.26174
011	73	27259753274	20171113	50018546	FA	000500018546	00000037205	00000000200	00000000744	15	2017-12-04 10:44:40.26174
011	74	27214583661	20171113	50028493	FA	000500028493	00000064139	00000000200	00000001283	15	2017-12-04 10:44:40.26174
011	75	27952625514	20171113	50018549	FA	000500018549	00000538737	00000000200	00000010775	15	2017-12-04 10:44:40.26174
011	76	20290347158	20171114	50028515	FA	000500028515	00000074956	00000000200	00000001499	15	2017-12-04 10:44:40.26174
011	77	20367604302	20171116	50028702	FA	000500028702	00000140994	00000000200	00000002820	15	2017-12-04 10:44:40.26174
011	78	27215184760	20171116	50028704	FA	000500028704	00000056429	00000000200	00000001129	15	2017-12-04 10:44:40.26174
011	79	27942269566	20171116	50028706	FA	000500028706	00000062533	00000000200	00000001251	15	2017-12-04 10:44:40.26174
011	80	20173955740	20171116	50018676	FA	000500018676	00000099651	00000000200	00000001993	15	2017-12-04 10:44:40.26174
011	81	27346653502	20171116	50028710	FA	000500028710	00000147734	00000000200	00000002955	15	2017-12-04 10:44:40.26174
011	82	27293985311	20171116	50028711	FA	000500028711	00000027040	00000000200	00000000541	15	2017-12-04 10:44:40.26174
011	83	27346653340	20171116	50028714	FA	000500028714	00000322227	00000000200	00000006445	15	2017-12-04 10:44:40.26174
011	84	27943434803	20171116	50028717	FA	000500028717	00000087411	00000000200	00000001748	15	2017-12-04 10:44:40.26174
011	85	27044920102	20171116	50028720	FA	000500028720	00000050220	00000000200	00000001004	15	2017-12-04 10:44:40.26174
011	86	20313340792	20171116	50028730	FA	000500028730	00000059929	00000000200	00000001199	15	2017-12-04 10:44:40.26174
011	87	27394430655	20171116	50028731	FA	000500028731	00000103408	00000000200	00000002068	15	2017-12-04 10:44:40.26174
011	88	27044920382	20171116	50028733	FA	000500028733	00000027597	00000000200	00000000552	15	2017-12-04 10:44:40.26174
011	89	27368604483	20171116	50018692	FA	000500018692	00000129046	00000000200	00000002581	15	2017-12-04 10:44:40.26174
011	90	20943149942	20171116	50028738	FA	000500028738	00000049266	00000000200	00000000985	15	2017-12-04 10:44:40.26174
011	91	27259774328	20171116	50028739	FA	000500028739	00000014882	00000000200	00000000298	15	2017-12-04 10:44:40.26174
011	92	20226158074	20171116	50028741	FA	000500028741	00000031571	00000000200	00000000631	15	2017-12-04 10:44:40.26174
011	93	27305781342	20171116	50028742	FA	000500028742	00000016685	00000000200	00000000334	15	2017-12-04 10:44:40.26174
011	94	27259753274	20171116	50018700	FA	000500018700	00000029764	00000000200	00000000595	15	2017-12-04 10:44:40.26174
011	95	20388075806	20171116	50028744	FA	000500028744	00000158613	00000000200	00000003172	15	2017-12-04 10:44:40.26174
011	96	27214583661	20171116	50028746	FA	000500028746	00000017707	00000000200	00000000354	15	2017-12-04 10:44:40.26174
011	97	27952625514	20171116	50018703	FA	000500018703	00000501323	00000000200	00000010026	15	2017-12-04 10:44:40.26174
011	98	27952625514	20171116	50018704	FA	000500018704	00000068020	00000000200	00000001360	15	2017-12-04 10:44:40.26174
011	99	20215586139	20171118	50028799	FA	000500028799	00000054558	00000000200	00000001091	15	2017-12-04 10:44:40.26174
011	100	20290347158	20171118	50028801	FA	000500028801	00000047113	00000000200	00000000942	15	2017-12-04 10:44:40.26174
011	101	20367604302	20171123	50029051	FA	000500029051	00000166784	00000000200	00000003336	15	2017-12-04 10:44:40.26174
011	102	27215184760	20171123	50029053	FA	000500029053	00000022652	00000000200	00000000453	15	2017-12-04 10:44:40.26174
011	103	27942269566	20171123	50029055	FA	000500029055	00000030525	00000000200	00000000610	15	2017-12-04 10:44:40.26174
011	104	20173955740	20171123	50018905	FA	000500018905	00000042787	00000000200	00000000856	15	2017-12-04 10:44:40.26174
011	105	27346653502	20171123	50029059	FA	000500029059	00000171366	00000000200	00000003427	15	2017-12-04 10:44:40.26174
011	106	27346653340	20171123	50029062	FA	000500029062	00000044086	00000000200	00000000882	15	2017-12-04 10:44:40.26174
011	107	27943434803	20171123	50029064	FA	000500029064	00000079184	00000000200	00000001584	15	2017-12-04 10:44:40.26174
011	108	27044920102	20171123	50029067	FA	000500029067	00000103741	00000000200	00000002075	15	2017-12-04 10:44:40.26174
011	109	20267029114	20171123	50029070	FA	000500029070	00000023557	00000000200	00000000471	15	2017-12-04 10:44:40.26174
011	110	23331976199	20171123	50029075	FA	000500029075	00000291264	00000000200	00000005825	15	2017-12-04 10:44:40.26174
011	111	20313340792	20171123	50029079	FA	000500029079	00000129314	00000000200	00000002586	15	2017-12-04 10:44:40.26174
011	112	27394430655	20171123	50029080	FA	000500029080	00000060145	00000000200	00000001203	15	2017-12-04 10:44:40.26174
011	113	27368604483	20171123	50018924	FA	000500018924	00000081827	00000000200	00000001637	15	2017-12-04 10:44:40.26174
011	114	20943149942	20171123	50029085	FA	000500029085	00000090329	00000000200	00000001807	15	2017-12-04 10:44:40.26174
011	115	27259774328	20171123	50029086	FA	000500029086	00000081718	00000000200	00000001634	15	2017-12-04 10:44:40.26174
011	116	20226158074	20171123	50029088	FA	000500029088	00000025691	00000000200	00000000514	15	2017-12-04 10:44:40.26174
011	117	27305781342	20171123	50029089	FA	000500029089	00000082783	00000000200	00000001656	15	2017-12-04 10:44:40.26174
011	118	20388075806	20171123	50029091	FA	000500029091	00000043296	00000000200	00000000866	15	2017-12-04 10:44:40.26174
011	119	27214583661	20171123	50029093	FA	000500029093	00000082796	00000000200	00000001656	15	2017-12-04 10:44:40.26174
011	120	27952625514	20171123	50018934	FA	000500018934	00000465611	00000000200	00000009312	15	2017-12-04 10:44:40.26174
011	121	27952625514	20171123	50018935	FA	000500018935	00000223986	00000000200	00000004480	15	2017-12-04 10:44:40.26174
011	122	27952625514	20171124	50018959	FA	000500018959	00007592924	00000000200	00000151858	15	2017-12-04 10:44:40.26174
011	123	20290347158	20171125	50029150	FA	000500029150	00000029246	00000000200	00000000585	15	2017-12-04 10:44:40.26174
011	124	27346653502	20171127	50029214	FA	000500029214	00000116121	00000000200	00000002322	15	2017-12-04 10:44:40.26174
011	125	27346653340	20171127	50029215	FA	000500029215	00000090776	00000000200	00000001816	15	2017-12-04 10:44:40.26174
011	126	27044920102	20171127	50029220	FA	000500029220	00000047557	00000000200	00000000951	15	2017-12-04 10:44:40.26174
011	127	20267029114	20171127	50029222	FA	000500029222	00000023557	00000000200	00000000471	15	2017-12-04 10:44:40.26174
011	128	23331976199	20171127	50029227	FA	000500029227	00000282991	00000000200	00000005660	15	2017-12-04 10:44:40.26174
011	129	23331976199	20171127	50029228	FA	000500029228	00000016436	00000000200	00000000329	15	2017-12-04 10:44:40.26174
011	130	27226803233	20171127	50029230	FA	000500029230	00000101175	00000000200	00000002024	15	2017-12-04 10:44:40.26174
011	131	27342957760	20171127	50029233	FA	000500029233	00000132897	00000000200	00000002658	15	2017-12-04 10:44:40.26174
011	132	20313340792	20171127	50029234	FA	000500029234	00000026399	00000000200	00000000528	15	2017-12-04 10:44:40.26174
011	133	27394430655	20171127	50029235	FA	000500029235	00000475163	00000000200	00000009503	15	2017-12-04 10:44:40.26174
011	134	27044920382	20171127	50029237	FA	000500029237	00000032923	00000000200	00000000658	15	2017-12-04 10:44:40.26174
011	135	27368604483	20171127	50019045	FA	000500019045	00000055745	00000000200	00000001115	15	2017-12-04 10:44:40.26174
011	136	20943149942	20171127	50029240	FA	000500029240	00000098537	00000000200	00000001971	15	2017-12-04 10:44:40.26174
011	137	27259774328	20171127	50029241	FA	000500029241	00000046547	00000000200	00000000931	15	2017-12-04 10:44:40.26174
011	138	20226158074	20171127	50029242	FA	000500029242	00000068597	00000000200	00000001372	15	2017-12-04 10:44:40.26174
011	139	20388075806	20171127	50029244	FA	000500029244	00000129538	00000000200	00000002591	15	2017-12-04 10:44:40.26174
011	140	27214583661	20171127	50029246	FA	000500029246	00000032016	00000000200	00000000640	15	2017-12-04 10:44:40.26174
011	141	27952625514	20171127	50019054	FA	000500019054	00000640160	00000000200	00000012803	15	2017-12-04 10:44:40.26174
011	142	27952625514	20171127	50019055	FA	000500019055	00000211197	00000000200	00000004224	15	2017-12-04 10:44:40.26174
011	143	20367604302	20171130	50029475	FA	000500029475	00000141101	00000000200	00000002822	15	2017-12-04 10:44:40.26174
011	144	27942269566	20171130	50029478	FA	000500029478	00000050220	00000000200	00000001004	15	2017-12-04 10:44:40.26174
011	145	20173955740	20171130	50019204	FA	000500019204	00000102647	00000000200	00000002053	15	2017-12-04 10:44:40.26174
011	146	27346653502	20171130	50029481	FA	000500029481	00000109359	00000000200	00000002187	15	2017-12-04 10:44:40.26174
011	147	27346653340	20171130	50029483	FA	000500029483	00000154868	00000000200	00000003097	15	2017-12-04 10:44:40.26174
011	148	27943434803	20171130	50029485	FA	000500029485	00000154446	00000000200	00000003089	15	2017-12-04 10:44:40.26174
011	149	27044920102	20171130	50029488	FA	000500029488	00000055381	00000000200	00000001108	15	2017-12-04 10:44:40.26174
011	150	23331976199	20171130	50029494	FA	000500029494	00000248074	00000000200	00000004961	15	2017-12-04 10:44:40.26174
011	151	27226803233	20171130	50029496	FA	000500029496	00000068847	00000000200	00000001377	15	2017-12-04 10:44:40.26174
011	152	20313340792	20171130	50029498	FA	000500029498	00000025057	00000000200	00000000501	15	2017-12-04 10:44:40.26174
011	153	20224184515	20171130	50029499	FA	000500029499	00000094571	00000000200	00000001891	15	2017-12-04 10:44:40.26174
011	154	27394430655	20171130	50029500	FA	000500029500	00000024175	00000000200	00000000483	15	2017-12-04 10:44:40.26174
011	155	27368604483	20171130	50019221	FA	000500019221	00000077768	00000000200	00000001555	15	2017-12-04 10:44:40.26174
011	156	20943149942	20171130	50029505	FA	000500029505	00000073795	00000000200	00000001476	15	2017-12-04 10:44:40.26174
011	157	27259774328	20171130	50029506	FA	000500029506	00000018620	00000000200	00000000372	15	2017-12-04 10:44:40.26174
011	158	20226158074	20171130	50029507	FA	000500029507	00000070266	00000000200	00000001405	15	2017-12-04 10:44:40.26174
011	159	27305781342	20171130	50029508	FA	000500029508	00000038536	00000000200	00000000771	15	2017-12-04 10:44:40.26174
011	160	27259753274	20171130	50019228	FA	000500019228	00000059528	00000000200	00000001191	15	2017-12-04 10:44:40.26174
011	161	20388075806	20171130	50029510	FA	000500029510	00000090600	00000000200	00000001812	15	2017-12-04 10:44:40.26174
011	162	27214583661	20171130	50029512	FA	000500029512	00000062838	00000000200	00000001257	15	2017-12-04 10:44:40.26174
011	163	27952625514	20171130	50019231	FA	000500019231	00000529318	00000000200	00000010586	15	2017-12-04 10:44:40.26174
011	164	27952625514	20171130	50019232	FA	000500019232	00000415915	00000000200	00000008318	15	2017-12-04 10:44:40.26174
012	225	27121002650	20171114	00004530	FB	000000059308	00000057290	00000000200	00000001146	16	2017-12-06 15:31:35.056328
012	226	27044920102	20171114	00004531	FB	000000059309	00000046100	00000000200	00000000922	16	2017-12-06 15:31:35.056328
012	227	27313340339	20171114	00004532	FB	000000059310	00000135230	00000000200	00000002705	16	2017-12-06 15:31:35.056328
012	228	27226803233	20171114	00004533	FB	000000059311	00000076653	00000000200	00000001533	16	2017-12-06 15:31:35.056328
012	229	23331976199	20171114	00004534	FB	000000059314	00000006213	00000000200	00000000124	16	2017-12-06 15:31:35.056328
012	230	23331976199	20171114	00004535	FB	000000059315	00000109845	00000000200	00000002197	16	2017-12-06 15:31:35.056328
012	231	23331976199	20171114	00004536	FB	000000059316	00000059630	00000000200	00000001193	16	2017-12-06 15:31:35.056328
012	232	27325453465	20171114	00004537	FB	000000059317	00000081588	00000000200	00000001632	16	2017-12-06 15:31:35.056328
012	233	27248176607	20171114	00004538	FB	000000059318	00000185822	00000000200	00000003716	16	2017-12-06 15:31:35.056328
012	234	27248176607	20171114	00004539	FB	000000059319	00000077364	00000000200	00000001547	16	2017-12-06 15:31:35.056328
012	235	23331976199	20171114	00004540	FB	000000059320	00000162387	00000000200	00000003248	16	2017-12-06 15:31:35.056328
012	236	23331976199	20171114	00004541	FB	000000059321	00000035873	00000000200	00000000717	16	2017-12-06 15:31:35.056328
012	237	20082698508	20171114	00004542	FB	000000059322	00000063892	00000000200	00000001278	16	2017-12-06 15:31:35.056328
012	238	27394430655	20171114	00004543	FB	000000059323	00000100712	00000000200	00000002014	16	2017-12-06 15:31:35.056328
012	239	27121002650	20171114	00004544	FB	000000059324	00000009099	00000000200	00000000182	16	2017-12-06 15:31:35.056328
012	240	27226803233	20171114	00004545	FB	000000059325	00000019750	00000000200	00000000395	16	2017-12-06 15:31:35.056328
012	241	27248176607	20171114	00004546	FB	000000059327	00000026692	00000000200	00000000534	16	2017-12-06 15:31:35.056328
012	242	23331976199	20171114	00004547	FB	000000059328	00000036530	00000000200	00000000731	16	2017-12-06 15:31:35.056328
012	243	30670362317	20171114	00004548	OT	000000002158	-0000002871	00000000199	-0000000057	16	2017-12-06 15:31:35.056328
012	244	30670362317	20171115	00004549	FA	000000035247	00000183889	00000000200	00000003678	16	2017-12-06 15:31:35.056328
014	2	20125941991	20171101	80762206	FA	028800256864	00000135756	00000000200	00000002715	3	2017-12-05 15:57:19.680123
014	3	11111111111	20171101	09890873	FA	029100163658	00000617662	00000000600	00000037060	3	2017-12-05 15:57:19.680123
014	4	11111111111	20171101	09890875	FA	029100163659	00000200103	00000000600	00000012006	3	2017-12-05 15:57:19.680123
014	5	20937978473	20171101	80762207	FA	029100163662	00000275641	00000000200	00000005513	3	2017-12-05 15:57:19.680123
014	6	20937978473	20171101	80762208	FA	029100163663	00000061032	00000000200	00000001221	3	2017-12-05 15:57:19.680123
014	7	27236216719	20171101	80762225	FA	029100163698	00000351525	00000000200	00000007031	3	2017-12-05 15:57:19.680123
014	8	20178791843	20171101	80303069	FA	028800093852	00000498515	00000000200	00000009971	3	2017-12-05 15:57:19.680123
014	9	20178791843	20171101	80303070	FA	029100065461	00000362086	00000000200	00000007242	3	2017-12-05 15:57:19.680123
014	10	11111111111	20171101	09890894	FA	028800256883	00000649862	00000000600	00000038991	3	2017-12-05 15:57:19.680123
014	11	11111111111	20171101	09890896	FA	029100163704	00000516022	00000000600	00000030961	3	2017-12-05 15:57:19.680123
014	12	20178791843	20171101	80303071	FA	029100065463	00000204115	00000000200	00000004083	3	2017-12-05 15:57:19.680123
014	13	20178791843	20171101	80303072	FA	029100065464	00000174932	00000000200	00000003499	3	2017-12-05 15:57:19.680123
014	14	11111111111	20171101	09890897	FA	029100163706	00000310591	00000000600	00000018635	3	2017-12-05 15:57:19.680123
014	15	27200130079	20171101	09890906	FA	028800256890	00000368842	00000000200	00000007377	3	2017-12-05 15:57:19.680123
014	16	30714121606	20171101	09823537	FA	028800093855	00000123272	00000000200	00000002465	3	2017-12-05 15:57:19.680123
014	17	30714121606	20171101	00981604	NC	028800006164	-0000020130	00000000200	-0000000403	3	2017-12-05 15:57:19.680123
014	18	30714121606	20171101	09823538	FA	028800093856	00000014830	00000000200	00000000297	3	2017-12-05 15:57:19.680123
014	19	27290859560	20171101	09823542	FA	029100065476	00000099572	00000000200	00000001991	3	2017-12-05 15:57:19.680123
014	20	20241218539	20171101	09890924	FA	029100163772	00000255112	00000000200	00000005102	3	2017-12-05 15:57:19.680123
014	21	20241218539	20171101	00986297	NC	029100011282	-0000037898	00000000200	-0000000758	3	2017-12-05 15:57:19.680123
014	22	20241218539	20171101	09890927	FA	029100163779	00000026900	00000000200	00000000538	3	2017-12-05 15:57:19.680123
014	23	20083960907	20171101	80762276	FA	029100163794	00000449674	00000000200	00000008993	3	2017-12-05 15:57:19.680123
014	24	11111111111	20171101	09890936	FA	029100163799	00000248497	00000000600	00000014910	3	2017-12-05 15:57:19.680123
014	25	11111111111	20171101	80762277	FA	029100163800	00000082900	00000000600	00000004974	3	2017-12-05 15:57:19.680123
014	26	11111111111	20171101	80762278	FA	029100163801	00000082201	00000000600	00000004932	3	2017-12-05 15:57:19.680123
014	27	11111111111	20171101	80762279	FA	029100163802	00000159129	00000000600	00000009548	3	2017-12-05 15:57:19.680123
014	28	11111111111	20171101	09890941	FA	029100163813	00000443558	00000000600	00000026613	3	2017-12-05 15:57:19.680123
014	29	11111111111	20171101	09890943	FA	028800256932	00000739755	00000000600	00000044385	3	2017-12-05 15:57:19.680123
014	30	20290121222	20171102	80762305	FA	029100163833	00000080195	00000000200	00000001604	3	2017-12-05 15:57:19.680123
014	31	27222713140	20171102	80303091	FA	029100065506	00001081242	00000000200	00000021625	3	2017-12-05 15:57:19.680123
014	32	27056603560	20171102	80303092	FA	029100065503	00001843076	00000000200	00000036862	3	2017-12-05 15:57:19.680123
014	33	20937978473	20171102	80762314	FA	029100163839	00000169378	00000000200	00000003388	3	2017-12-05 15:57:19.680123
014	34	11111111111	20171102	80762315	FA	028800257003	00000321856	00000000600	00000019312	3	2017-12-05 15:57:19.680123
014	35	11111111111	20171102	09890978	FA	028800257004	00000038576	00000000600	00000002315	3	2017-12-05 15:57:19.680123
014	36	27215182997	20171102	80762316	FA	028800257007	00000619744	00000000200	00000012395	3	2017-12-05 15:57:19.680123
014	37	30715184040	20171102	80303102	FA	029100065526	00000119368	00000000200	00000002387	3	2017-12-05 15:57:19.680123
014	38	30670357577	20171102	80303108	FA	029100065544	00000443866	00000000200	00000008878	3	2017-12-05 15:57:19.680123
014	39	27259753274	20171102	80303109	FA	028800093892	00000097058	00000000200	00000001941	3	2017-12-05 15:57:19.680123
014	40	11111111111	20171102	80762341	FA	028800257022	00000359016	00000000600	00000021541	3	2017-12-05 15:57:19.680123
014	41	20206714442	20171102	80762343	FA	028800257029	00000304968	00000000200	00000006099	3	2017-12-05 15:57:19.680123
014	42	27221851833	20171102	09891020	FA	028800257051	00000111188	00000000200	00000002224	3	2017-12-05 15:57:19.680123
014	43	11111111111	20171102	80762351	FA	028800257053	00000299219	00000000600	00000017953	3	2017-12-05 15:57:19.680123
014	44	20179005833	20171102	80303119	FA	028800093903	00000039338	00000000200	00000000787	3	2017-12-05 15:57:19.680123
014	45	20213541804	20171102	80303129	FA	028800093921	00000413993	00000000200	00000008280	3	2017-12-05 15:57:19.680123
014	46	11111111111	20171102	09891069	FA	028800257096	00000217107	00000000600	00000013027	3	2017-12-05 15:57:19.680123
014	47	11111111111	20171102	80762391	FA	028800257099	00000081351	00000000600	00000004881	3	2017-12-05 15:57:19.680123
014	48	11111111111	20171102	09891071	FA	028800257101	00000198994	00000000600	00000011940	3	2017-12-05 15:57:19.680123
014	49	23241216349	20171102	80303134	FA	028800093929	00000377972	00000000200	00000007560	3	2017-12-05 15:57:19.680123
014	50	30670364603	20171102	09823586	FA	028800093931	00000360947	00000000200	00000007219	3	2017-12-05 15:57:19.680123
014	51	11111111111	20171102	80762397	FA	028800257107	00000113836	00000000600	00000006830	3	2017-12-05 15:57:19.680123
014	52	23226940294	20171103	80762431	FA	029100164066	00000066077	00000000200	00000001322	3	2017-12-05 15:57:19.680123
014	53	20924247410	20171103	80762432	FA	028800257122	00000377009	00000000200	00000007540	3	2017-12-05 15:57:19.680123
014	54	20163191505	20171103	80762438	FA	029100164096	00000257245	00000000200	00000005145	3	2017-12-05 15:57:19.680123
014	55	30670357577	20171103	80303144	FA	029100065582	00000019518	00000000200	00000000390	3	2017-12-05 15:57:19.680123
014	56	11111111111	20171103	80762443	FA	029100164122	00000180194	00000000600	00000010811	3	2017-12-05 15:57:19.680123
014	57	11111111111	20171103	09891108	FA	028800257123	00000284183	00000000600	00000017051	3	2017-12-05 15:57:19.680123
014	58	20015318059	20171103	09823592	FA	029100065583	00000094732	00000000200	00000001895	3	2017-12-05 15:57:19.680123
014	59	27100281819	20171103	80303147	FA	029100065587	00000376109	00000000200	00000007523	3	2017-12-05 15:57:19.680123
014	60	20202363637	20171103	80303149	FA	028800093938	00000263931	00000000200	00000005279	3	2017-12-05 15:57:19.680123
014	61	20236216552	20171103	09891132	FA	029100164173	00000192602	00000000200	00000003852	3	2017-12-05 15:57:19.680123
014	62	27952625514	20171103	08035314	NC	029100006356	-0000044350	00000000200	-0000000887	3	2017-12-05 15:57:19.680123
014	63	27236216719	20171103	80762462	FA	029100164184	00000282475	00000000200	00000005650	3	2017-12-05 15:57:19.680123
014	64	11111111111	20171103	80762463	FA	029100164185	00000222372	00000000600	00000013342	3	2017-12-05 15:57:19.680123
014	65	11111111111	20171103	80762464	FA	029100164188	00000095152	00000000600	00000005709	3	2017-12-05 15:57:19.680123
014	66	11111111111	20171103	80762465	FA	029100164190	00000307014	00000000600	00000018421	3	2017-12-05 15:57:19.680123
014	67	11111111111	20171103	09891136	FA	029100164191	00000092999	00000000600	00000005580	3	2017-12-05 15:57:19.680123
014	68	27952625514	20171103	80303157	FA	028800093946	00000008070	00000000200	00000000161	3	2017-12-05 15:57:19.680123
014	69	20226157833	20171103	80303158	FA	028800093948	00000844881	00000000200	00000016898	3	2017-12-05 15:57:19.680123
014	70	20331132269	20171103	80303159	FA	028800093951	00000022299	00000000200	00000000446	3	2017-12-05 15:57:19.680123
014	71	30710404670	20171103	09823604	FA	028800093956	00000484803	00000000200	00000009696	3	2017-12-05 15:57:19.680123
014	72	11111111111	20171103	09891168	FA	028800257225	00000346909	00000000600	00000020815	3	2017-12-05 15:57:19.680123
014	73	23125943454	20171103	09891196	FA	028800257315	00000736907	00000000200	00000014738	3	2017-12-05 15:57:19.680123
014	74	23289795464	20171103	80303177	FA	028800093998	00000369928	00000000200	00000007399	3	2017-12-05 15:57:19.680123
014	75	23289795464	20171103	80303178	FA	028800094000	00001885066	00000000200	00000037701	3	2017-12-05 15:57:19.680123
014	76	20073299498	20171103	09823608	FA	028800093991	00000073539	00000000200	00000001471	3	2017-12-05 15:57:19.680123
009	23	20305781720	20171101	00000001	FA	000500006120	00000081323	00000000200	00000001626	13	2017-12-11 11:41:12.19533
009	24	30670364603	20171101	00000001	FA	000500006121	00000109339	00000000200	00000002187	13	2017-12-11 11:41:12.19533
009	25	20054139285	20171101	00000001	FA	000500006122	00000072396	00000000200	00000001448	13	2017-12-11 11:41:12.19533
009	26	20054139285	20171101	00000001	FA	000500006123	00000052174	00000000200	00000001043	13	2017-12-11 11:41:12.19533
009	27	20165595743	20171101	00000001	FA	000500005851	00000028223	00000000200	00000000564	13	2017-12-11 11:41:12.19533
009	28	20118857020	20171101	00000001	FA	000500005852	00000066033	00000000200	00000001321	13	2017-12-11 11:41:12.19533
009	29	23277528894	20171101	00000001	FA	000500005853	00001001652	00000000200	00000020033	13	2017-12-11 11:41:12.19533
009	30	27064350809	20171101	00000001	FA	000500006124	00000115289	00000000200	00000002306	13	2017-12-11 11:41:12.19533
009	31	20261719887	20171101	00000001	FA	000500006125	00000170620	00000000200	00000003412	13	2017-12-11 11:41:12.19533
009	32	23241216349	20171101	00000001	FA	000500006126	00000043438	00000000200	00000000869	13	2017-12-11 11:41:12.19533
009	33	27313340754	20171101	00000001	FA	000500005854	00000061487	00000000200	00000001230	13	2017-12-11 11:41:12.19533
009	34	23163173824	20171101	00000001	FA	000500006127	00000036695	00000000200	00000000734	13	2017-12-11 11:41:12.19533
009	35	27215184760	20171101	00000001	FA	000500005855	00000102761	00000000200	00000002055	13	2017-12-11 11:41:12.19533
009	36	27313340339	20171101	00000001	FA	000500005856	00000026280	00000000200	00000000526	13	2017-12-11 11:41:12.19533
009	37	27293985311	20171101	00000001	FA	000500005857	00000026652	00000000200	00000000533	13	2017-12-11 11:41:12.19533
009	38	20243042802	20171101	00000001	FA	000500005858	00000213801	00000000200	00000004276	13	2017-12-11 11:41:12.19533
009	39	20243042802	20171101	00000001	FA	000500005859	00000074380	00000000200	00000001488	13	2017-12-11 11:41:12.19533
009	40	27056603560	20171101	00000001	FA	000500006128	00000024979	00000000200	00000000500	13	2017-12-11 11:41:12.19533
009	41	27952625514	20171101	00000001	FA	000500006129	00000023058	00000000200	00000000461	13	2017-12-11 11:41:12.19533
009	42	23289795464	20171101	00000001	FA	000500006130	00000020455	00000000200	00000000409	13	2017-12-11 11:41:12.19533
009	43	30645715116	20171101	00000001	FA	000500006132	00000596818	00000000200	00000011936	13	2017-12-11 11:41:12.19533
009	44	20139409591	20171104	00000001	FA	000500005918	00000060330	00000000200	00000001207	13	2017-12-11 11:41:12.19533
009	45	23277528894	20171110	00000001	FA	000500005953	00000775206	00000000200	00000015504	13	2017-12-11 11:41:12.19533
009	46	23241216349	20171113	00000001	FA	000500006310	00000156901	00000000200	00000003138	13	2017-12-11 11:41:12.19533
009	47	27186187666	20171114	00000001	FA	000500006011	00000219917	00000000200	00000004398	13	2017-12-11 11:41:12.19533
009	48	27186187666	20171114	00000001	FA	000500006012	00000062479	00000000200	00000001250	13	2017-12-11 11:41:12.19533
009	49	20165595743	20171115	00000001	FA	000500006014	00000012479	00000000200	00000000250	13	2017-12-11 11:41:12.19533
009	50	23289795464	20171115	00000001	FA	000500006325	00000154547	00000000200	00000003091	13	2017-12-11 11:41:12.19533
009	51	23289795464	20171115	00000001	FA	000500006326	00000054545	00000000200	00000001091	13	2017-12-11 11:41:12.19533
009	52	27394430655	20171115	00000001	FA	000500006015	00000047520	00000000200	00000000950	13	2017-12-11 11:41:12.19533
009	53	27259753274	20171115	00000001	FA	000500006327	00000020661	00000000200	00000000413	13	2017-12-11 11:41:12.19533
009	54	27222713140	20171115	00000001	FA	000500006328	00000080732	00000000200	00000001615	13	2017-12-11 11:41:12.19533
009	55	27280191235	20171115	00000001	FA	000500006329	00000094215	00000000200	00000001884	13	2017-12-11 11:41:12.19533
009	56	27237091987	20171115	00000001	FA	000500006016	00000091239	00000000200	00000001825	13	2017-12-11 11:41:12.19533
009	57	20139409591	20171115	00000001	FA	000500006017	00000105041	00000000200	00000002101	13	2017-12-11 11:41:12.19533
009	58	27222713140	20171115	00000001	FA	000500006330	00000144216	00000000200	00000002884	13	2017-12-11 11:41:12.19533
009	59	27215184760	20171115	00000001	FA	000500006018	00000068831	00000000200	00000001377	13	2017-12-11 11:41:12.19533
009	60	20160060183	20171115	00000001	FA	000500006019	00000067190	00000000200	00000001344	13	2017-12-11 11:41:12.19533
009	61	27363206641	20171115	00000001	FA	000500006020	00000143884	00000000200	00000002878	13	2017-12-11 11:41:12.19533
009	62	27342957760	20171115	00000001	FA	000500006021	00000115768	00000000200	00000002315	13	2017-12-11 11:41:12.19533
009	63	20082698508	20171115	00000001	FA	000500006022	00000024545	00000000200	00000000491	13	2017-12-11 11:41:12.19533
009	64	27044920102	20171115	00000001	FA	000500006023	00000040991	00000000200	00000000820	13	2017-12-11 11:41:12.19533
009	65	27056603560	20171115	00000001	FA	000500006332	00000068182	00000000200	00000001364	13	2017-12-11 11:41:12.19533
009	66	27952625514	20171115	00000001	FA	000500006333	00000351659	00000000200	00000007033	13	2017-12-11 11:41:12.19533
009	67	27952625514	20171115	00000001	FA	000500006334	00000577033	00000000200	00000011541	13	2017-12-11 11:41:12.19533
009	68	27952625514	20171115	00000001	FA	000500006336	00000057868	00000000200	00000001157	13	2017-12-11 11:41:12.19533
009	69	20305781720	20171115	00000001	FA	000500006337	00000146612	00000000200	00000002932	13	2017-12-11 11:41:12.19533
009	70	20305781720	20171115	00000001	FA	000500006338	00000188431	00000000200	00000003769	13	2017-12-11 11:41:12.19533
009	71	20305781720	20171115	00000001	FA	000500006339	00000034711	00000000200	00000000694	13	2017-12-11 11:41:12.19533
009	72	30670364603	20171115	00000001	FA	000500006340	00000230826	00000000200	00000004617	13	2017-12-11 11:41:12.19533
009	73	27200953946	20171115	00000001	FA	000500006024	00000030330	00000000200	00000000607	13	2017-12-11 11:41:12.19533
009	74	27325453465	20171115	00000001	FA	000500006025	00000072446	00000000200	00000001449	13	2017-12-11 11:41:12.19533
009	75	20054139285	20171115	00000001	FA	000500006341	00000225998	00000000200	00000004520	13	2017-12-11 11:41:12.19533
009	76	20054139285	20171115	00000001	FA	000500006342	00000099091	00000000200	00000001982	13	2017-12-11 11:41:12.19533
009	77	20118857020	20171115	00000001	FA	000500006026	00000093801	00000000200	00000001876	13	2017-12-11 11:41:12.19533
009	78	27064350809	20171115	00000001	FA	000500006343	00000141157	00000000200	00000002823	13	2017-12-11 11:41:12.19533
009	79	23163173824	20171115	00000001	FA	000500006344	00000041322	00000000200	00000000826	13	2017-12-11 11:41:12.19533
009	80	27293985311	20171115	00000001	FA	000500006027	00000051280	00000000200	00000001026	13	2017-12-11 11:41:12.19533
009	81	23141622099	20171115	00000001	FA	000500006028	00000269680	00000000200	00000005394	13	2017-12-11 11:41:12.19533
009	82	20271066547	20171115	00000001	FA	000500006029	00000022314	00000000200	00000000446	13	2017-12-11 11:41:12.19533
009	83	20243042802	20171115	00000001	FA	000500006030	00000298339	00000000200	00000005967	13	2017-12-11 11:41:12.19533
009	84	20243042802	20171115	00000001	FA	000500006031	00000077685	00000000200	00000001554	13	2017-12-11 11:41:12.19533
009	85	23277528894	20171115	00000001	FA	000500006032	00001149586	00000000200	00000022992	13	2017-12-11 11:41:12.19533
009	86	23289795464	20171115	00000001	FA	000500006345	00000030579	00000000200	00000000612	13	2017-12-11 11:41:12.19533
009	87	23289795464	20171115	00000001	FA	000500006346	00000030579	00000000200	00000000612	13	2017-12-11 11:41:12.19533
009	88	27952625514	20171115	00000001	FA	000500006347	00000314050	00000000200	00000006281	13	2017-12-11 11:41:12.19533
009	89	27952625514	20171115	00000001	FA	000500006348	00000241661	00000000200	00000004833	13	2017-12-11 11:41:12.19533
009	90	27363206641	20171115	00000001	FA	000500006033	00000095206	00000000200	00000001904	13	2017-12-11 11:41:12.19533
009	91	27952625514	20171115	00000001	FA	000500006349	00000086364	00000000200	00000001727	13	2017-12-11 11:41:12.19533
009	92	20139409591	20171117	00000001	FA	000500006067	00000060165	00000000200	00000001203	13	2017-12-11 11:41:12.19533
009	93	27215184760	20171118	00000001	FA	000500006101	00000070578	00000000200	00000001412	13	2017-12-11 11:41:12.19533
009	94	23289795464	20171122	00000001	FA	000500006460	00000195745	00000000200	00000003915	13	2017-12-11 11:41:12.19533
009	95	23289795464	20171122	00000001	FA	000500006461	00000021818	00000000200	00000000436	13	2017-12-11 11:41:12.19533
009	96	23289795464	20171122	00000001	FA	000500006462	00000111406	00000000200	00000002228	13	2017-12-11 11:41:12.19533
009	97	27394430655	20171122	00000001	FA	000500006118	00000029586	00000000200	00000000592	13	2017-12-11 11:41:12.19533
009	98	27363206641	20171122	00000001	FA	000500006119	00000060619	00000000200	00000001212	13	2017-12-11 11:41:12.19533
009	99	27363206641	20171122	00000001	FA	000500006120	00000069545	00000000200	00000001391	13	2017-12-11 11:41:12.19533
009	100	27280191235	20171122	00000001	FA	000500006463	00000094215	00000000200	00000001884	13	2017-12-11 11:41:12.19533
009	101	27222713140	20171122	00000001	FA	000500006464	00000094875	00000000200	00000001898	13	2017-12-11 11:41:12.19533
009	102	27259753274	20171122	00000001	FA	000500006465	00000079504	00000000200	00000001590	13	2017-12-11 11:41:12.19533
009	103	20261719887	20171122	00000001	FA	000500006466	00000212150	00000000200	00000004243	13	2017-12-11 11:41:12.19533
009	104	20261719887	20171122	00000001	FA	000500006467	00000032231	00000000200	00000000645	13	2017-12-11 11:41:12.19533
009	105	20082698508	20171122	00000001	FA	000500006121	00000028012	00000000200	00000000560	13	2017-12-11 11:41:12.19533
009	106	27044920102	20171122	00000001	FA	000500006122	00000028016	00000000200	00000000560	13	2017-12-11 11:41:12.19533
009	107	27056603560	20171122	00000001	FA	000500006468	00000048099	00000000200	00000000962	13	2017-12-11 11:41:12.19533
009	108	27056603560	20171122	00000001	FA	000500006469	00000099173	00000000200	00000001983	13	2017-12-11 11:41:12.19533
009	109	27239279908	20171122	00000001	FA	000500006123	00000042809	00000000200	00000000856	13	2017-12-11 11:41:12.19533
009	110	20305781720	20171122	00000001	FA	000500006470	00000133306	00000000200	00000002666	13	2017-12-11 11:41:12.19533
009	111	20305781720	20171122	00000001	FA	000500006471	00000180826	00000000200	00000003617	13	2017-12-11 11:41:12.19533
009	112	20305781720	20171122	00000001	FA	000500006472	00000016529	00000000200	00000000331	13	2017-12-11 11:41:12.19533
009	113	30670364603	20171122	00000001	FA	000500006473	00000199257	00000000200	00000003985	13	2017-12-11 11:41:12.19533
009	114	27325453465	20171122	00000001	FA	000500006124	00000050950	00000000200	00000001019	13	2017-12-11 11:41:12.19533
009	115	20054139285	20171122	00000001	FA	000500006474	00000093141	00000000200	00000001863	13	2017-12-11 11:41:12.19533
009	116	20165595743	20171122	00000001	FA	000500006125	00000038677	00000000200	00000000774	13	2017-12-11 11:41:12.19533
009	117	27297722927	20171122	00000001	FA	000500006126	00000043719	00000000200	00000000874	13	2017-12-11 11:41:12.19533
009	118	23277528894	20171122	00000001	FA	000500006127	00000722355	00000000200	00000014447	13	2017-12-11 11:41:12.19533
009	119	27064350809	20171122	00000001	FA	000500006476	00000130330	00000000200	00000002607	13	2017-12-11 11:41:12.19533
009	120	27342957760	20171122	00000001	FA	000500006128	00000333404	00000000200	00000006668	13	2017-12-11 11:41:12.19533
009	121	23163173824	20171122	00000001	FA	000500006477	00000025125	00000000200	00000000503	13	2017-12-11 11:41:12.19533
009	122	27313340339	20171122	00000001	FA	000500006130	00000073801	00000000200	00000001476	13	2017-12-11 11:41:12.19533
009	123	27237091987	20171122	00000001	FA	000500006131	00000064793	00000000200	00000001296	13	2017-12-11 11:41:12.19533
009	124	27222713140	20171122	00000001	FA	000500006478	00000173390	00000000200	00000003468	13	2017-12-11 11:41:12.19533
009	125	27293985311	20171122	00000001	FA	000500006134	00000025619	00000000200	00000000512	13	2017-12-11 11:41:12.19533
009	126	27236216638	20171122	00000001	FA	000500006135	00000151528	00000000200	00000003031	13	2017-12-11 11:41:12.19533
009	127	27222713140	20171122	00000001	FA	000500006482	00000173390	00000000200	00000003468	13	2017-12-11 11:41:12.19533
009	128	27952625514	20171122	00000001	FA	000500006483	00000484892	00000000200	00000009698	13	2017-12-11 11:41:12.19533
009	129	27952625514	20171122	00000001	FA	000500006484	00000556731	00000000200	00000011135	13	2017-12-11 11:41:12.19533
009	130	27952625514	20171122	00000001	FA	000500006485	00000123323	00000000200	00000002466	13	2017-12-11 11:41:12.19533
014	77	20253240653	20171103	09891201	FA	028800257341	00000708195	00000000200	00000014164	3	2017-12-05 15:57:19.680123
014	78	11111111111	20171103	09891202	FA	028800257346	00000157951	00000000600	00000009477	3	2017-12-05 15:57:19.680123
014	79	11111111111	20171103	80762511	FA	028800257347	00000181734	00000000600	00000010904	3	2017-12-05 15:57:19.680123
014	80	11111111111	20171103	09891203	FA	028800257344	00000463990	00000000600	00000027839	3	2017-12-05 15:57:19.680123
014	81	11111111111	20171103	80762513	FA	028800257345	00000074849	00000000600	00000004491	3	2017-12-05 15:57:19.680123
014	82	11111111111	20171103	80762515	FA	028800257352	00000547672	00000000600	00000032860	3	2017-12-05 15:57:19.680123
014	83	11111111111	20171103	80762516	FA	028800257358	00000211737	00000000600	00000012704	3	2017-12-05 15:57:19.680123
014	84	11111111111	20171103	80762517	FA	028800257360	00000094254	00000000600	00000005655	3	2017-12-05 15:57:19.680123
014	85	20253240653	20171103	00986310	NC	028800011951	-0000043317	00000000200	-0000000866	3	2017-12-05 15:57:19.680123
014	86	23078116749	20171103	09891207	FA	028800257382	00000042127	00000000200	00000000843	3	2017-12-05 15:57:19.680123
014	87	23078116749	20171103	09891208	FA	028800257384	00000067815	00000000200	00000001356	3	2017-12-05 15:57:19.680123
014	88	23078116749	20171103	80762525	FA	028800257383	00000001403	00000000200	00000000028	3	2017-12-05 15:57:19.680123
014	89	30714240389	20171104	09823612	FA	028800094004	00000279077	00000000200	00000005582	3	2017-12-05 15:57:19.680123
014	90	27952625514	20171104	80303181	FA	028800094011	00000170340	00000000200	00000003407	3	2017-12-05 15:57:19.680123
014	91	27952625514	20171104	80303183	FA	028800094013	00003564176	00000000200	00000071284	3	2017-12-05 15:57:19.680123
014	92	23236216799	20171104	09891231	FA	028800257444	00000417925	00000000200	00000008358	3	2017-12-05 15:57:19.680123
014	93	27356936561	20171104	80762542	FA	028800257448	00000140691	00000000200	00000002814	3	2017-12-05 15:57:19.680123
014	94	23148113149	20171104	80303194	FA	028800094024	00000047264	00000000200	00000000945	3	2017-12-05 15:57:19.680123
014	95	11111111111	20171104	09891240	FA	028800257462	00000160655	00000000600	00000009640	3	2017-12-05 15:57:19.680123
014	96	11111111111	20171104	80762547	FA	028800257469	00000330637	00000000600	00000019839	3	2017-12-05 15:57:19.680123
014	97	20274035170	20171104	80303195	FA	028800094028	00000516598	00000000200	00000010332	3	2017-12-05 15:57:19.680123
014	98	11111111111	20171104	09891245	FA	028800257486	00000267272	00000000600	00000016036	3	2017-12-05 15:57:19.680123
014	99	30653645143	20171104	09891261	FA	028800257530	00000168508	00000000200	00000003370	3	2017-12-05 15:57:19.680123
014	100	30714121606	20171104	09823616	FA	028800094036	00000084445	00000000200	00000001689	3	2017-12-05 15:57:19.680123
014	101	27926131864	20171104	80762568	FA	028800257538	00000080088	00000000200	00000001602	3	2017-12-05 15:57:19.680123
014	102	27926131864	20171104	80762572	FA	028800257544	00000045356	00000000200	00000000907	3	2017-12-05 15:57:19.680123
014	103	11111111111	20171104	80762574	FA	028800257546	00000101957	00000000600	00000006117	3	2017-12-05 15:57:19.680123
014	104	27926131864	20171104	80762576	FA	028800257547	00000414682	00000000200	00000008294	3	2017-12-05 15:57:19.680123
014	105	20015318059	20171104	09823621	FA	028800094047	00000193059	00000000200	00000003861	3	2017-12-05 15:57:19.680123
014	106	11111111111	20171104	09891275	FA	028800257590	00000345615	00000000600	00000020737	3	2017-12-05 15:57:19.680123
014	107	20083960907	20171104	09891280	FA	029100164193	00000422727	00000000200	00000008455	3	2017-12-05 15:57:19.680123
014	108	11111111111	20171104	09891281	FA	029100164197	00000523699	00000000600	00000031422	3	2017-12-05 15:57:19.680123
014	109	11111111111	20171104	09891283	FA	028800257615	00000162722	00000000600	00000009763	3	2017-12-05 15:57:19.680123
014	110	20078143690	20171104	80762682	FA	028800257743	00000051879	00000000200	00000001038	3	2017-12-05 15:57:19.680123
014	111	27173955249	20171104	80762683	FA	029100164347	00000355272	00000000200	00000007106	3	2017-12-05 15:57:19.680123
014	112	20113547198	20171104	80762684	FA	029100164348	00000751600	00000000200	00000015032	3	2017-12-05 15:57:19.680123
014	113	30714121606	20171104	09823642	FA	029100065641	00000217869	00000000200	00000004358	3	2017-12-05 15:57:19.680123
014	114	11111111111	20171104	09891374	FA	028800257744	00000139255	00000000600	00000008355	3	2017-12-05 15:57:19.680123
014	115	27173955249	20171104	08064253	NC	029100011293	-0000050640	00000000200	-0000001013	3	2017-12-05 15:57:19.680123
014	116	11111111111	20171104	09891375	FA	028800257745	00000120638	00000000600	00000007238	3	2017-12-05 15:57:19.680123
014	117	11111111111	20171104	09891379	FA	028800257747	00000036540	00000000600	00000002192	3	2017-12-05 15:57:19.680123
014	118	27047139622	20171106	09891390	FA	029100164373	00000086048	00000000200	00000001721	3	2017-12-05 15:57:19.680123
014	119	11111111111	20171106	09891391	FA	029100164374	00000143316	00000000600	00000008599	3	2017-12-05 15:57:19.680123
014	120	11111111111	20171106	09891392	FA	029100164375	00000163367	00000000600	00000009802	3	2017-12-05 15:57:19.680123
014	121	27163155341	20171106	80762698	FA	029100164379	00000192171	00000000200	00000003843	3	2017-12-05 15:57:19.680123
014	122	27163155341	20171106	08064254	NC	029100011294	-0000127630	00000000200	-0000002553	3	2017-12-05 15:57:19.680123
014	123	23215564894	20171106	80303231	FA	029100065659	00000178321	00000000200	00000003567	3	2017-12-05 15:57:19.680123
014	124	11111111111	20171106	80762705	FA	029100164392	00000162245	00000000600	00000009735	3	2017-12-05 15:57:19.680123
014	125	11111111111	20171106	80762706	FA	029100164393	00000146330	00000000600	00000008780	3	2017-12-05 15:57:19.680123
014	126	11111111111	20171106	08064255	NC	029100011296	-0000162245	00000000600	-0000009735	3	2017-12-05 15:57:19.680123
014	127	11111111111	20171106	09891396	FA	029100164394	00000112051	00000000600	00000006723	3	2017-12-05 15:57:19.680123
014	128	20078143690	20171106	80762712	FA	029100164407	00000057006	00000000200	00000001140	3	2017-12-05 15:57:19.680123
014	129	20216609167	20171106	80762715	FA	029100164416	00000421114	00000000200	00000008422	3	2017-12-05 15:57:19.680123
014	130	11111111111	20171106	09891404	FA	029100164420	00000344683	00000000600	00000020681	3	2017-12-05 15:57:19.680123
014	131	11111111111	20171106	80762717	FA	028800257759	00000118395	00000000600	00000007104	3	2017-12-05 15:57:19.680123
014	132	11111111111	20171106	80762718	FA	028800257760	00000022028	00000000600	00000001322	3	2017-12-05 15:57:19.680123
014	133	27185951184	20171106	80762721	FA	029100164428	00000492988	00000000200	00000009860	3	2017-12-05 15:57:19.680123
014	134	20252613790	20171106	09823648	FA	029100065664	00000093919	00000000200	00000001878	3	2017-12-05 15:57:19.680123
014	135	20331132269	20171106	09823651	FA	028800094101	00000252047	00000000200	00000005041	3	2017-12-05 15:57:19.680123
014	136	20331132269	20171106	09823653	FA	028800094102	00000029198	00000000200	00000000584	3	2017-12-05 15:57:19.680123
014	137	11111111111	20171106	09891421	FA	028800257786	00000330406	00000000600	00000019824	3	2017-12-05 15:57:19.680123
014	138	11111111111	20171106	09891423	FA	028800257789	00000307083	00000000600	00000018425	3	2017-12-05 15:57:19.680123
014	139	27952625514	20171106	08035319	NC	028800006175	-0000357045	00000000200	-0000007141	3	2017-12-05 15:57:19.680123
014	140	20274031086	20171106	80303237	FA	028800094108	00000164364	00000000200	00000003287	3	2017-12-05 15:57:19.680123
014	141	27045748028	20171106	80762755	FA	029100164494	00000324175	00000000200	00000006484	3	2017-12-05 15:57:19.680123
014	142	20184322324	20171106	80303238	FA	029100065677	00000172024	00000000200	00000003441	3	2017-12-05 15:57:19.680123
014	143	27200953946	20171106	80762762	FA	028800257803	00000860786	00000000200	00000017216	3	2017-12-05 15:57:19.680123
014	144	27952625514	20171106	08035320	NC	028800006176	-0000168297	00000000200	-0000003366	3	2017-12-05 15:57:19.680123
014	145	27125944707	20171106	80303257	FA	029100065694	00000298798	00000000200	00000005976	3	2017-12-05 15:57:19.680123
014	146	23073183979	20171106	80762793	FA	029100164588	00000154653	00000000200	00000003093	3	2017-12-05 15:57:19.680123
014	147	11111111111	20171106	80762794	FA	029100164597	00000442200	00000000600	00000026532	3	2017-12-05 15:57:19.680123
014	148	20285166676	20171106	80762796	FA	028800257832	00000090952	00000000200	00000001819	3	2017-12-05 15:57:19.680123
014	149	11111111111	20171106	09891478	FA	028800257833	00000053709	00000000600	00000003223	3	2017-12-05 15:57:19.680123
014	150	23344036209	20171106	80303271	FA	028800094150	00000218302	00000000200	00000004366	3	2017-12-05 15:57:19.680123
014	151	20121294908	20171106	09891482	FA	028800257847	00000114233	00000000200	00000002285	3	2017-12-05 15:57:19.680123
014	152	20066520200	20171107	09891493	FA	029100164618	00000050263	00000000200	00000001005	3	2017-12-05 15:57:19.680123
014	153	11111111111	20171107	80762814	FA	029100164620	00000362932	00000000600	00000021776	3	2017-12-05 15:57:19.680123
014	154	11111111111	20171107	09891494	FA	029100164622	00000125703	00000000600	00000007543	3	2017-12-05 15:57:19.680123
014	155	11111111111	20171107	80762815	FA	029100164624	00000650967	00000000600	00000039058	3	2017-12-05 15:57:19.680123
014	156	11111111111	20171107	80762816	FA	029100164627	00000981605	00000000600	00000058897	3	2017-12-05 15:57:19.680123
014	157	11111111111	20171107	80762818	FA	029100164629	00000213395	00000000600	00000012804	3	2017-12-05 15:57:19.680123
014	158	11111111111	20171107	80762819	FA	029100164630	00000215251	00000000600	00000012915	3	2017-12-05 15:57:19.680123
014	159	11111111111	20171107	80762820	FA	029100164628	00000011815	00000000600	00000000709	3	2017-12-05 15:57:19.680123
014	160	24248899286	20171107	09823680	FA	029100065727	00000275991	00000000200	00000005520	3	2017-12-05 15:57:19.680123
014	161	11111111111	20171107	80762834	FA	028800257864	00000035730	00000000600	00000002144	3	2017-12-05 15:57:19.680123
014	162	11111111111	20171107	80762835	FA	028800257865	00000166450	00000000600	00000009987	3	2017-12-05 15:57:19.680123
014	163	11111111111	20171107	80762836	FA	028800257866	00000024850	00000000600	00000001491	3	2017-12-05 15:57:19.680123
014	164	11111111111	20171107	09891500	FA	028800257871	00000699000	00000000600	00000041940	3	2017-12-05 15:57:19.680123
014	165	20182225992	20171107	80303286	FA	029100065732	00000269288	00000000200	00000005386	3	2017-12-05 15:57:19.680123
014	166	11111111111	20171107	09891511	FA	029100164677	00000328821	00000000600	00000019729	3	2017-12-05 15:57:19.680123
014	167	11111111111	20171107	09891512	FA	029100164679	00000368411	00000000600	00000022105	3	2017-12-05 15:57:19.680123
014	168	20274035170	20171107	09823698	FA	029100065755	00000235180	00000000200	00000004704	3	2017-12-05 15:57:19.680123
014	169	11111111111	20171107	80762896	FA	029100164765	00000115594	00000000600	00000006936	3	2017-12-05 15:57:19.680123
014	170	20148113239	20171107	80762911	FA	029100164800	00000369156	00000000200	00000007383	3	2017-12-05 15:57:19.680123
014	171	27044920102	20171107	80762919	FA	029100164818	00000566997	00000000200	00000011340	3	2017-12-05 15:57:19.680123
014	172	27166891936	20171107	80762931	FA	028800257968	00000125174	00000000200	00000002503	3	2017-12-05 15:57:19.680123
014	173	23241216349	20171107	80303304	FA	028800094193	00000211431	00000000200	00000004229	3	2017-12-05 15:57:19.680123
014	174	30647748739	20171108	09891581	FA	029100164843	00000172120	00000000200	00000003443	3	2017-12-05 15:57:19.680123
014	175	11111111111	20171108	80762953	FA	029100164844	00000028900	00000000600	00000001734	3	2017-12-05 15:57:19.680123
014	176	20122254942	20171108	09823707	FA	029100065776	00000047839	00000000200	00000000957	3	2017-12-05 15:57:19.680123
014	177	11111111111	20171108	09891588	FA	029100164860	00000204492	00000000600	00000012270	3	2017-12-05 15:57:19.680123
014	178	11111111111	20171108	80762963	FA	029100164861	00000092351	00000000600	00000005541	3	2017-12-05 15:57:19.680123
014	179	27103044796	20171108	09891591	FA	029100164872	00000601696	00000000200	00000012034	3	2017-12-05 15:57:19.680123
014	180	27103044796	20171108	09891593	FA	029100164875	00000456779	00000000200	00000009136	3	2017-12-05 15:57:19.680123
014	181	23215564894	20171108	80303320	FA	029100065785	00000299445	00000000200	00000005989	3	2017-12-05 15:57:19.680123
014	182	23930384003	20171108	80762973	FA	029100164886	00000104161	00000000200	00000002083	3	2017-12-05 15:57:19.680123
014	183	23930384003	20171108	80762974	FA	029100164887	00000071746	00000000200	00000001435	3	2017-12-05 15:57:19.680123
014	184	23930384003	20171108	80762975	FA	029100164888	00000101550	00000000200	00000002031	3	2017-12-05 15:57:19.680123
014	185	11111111111	20171108	80762976	FA	029100164885	00000057800	00000000600	00000003468	3	2017-12-05 15:57:19.680123
014	186	20179005833	20171108	80303323	FA	028800094201	00000072783	00000000200	00000001456	3	2017-12-05 15:57:19.680123
014	187	20163184592	20171108	80303328	FA	028800094208	00000519393	00000000200	00000010388	3	2017-12-05 15:57:19.680123
014	188	27290859560	20171108	80303331	FA	029100065803	00000031758	00000000200	00000000635	3	2017-12-05 15:57:19.680123
014	189	27296732589	20171108	09891627	FA	029100164951	00000263866	00000000200	00000005277	3	2017-12-05 15:57:19.680123
014	190	27296732589	20171108	80763052	FA	029100164952	00000157648	00000000200	00000003153	3	2017-12-05 15:57:19.680123
014	191	27100281819	20171108	80303341	FA	029100065822	00000316803	00000000200	00000006336	3	2017-12-05 15:57:19.680123
014	192	27100281819	20171108	80303342	FA	029100065823	00000248605	00000000200	00000004972	3	2017-12-05 15:57:19.680123
014	193	11111111111	20171108	80763054	FA	029100164958	00000291801	00000000600	00000017508	3	2017-12-05 15:57:19.680123
014	194	11111111111	20171108	80763056	FA	029100164962	00000538919	00000000600	00000032335	3	2017-12-05 15:57:19.680123
014	195	20264206392	20171108	09891631	FA	029100164963	00000132804	00000000200	00000002656	3	2017-12-05 15:57:19.680123
014	196	20125943994	20171108	80763057	FA	028800258105	00000160695	00000000200	00000003214	3	2017-12-05 15:57:19.680123
014	197	11111111111	20171108	80763058	FA	028800258106	00000098392	00000000600	00000005903	3	2017-12-05 15:57:19.680123
014	198	11111111111	20171108	09891632	FA	028800258108	00000419507	00000000600	00000025170	3	2017-12-05 15:57:19.680123
014	199	11111111111	20171108	09891633	FA	029100164967	00000140799	00000000600	00000008448	3	2017-12-05 15:57:19.680123
014	200	20264206392	20171108	00986328	NC	029100011308	-0000013547	00000000200	-0000000271	3	2017-12-05 15:57:19.680123
014	201	27200953946	20171108	80763064	FA	028800258117	00000171327	00000000200	00000003427	3	2017-12-05 15:57:19.680123
014	202	11111111111	20171108	80763065	FA	029100164975	00000186941	00000000600	00000011217	3	2017-12-05 15:57:19.680123
014	203	11111111111	20171108	80763066	FA	028800258118	00000017995	00000000600	00000001080	3	2017-12-05 15:57:19.680123
014	204	11111111111	20171108	80763067	FA	029100164979	00000210001	00000000600	00000012600	3	2017-12-05 15:57:19.680123
014	205	11111111111	20171108	80763070	FA	029100164982	00000101888	00000000600	00000006113	3	2017-12-05 15:57:19.680123
014	206	30715184040	20171108	80303349	FA	029100065837	00000167938	00000000200	00000003359	3	2017-12-05 15:57:19.680123
014	207	11111111111	20171108	09891642	FA	029100164991	00000404444	00000000600	00000024267	3	2017-12-05 15:57:19.680123
014	208	11111111111	20171108	80763076	FA	029100164995	00000189383	00000000600	00000011363	3	2017-12-05 15:57:19.680123
014	209	11111111111	20171108	09891645	FA	029100164999	00000288375	00000000600	00000017303	3	2017-12-05 15:57:19.680123
014	210	20078161273	20171108	09891665	FA	028800258179	00000447893	00000000200	00000008958	3	2017-12-05 15:57:19.680123
014	211	11111111111	20171108	80763100	FA	028800258180	00000151004	00000000600	00000009060	3	2017-12-05 15:57:19.680123
014	212	27056603560	20171109	80303361	FA	029100065843	00001344297	00000000200	00000026887	3	2017-12-05 15:57:19.680123
014	213	27222713140	20171109	80303362	FA	029100065844	00000311673	00000000200	00000006234	3	2017-12-05 15:57:19.680123
014	214	27056603560	20171109	80303366	FA	029100065848	00000047769	00000000200	00000000955	3	2017-12-05 15:57:19.680123
014	215	27056603560	20171109	80303376	FA	029100065867	00000269918	00000000200	00000005398	3	2017-12-05 15:57:19.680123
014	216	27056603560	20171109	08035331	NC	029100006364	-0000044426	00000000200	-0000000889	3	2017-12-05 15:57:19.680123
014	217	30670364778	20171109	08064276	NC	029100011317	-0000141328	00000000200	-0000002827	3	2017-12-05 15:57:19.680123
014	218	20214681316	20171109	80303386	FA	029100065879	00000323543	00000000200	00000006471	3	2017-12-05 15:57:19.680123
014	219	11111111111	20171109	09891684	FA	029100165049	00000140048	00000000600	00000008403	3	2017-12-05 15:57:19.680123
014	220	30670364778	20171109	80763129	FA	029100165051	00000185153	00000000200	00000003703	3	2017-12-05 15:57:19.680123
014	221	11111111111	20171109	80763130	FA	029100165055	00000235802	00000000600	00000014148	3	2017-12-05 15:57:19.680123
014	222	20101473032	20171109	09891689	FA	029100165130	00000112915	00000000200	00000002258	3	2017-12-05 15:57:19.680123
014	223	11111111111	20171109	09891690	FA	029100165069	00000134172	00000000600	00000008050	3	2017-12-05 15:57:19.680123
014	224	30715184040	20171109	80303390	FA	029100065887	00000041059	00000000200	00000000821	3	2017-12-05 15:57:19.680123
014	225	11111111111	20171109	80763137	FA	029100165071	00000357670	00000000600	00000021460	3	2017-12-05 15:57:19.680123
014	226	11111111111	20171109	09891695	FA	029100165073	00000259942	00000000600	00000015596	3	2017-12-05 15:57:19.680123
014	227	11111111111	20171109	09891696	FA	029100165074	00000054645	00000000600	00000003279	3	2017-12-05 15:57:19.680123
014	228	27297722927	20171109	80763141	FA	029100165093	00000451549	00000000200	00000009031	3	2017-12-05 15:57:19.680123
014	229	20101473032	20171109	09891697	FA	029100165078	00000112915	00000000200	00000002258	3	2017-12-05 15:57:19.680123
014	230	20320861501	20171109	09823734	FA	028800094231	00000238955	00000000200	00000004779	3	2017-12-05 15:57:19.680123
014	231	11111111111	20171109	09891699	FA	029100165086	00000506902	00000000600	00000030414	3	2017-12-05 15:57:19.680123
014	232	20125944869	20171109	09891700	FA	028800258197	00000754181	00000000200	00000015084	3	2017-12-05 15:57:19.680123
014	233	27219273105	20171109	80763165	FA	029100165112	00000119724	00000000200	00000002394	3	2017-12-05 15:57:19.680123
014	234	27222713140	20171109	08035334	NC	029100006367	-0000028439	00000000200	-0000000569	3	2017-12-05 15:57:19.680123
014	235	27064350809	20171109	80303414	FA	028800094247	00001838548	00000000200	00000036772	3	2017-12-05 15:57:19.680123
014	236	23241216349	20171109	80303423	FA	028800094253	00000248689	00000000200	00000004974	3	2017-12-05 15:57:19.680123
014	237	20101473032	20171109	00986334	NC	029100011323	-0000112915	00000000200	-0000002258	3	2017-12-05 15:57:19.680123
014	238	27356936561	20171109	80763204	FA	029100165175	00000251793	00000000200	00000005036	3	2017-12-05 15:57:19.680123
014	239	11111111111	20171109	80763206	FA	029100165179	00000014931	00000000600	00000000896	3	2017-12-05 15:57:19.680123
014	240	11111111111	20171109	80763208	FA	029100165184	00000401174	00000000600	00000024070	3	2017-12-05 15:57:19.680123
014	241	11111111111	20171109	80763209	FA	029100165187	00000227562	00000000600	00000013654	3	2017-12-05 15:57:19.680123
014	242	11111111111	20171109	09891739	FA	029100165188	00000127600	00000000600	00000007656	3	2017-12-05 15:57:19.680123
014	243	27222713140	20171109	80303432	FA	028800094259	00000283878	00000000200	00000005678	3	2017-12-05 15:57:19.680123
014	244	27222713140	20171109	80303433	FA	028800094260	00001258112	00000000200	00000025162	3	2017-12-05 15:57:19.680123
014	245	20263575718	20171109	80763214	FA	028800258251	00000319400	00000000200	00000006388	3	2017-12-05 15:57:19.680123
014	246	27041619622	20171109	80763215	FA	028800258254	00000336649	00000000200	00000006733	3	2017-12-05 15:57:19.680123
014	247	27041619622	20171109	80763217	FA	028800258255	00000267068	00000000200	00000005342	3	2017-12-05 15:57:19.680123
014	248	20927423635	20171109	80763218	FA	029100165197	00000357522	00000000200	00000007150	3	2017-12-05 15:57:19.680123
014	249	20253240653	20171109	80763220	FA	029100165199	00000129020	00000000200	00000002580	3	2017-12-05 15:57:19.680123
014	250	20253240653	20171109	80763222	FA	029100165200	00000138342	00000000200	00000002767	3	2017-12-05 15:57:19.680123
014	251	11111111111	20171109	09891746	FA	029100165205	00000226602	00000000600	00000013596	3	2017-12-05 15:57:19.680123
014	252	11111111111	20171109	80763230	FA	029100165211	00000158352	00000000600	00000009501	3	2017-12-05 15:57:19.680123
014	253	20179005833	20171109	80303434	FA	029100065938	00000229089	00000000200	00000004582	3	2017-12-05 15:57:19.680123
014	254	23141622099	20171109	09891747	FA	029100165215	00000059063	00000000200	00000001181	3	2017-12-05 15:57:19.680123
014	255	23141622099	20171109	09891748	FA	029100165216	00000008639	00000000200	00000000173	3	2017-12-05 15:57:19.680123
014	256	11111111111	20171109	80763233	FA	029100165217	00000067304	00000000600	00000004038	3	2017-12-05 15:57:19.680123
014	257	11111111111	20171109	09891749	FA	029100165222	00000285097	00000000600	00000017106	3	2017-12-05 15:57:19.680123
014	258	11111111111	20171109	80763234	FA	028800258266	00000091922	00000000600	00000005515	3	2017-12-05 15:57:19.680123
014	259	11111111111	20171109	09891750	FA	029100165224	00000217752	00000000600	00000013065	3	2017-12-05 15:57:19.680123
009	131	27952625514	20171122	00000001	FA	000500006486	00000231925	00000000200	00000004639	13	2017-12-11 11:41:12.19533
009	132	27952625514	20171122	00000001	FA	000500006487	00000152900	00000000200	00000003058	13	2017-12-11 11:41:12.19533
009	133	27952625514	20171122	00000001	FA	000500006488	00000048991	00000000200	00000000980	13	2017-12-11 11:41:12.19533
009	134	27952625514	20171122	00000001	FA	000500006490	00000093273	00000000200	00000001865	13	2017-12-11 11:41:12.19533
009	135	27186187666	20171124	00000001	FA	000500006177	00000097933	00000000200	00000001959	13	2017-12-11 11:41:12.19533
009	136	23289795464	20171129	00000001	FA	000500006597	00000095315	00000000200	00000001906	13	2017-12-11 11:41:12.19533
009	137	23289795464	20171129	00000001	FA	000500006598	00000151612	00000000200	00000003032	13	2017-12-11 11:41:12.19533
009	138	27280191235	20171129	00000001	FA	000500006599	00000125620	00000000200	00000002512	13	2017-12-11 11:41:12.19533
009	139	27363206641	20171129	00000001	FA	000500006221	00000127561	00000000200	00000002551	13	2017-12-11 11:41:12.19533
009	140	27363206641	20171129	00000001	FA	000500006222	00000229008	00000000200	00000004580	13	2017-12-11 11:41:12.19533
009	141	27222713140	20171129	00000001	FA	000500006600	00000131331	00000000200	00000002627	13	2017-12-11 11:41:12.19533
009	142	27237091987	20171129	00000001	FA	000500006223	00000041157	00000000200	00000000823	13	2017-12-11 11:41:12.19533
009	143	20139409591	20171129	00000001	FA	000500006224	00000115041	00000000200	00000002301	13	2017-12-11 11:41:12.19533
009	144	27222713140	20171129	00000001	FA	000500006601	00000068596	00000000200	00000001372	13	2017-12-11 11:41:12.19533
009	145	27215184760	20171129	00000001	FA	000500006225	00000100380	00000000200	00000002008	13	2017-12-11 11:41:12.19533
009	146	20271066547	20171129	00000001	FA	000500006226	00000058512	00000000200	00000001170	13	2017-12-11 11:41:12.19533
009	147	27163171843	20171129	00000001	FA	000500006227	00000016280	00000000200	00000000326	13	2017-12-11 11:41:12.19533
009	148	27163171843	20171129	00000001	FA	000500006228	00000006198	00000000200	00000000124	13	2017-12-11 11:41:12.19533
009	149	20261719887	20171129	00000001	FA	000500006602	00000134216	00000000200	00000002684	13	2017-12-11 11:41:12.19533
009	150	20261719887	20171129	00000001	FA	000500006603	00000123969	00000000200	00000002479	13	2017-12-11 11:41:12.19533
009	151	20082698508	20171129	00000001	FA	000500006231	00000106735	00000000200	00000002135	13	2017-12-11 11:41:12.19533
009	152	23277528894	20171129	00000001	FA	000500006232	00001532231	00000000200	00000030645	13	2017-12-11 11:41:12.19533
009	153	27056603560	20171129	00000001	FA	000500006604	00000100577	00000000200	00000002012	13	2017-12-11 11:41:12.19533
009	154	27952625514	20171129	00000001	FA	000500006605	00000346295	00000000200	00000006926	13	2017-12-11 11:41:12.19533
009	155	27952625514	20171129	00000001	FA	000500006607	00000022985	00000000200	00000000460	13	2017-12-11 11:41:12.19533
009	156	27952625514	20171129	00000001	FA	000500006608	00000049388	00000000200	00000000988	13	2017-12-11 11:41:12.19533
009	157	27952625514	20171129	00000001	FA	000500006609	00000325512	00000000200	00000006510	13	2017-12-11 11:41:12.19533
009	158	30670364603	20171129	00000001	FA	000500006610	00000182149	00000000200	00000003643	13	2017-12-11 11:41:12.19533
009	159	27200953946	20171129	00000001	FA	000500006233	00000092809	00000000200	00000001856	13	2017-12-11 11:41:12.19533
009	160	27236216638	20171129	00000001	FA	000500006234	00000137933	00000000200	00000002759	13	2017-12-11 11:41:12.19533
009	161	30715278347	20171129	00000001	FA	000500006611	00000140330	00000000200	00000002807	13	2017-12-11 11:41:12.19533
009	162	27064350809	20171129	00000001	FA	000500006613	00000128762	00000000200	00000002575	13	2017-12-11 11:41:12.19533
009	163	27293985311	20171129	00000001	FA	000500006235	00000026652	00000000200	00000000533	13	2017-12-11 11:41:12.19533
009	164	20118857020	20171129	00000001	FA	000500006236	00000155067	00000000200	00000003101	13	2017-12-11 11:41:12.19533
009	165	23163173824	20171129	00000001	FA	000500006615	00000105421	00000000200	00000002108	13	2017-12-11 11:41:12.19533
009	166	23141622099	20171129	00000001	FA	000500006237	00000312318	00000000200	00000006246	13	2017-12-11 11:41:12.19533
009	167	20243042802	20171129	00000001	FA	000500006238	00000253318	00000000200	00000005066	13	2017-12-11 11:41:12.19533
009	168	23241216349	20171129	00000001	FA	000500006618	00000237603	00000000200	00000004752	13	2017-12-11 11:41:12.19533
014	260	11111111111	20171109	80763238	FA	028800258267	00000177421	00000000600	00000010645	3	2017-12-05 15:57:19.680123
014	261	11111111111	20171109	08064287	NC	028800011995	-0000091922	00000000600	-0000005515	3	2017-12-05 15:57:19.680123
014	262	11111111111	20171109	08064288	NC	028800011996	-0000177421	00000000600	-0000010645	3	2017-12-05 15:57:19.680123
014	263	20268330411	20171109	09891755	FA	028800258269	00000132767	00000000200	00000002655	3	2017-12-05 15:57:19.680123
014	264	20268330411	20171109	09891766	FA	028800258279	00000118444	00000000200	00000002369	3	2017-12-05 15:57:19.680123
014	265	27222713140	20171110	08035342	NC	028800006202	-0000796317	00000000200	-0000015926	3	2017-12-05 15:57:19.680123
014	266	20162750640	20171110	80763290	FA	029100165277	00000371588	00000000200	00000007432	3	2017-12-05 15:57:19.680123
014	267	27296732589	20171110	09891788	FA	028800258337	00000172575	00000000200	00000003451	3	2017-12-05 15:57:19.680123
014	268	20226157833	20171110	80303461	FA	029100065948	00000758722	00000000200	00000015175	3	2017-12-05 15:57:19.680123
014	269	11111111111	20171110	80763294	FA	029100165282	00000123272	00000000600	00000007396	3	2017-12-05 15:57:19.680123
014	270	11111111111	20171110	09891791	FA	029100165285	00000258037	00000000600	00000015482	3	2017-12-05 15:57:19.680123
014	271	11111111111	20171110	09891792	FA	029100165289	00000263189	00000000600	00000015791	3	2017-12-05 15:57:19.680123
014	272	20170936621	20171110	80303465	FA	029100065961	00000537265	00000000200	00000010745	3	2017-12-05 15:57:19.680123
014	273	11111111111	20171110	09891793	FA	029100165290	00000164708	00000000600	00000009882	3	2017-12-05 15:57:19.680123
014	274	27346653782	20171110	80763297	FA	029100165302	00000117489	00000000200	00000002350	3	2017-12-05 15:57:19.680123
014	275	27148843258	20171110	80763298	FA	029100165303	00000198491	00000000200	00000003970	3	2017-12-05 15:57:19.680123
014	276	23185034674	20171110	80763310	FA	029100165329	00000185114	00000000200	00000003702	3	2017-12-05 15:57:19.680123
014	277	20237830955	20171110	09891811	FA	029100165337	00000513061	00000000200	00000010261	3	2017-12-05 15:57:19.680123
014	278	11111111111	20171110	80763318	FA	029100165338	00000040823	00000000600	00000002449	3	2017-12-05 15:57:19.680123
014	279	20178081498	20171110	80763323	FA	029100165347	00000212796	00000000200	00000004256	3	2017-12-05 15:57:19.680123
014	280	20178081498	20171110	09891816	FA	028800258361	00000258968	00000000200	00000005180	3	2017-12-05 15:57:19.680123
014	281	20285166676	20171110	80763333	FA	028800258368	00000013636	00000000200	00000000273	3	2017-12-05 15:57:19.680123
014	282	27037178638	20171110	80763340	FA	029100165359	00000221146	00000000200	00000004423	3	2017-12-05 15:57:19.680123
014	283	11111111111	20171110	80763342	FA	028800258376	00000650332	00000000600	00000039020	3	2017-12-05 15:57:19.680123
014	284	11111111111	20171110	80763343	FA	028800258377	00000566788	00000000600	00000034007	3	2017-12-05 15:57:19.680123
014	285	30670388480	20171110	09823759	FA	028800094302	00000138218	00000000200	00000002765	3	2017-12-05 15:57:19.680123
014	286	20179005833	20171110	80303501	FA	028800094318	00000215358	00000000200	00000004307	3	2017-12-05 15:57:19.680123
014	287	11111111111	20171110	09891825	FA	029100165363	00000099227	00000000600	00000005954	3	2017-12-05 15:57:19.680123
014	288	20108342456	20171110	80763391	FA	028800258411	00000129946	00000000200	00000002599	3	2017-12-05 15:57:19.680123
014	289	20108342456	20171110	80763393	FA	028800258412	00000046360	00000000200	00000000927	3	2017-12-05 15:57:19.680123
014	290	30709978248	20171110	80303517	FA	028800094335	00000438523	00000000200	00000008771	3	2017-12-05 15:57:19.680123
014	291	27952625514	20171110	80303522	FA	028800094345	00000608154	00000000200	00000012163	3	2017-12-05 15:57:19.680123
014	292	27952625514	20171110	80303523	FA	028800094346	00000560581	00000000200	00000011212	3	2017-12-05 15:57:19.680123
014	293	27952625514	20171110	80303524	FA	028800094347	00000490100	00000000200	00000009802	3	2017-12-05 15:57:19.680123
014	294	27952625514	20171110	80303525	FA	028800094348	00000705672	00000000200	00000014113	3	2017-12-05 15:57:19.680123
014	295	27952625514	20171110	80303526	FA	028800094349	00000609096	00000000200	00000012182	3	2017-12-05 15:57:19.680123
014	296	27952625514	20171110	80303527	FA	028800094350	00000668305	00000000200	00000013366	3	2017-12-05 15:57:19.680123
014	297	27952625514	20171110	80303528	FA	028800094339	00000047109	00000000200	00000000942	3	2017-12-05 15:57:19.680123
014	298	20290121605	20171110	09891863	FA	028800258486	00000358456	00000000200	00000007169	3	2017-12-05 15:57:19.680123
014	299	11111111111	20171110	09891864	FA	028800258487	00000128321	00000000600	00000007699	3	2017-12-05 15:57:19.680123
014	300	20078206498	20171111	09823762	FA	028800094362	00000393463	00000000200	00000007869	3	2017-12-05 15:57:19.680123
014	301	11111111111	20171111	80763471	FA	028800258514	00000176976	00000000600	00000010619	3	2017-12-05 15:57:19.680123
014	302	20078206498	20171111	09823763	FA	028800094363	00000014130	00000000200	00000000283	3	2017-12-05 15:57:19.680123
014	303	27290859560	20171111	09823764	FA	028800094367	00000491949	00000000200	00000009839	3	2017-12-05 15:57:19.680123
014	304	20283075878	20171111	80763484	FA	028800258543	00000284419	00000000200	00000005688	3	2017-12-05 15:57:19.680123
014	305	20083368862	20171111	80763564	FA	029100165509	00000378014	00000000200	00000007561	3	2017-12-05 15:57:19.680123
014	306	27260224269	20171111	80763571	FA	028800258714	00000165216	00000000200	00000003304	3	2017-12-05 15:57:19.680123
014	307	27260224269	20171111	80763572	FA	028800258715	00000005481	00000000200	00000000110	3	2017-12-05 15:57:19.680123
014	308	11111111111	20171111	80763573	FA	028800258716	00000187475	00000000600	00000011249	3	2017-12-05 15:57:19.680123
014	309	11111111111	20171111	80763580	FA	029100165536	00000167150	00000000600	00000010029	3	2017-12-05 15:57:19.680123
014	310	11111111111	20171111	09891935	FA	029100165537	00000119233	00000000600	00000007154	3	2017-12-05 15:57:19.680123
014	311	20265462163	20171111	09891945	FA	028800258733	00000685364	00000000200	00000013708	3	2017-12-05 15:57:19.680123
014	312	20073186901	20171111	09891950	FA	029100165577	00000158329	00000000200	00000003166	3	2017-12-05 15:57:19.680123
014	313	27173955249	20171111	80763603	FA	028800258746	00000347678	00000000200	00000006953	3	2017-12-05 15:57:19.680123
014	314	30670371359	20171111	09823801	FA	029100066028	00000178184	00000000200	00000003564	3	2017-12-05 15:57:19.680123
014	315	30709978248	20171111	80303598	FA	028800094479	00000181470	00000000200	00000003630	3	2017-12-05 15:57:19.680123
014	316	27206355412	20171111	80763622	FA	028800258772	00000108094	00000000200	00000002162	3	2017-12-05 15:57:19.680123
014	317	27249663676	20171111	80763623	FA	028800258774	00000243964	00000000200	00000004879	3	2017-12-05 15:57:19.680123
014	318	11111111111	20171111	09891964	FA	028800258777	00000529363	00000000600	00000031762	3	2017-12-05 15:57:19.680123
014	319	27249663676	20171111	08064322	NC	029100011336	-0000037390	00000000200	-0000000748	3	2017-12-05 15:57:19.680123
014	320	27249663676	20171111	80763625	FA	029100165608	00000039439	00000000200	00000000789	3	2017-12-05 15:57:19.680123
014	321	11111111111	20171111	09891968	FA	029100165612	00000322984	00000000600	00000019379	3	2017-12-05 15:57:19.680123
014	322	30670364778	20171113	09891974	FA	029100165633	00000219363	00000000200	00000004387	3	2017-12-05 15:57:19.680123
014	323	20076156280	20171113	80763641	FA	029100165642	00000450485	00000000200	00000009010	3	2017-12-05 15:57:19.680123
014	324	20185054234	20171113	80763651	FA	029100165654	00000120067	00000000200	00000002401	3	2017-12-05 15:57:19.680123
014	325	20140758397	20171113	09823810	FA	029100066070	00000695595	00000000200	00000013912	3	2017-12-05 15:57:19.680123
014	326	11111111111	20171113	80763667	FA	029100165683	00000030098	00000000600	00000001806	3	2017-12-05 15:57:19.680123
014	327	27237091316	20171113	80763668	FA	029100165679	00000429943	00000000200	00000008599	3	2017-12-05 15:57:19.680123
014	328	11111111111	20171113	09891984	FA	029100165680	00000021170	00000000600	00000001270	3	2017-12-05 15:57:19.680123
014	329	11111111111	20171113	09891985	FA	029100165681	00000018349	00000000600	00000001101	3	2017-12-05 15:57:19.680123
014	330	11111111111	20171113	09891986	FA	029100165682	00000145197	00000000600	00000008712	3	2017-12-05 15:57:19.680123
014	331	20073299498	20171113	80303619	FA	029100066071	00000057597	00000000200	00000001152	3	2017-12-05 15:57:19.680123
014	332	20073299498	20171113	80303620	FA	029100066073	00000117529	00000000200	00000002351	3	2017-12-05 15:57:19.680123
014	333	11111111111	20171113	80763669	FA	028800258789	00000080666	00000000600	00000004840	3	2017-12-05 15:57:19.680123
014	334	11111111111	20171113	80763670	FA	028800258791	00000024925	00000000600	00000001496	3	2017-12-05 15:57:19.680123
014	335	11111111111	20171113	80763671	FA	028800258790	00000067643	00000000600	00000004059	3	2017-12-05 15:57:19.680123
014	336	20073299498	20171113	80303621	FA	028800094481	00000022099	00000000200	00000000442	3	2017-12-05 15:57:19.680123
014	337	11111111111	20171113	09891987	FA	028800258793	00000192715	00000000600	00000011563	3	2017-12-05 15:57:19.680123
014	338	20125944281	20171113	09823811	FA	028800094484	00000309063	00000000200	00000006182	3	2017-12-05 15:57:19.680123
014	339	11111111111	20171113	09891992	FA	028800258803	00000557529	00000000600	00000033452	3	2017-12-05 15:57:19.680123
014	340	11111111111	20171113	09891993	FA	028800258804	00000121920	00000000600	00000007315	3	2017-12-05 15:57:19.680123
014	341	27185951184	20171113	80763697	FA	029100165701	00000206190	00000000200	00000004124	3	2017-12-05 15:57:19.680123
014	342	11111111111	20171113	09891994	FA	028800258819	00000226781	00000000600	00000013607	3	2017-12-05 15:57:19.680123
004	2	20149833510	20171104	00000001	FA	000400085579	00000017656	00000000200	00000000353	8	2017-12-11 15:54:47.409013
004	3	27219273431	20171104	00000001	FA	000400085580	00000065385	00000000200	00000001308	8	2017-12-11 15:54:47.409013
004	4	27222713140	20171104	00000001	FA	000400026566	00000020560	00000000200	00000000411	8	2017-12-11 15:54:47.409013
004	5	20273644912	20171104	00000001	FA	000400085581	00000014750	00000000200	00000000295	8	2017-12-11 15:54:47.409013
004	6	27237091987	20171104	00000001	FA	000400085582	00000112313	00000000200	00000002246	8	2017-12-11 15:54:47.409013
004	7	27302090977	20171104	00000001	FA	000400026567	00000102300	00000000200	00000002046	8	2017-12-11 15:54:47.409013
004	8	20313340792	20171104	00000001	FA	000400085583	00000026409	00000000200	00000000528	8	2017-12-11 15:54:47.409013
004	9	27367571123	20171104	00000001	FA	000400026568	00000035053	00000000200	00000000701	8	2017-12-11 15:54:47.409013
004	10	30670364603	20171104	00000001	FA	000400026569	00000283662	00000000200	00000005673	8	2017-12-11 15:54:47.409013
004	11	27188206463	20171104	00000001	FA	000400026570	00000016497	00000000200	00000000330	8	2017-12-11 15:54:47.409013
004	12	27216609528	20171104	00000001	FA	000400085584	00000020719	00000000200	00000000414	8	2017-12-11 15:54:47.409013
004	13	20388075806	20171104	00000001	FA	000400085585	00000102952	00000000200	00000002059	8	2017-12-11 15:54:47.409013
004	14	20388075806	20171104	00000001	FA	000400085586	00000158520	00000000200	00000003170	8	2017-12-11 15:54:47.409013
004	15	20388075806	20171104	00000001	FA	000400085587	00000025790	00000000200	00000000516	8	2017-12-11 15:54:47.409013
004	16	30670364603	20171104	00000001	FA	000400026572	00000263119	00000000200	00000005262	8	2017-12-11 15:54:47.409013
004	17	27305782004	20171106	00000001	FA	000100054267	00000040066	00000000200	00000000801	8	2017-12-11 15:54:47.409013
004	18	20225250473	20171109	00000001	FA	000400085641	00000043912	00000000200	00000000878	8	2017-12-11 15:54:47.409013
004	19	27245844811	20171110	00000001	FA	000400026618	00000036547	00000000200	00000000731	8	2017-12-11 15:54:47.409013
004	20	20125944540	20171110	00000001	FA	000400026625	00000065752	00000000200	00000001315	8	2017-12-11 15:54:47.409013
004	21	20283075878	20171111	00000001	FA	000400085673	00000095255	00000000200	00000001905	8	2017-12-11 15:54:47.409013
004	22	27222713140	20171111	00000001	FA	000400026626	00000036072	00000000200	00000000721	8	2017-12-11 15:54:47.409013
004	23	27222713140	20171111	00000001	FA	000400026627	00000048652	00000000200	00000000973	8	2017-12-11 15:54:47.409013
004	24	20313340792	20171111	00000001	FA	000400085674	00000017057	00000000200	00000000341	8	2017-12-11 15:54:47.409013
004	25	27361302759	20171111	00000001	FA	000400085675	00000048759	00000000200	00000000975	8	2017-12-11 15:54:47.409013
004	26	27173955451	20171111	00000001	FA	000400026628	00000047427	00000000200	00000000949	8	2017-12-11 15:54:47.409013
004	27	27302090977	20171111	00000001	FA	000400026629	00000091600	00000000200	00000001832	8	2017-12-11 15:54:47.409013
004	28	27363206641	20171111	00000001	FA	000400085676	00000029084	00000000200	00000000582	8	2017-12-11 15:54:47.409013
004	29	27144481122	20171111	00000001	FA	000400085677	00000051651	00000000200	00000001033	8	2017-12-11 15:54:47.409013
004	30	27216609528	20171111	00000001	FA	000400085678	00000121545	00000000200	00000002431	8	2017-12-11 15:54:47.409013
004	31	27216609528	20171111	00000001	FA	000400085679	00000004491	00000000200	00000000090	8	2017-12-11 15:54:47.409013
004	32	27219273431	20171111	00000001	FA	000400085680	00000078689	00000000200	00000001574	8	2017-12-11 15:54:47.409013
004	33	20273644912	20171111	00000001	FA	000400085681	00000036292	00000000200	00000000726	8	2017-12-11 15:54:47.409013
004	34	20160060183	20171111	00000001	FA	000400085682	00000015670	00000000200	00000000313	8	2017-12-11 15:54:47.409013
004	35	23163173824	20171111	00000001	FA	000400026630	00000071753	00000000200	00000001435	8	2017-12-11 15:54:47.409013
004	36	20388075806	20171111	00000001	FA	000400085683	00000033754	00000000200	00000000675	8	2017-12-11 15:54:47.409013
004	37	30670364603	20171111	00000001	FA	000400026631	00000115067	00000000200	00000002301	8	2017-12-11 15:54:47.409013
004	38	27237091987	20171111	00000001	FA	000400085684	00000050910	00000000200	00000001018	8	2017-12-11 15:54:47.409013
004	39	20285080089	20171111	00000001	FA	000400085685	00000022343	00000000200	00000000447	8	2017-12-11 15:54:47.409013
004	40	20273644912	20171114	00000001	FA	000400085688	00000047324	00000000200	00000000946	8	2017-12-11 15:54:47.409013
004	41	27248176607	20171114	00000001	FA	000400085692	00000224841	00000000200	00000004497	8	2017-12-11 15:54:47.409013
004	42	30670364603	20171114	00000001	FA	000400026638	00000250436	00000000200	00000005009	8	2017-12-11 15:54:47.409013
004	43	30670364603	20171114	00000001	FA	000400026639	00000218927	00000000200	00000004379	8	2017-12-11 15:54:47.409013
004	44	30670364603	20171114	00000001	FA	000400026640	00000040149	00000000200	00000000803	8	2017-12-11 15:54:47.409013
004	45	27101402156	20171114	00000001	FA	000400085693	00000142792	00000000200	00000002856	8	2017-12-11 15:54:47.409013
004	46	23331976199	20171114	00000001	FA	000400085694	00000011052	00000000200	00000000221	8	2017-12-11 15:54:47.409013
004	47	27222713140	20171114	00000001	FA	000400026641	00000087122	00000000200	00000001742	8	2017-12-11 15:54:47.409013
004	48	30670364603	20171116	00000001	FA	000400026656	00000009515	00000000200	00000000190	8	2017-12-11 15:54:47.409013
004	49	27302090977	20171118	00000001	FA	000400026681	00000081694	00000000200	00000001634	8	2017-12-11 15:54:47.409013
004	50	20273644912	20171118	00000001	FA	000400085748	00000030960	00000000200	00000000619	8	2017-12-11 15:54:47.409013
004	51	27222713140	20171118	00000001	FA	000400026682	00000035122	00000000200	00000000702	8	2017-12-11 15:54:47.409013
004	52	27237091987	20171118	00000001	FA	000400085749	00000027788	00000000200	00000000556	8	2017-12-11 15:54:47.409013
004	53	27363206641	20171118	00000001	FA	000400085750	00000008357	00000000200	00000000167	8	2017-12-11 15:54:47.409013
004	54	20078192144	20171118	00000001	FA	000400085751	00000006645	00000000200	00000000133	8	2017-12-11 15:54:47.409013
004	55	23163173824	20171118	00000001	FA	000400026683	00000079826	00000000200	00000001597	8	2017-12-11 15:54:47.409013
004	56	20313340792	20171118	00000001	FA	000400085752	00000012306	00000000200	00000000246	8	2017-12-11 15:54:47.409013
004	57	27219273431	20171118	00000001	FA	000400085753	00000070861	00000000200	00000001417	8	2017-12-11 15:54:47.409013
004	58	27222713140	20171118	00000001	FA	000400026684	00000056993	00000000200	00000001140	8	2017-12-11 15:54:47.409013
004	59	20264630453	20171118	00000001	FA	000400085754	00000033783	00000000200	00000000676	8	2017-12-11 15:54:47.409013
004	60	27173955451	20171118	00000001	FA	000400026685	00000033678	00000000200	00000000674	8	2017-12-11 15:54:47.409013
004	61	30670364603	20171118	00000001	FA	000400026687	00000152069	00000000200	00000003041	8	2017-12-11 15:54:47.409013
004	62	20388075806	20171118	00000001	FA	000400085755	00000127835	00000000200	00000002557	8	2017-12-11 15:54:47.409013
004	63	20388075806	20171118	00000001	FA	000400085756	00000114280	00000000200	00000002286	8	2017-12-11 15:54:47.409013
004	64	20388075806	20171118	00000001	FA	000400085757	00000102635	00000000200	00000002053	8	2017-12-11 15:54:47.409013
004	65	27173955451	20171118	00000001	FA	000400026688	00000013629	00000000200	00000000273	8	2017-12-11 15:54:47.409013
004	66	20388075806	20171118	00000001	FA	000400085758	00000004955	00000000200	00000000099	8	2017-12-11 15:54:47.409013
004	67	27164461764	20171118	00000001	FA	000400085759	00000031552	00000000200	00000000631	8	2017-12-11 15:54:47.409013
004	68	27313340339	20171121	00000001	FA	000400085773	00000096959	00000000200	00000001939	8	2017-12-11 15:54:47.409013
004	69	27242508918	20171121	00000001	FA	000400085774	00000044546	00000000200	00000000891	8	2017-12-11 15:54:47.409013
004	70	27222713140	20171121	00000001	FA	000400026694	00000073271	00000000200	00000001465	8	2017-12-11 15:54:47.409013
004	71	27101402156	20171121	00000001	FA	000400085775	00000081386	00000000200	00000001628	8	2017-12-11 15:54:47.409013
004	72	27222713140	20171121	00000001	FA	000400026695	00000074174	00000000200	00000001483	8	2017-12-11 15:54:47.409013
004	73	20225250473	20171121	00000001	FA	000400085777	00000063243	00000000200	00000001265	8	2017-12-11 15:54:47.409013
004	74	27367571123	20171125	00000001	FA	000400026740	00000004672	00000000200	00000000093	8	2017-12-11 15:54:47.409013
004	75	27173955451	20171125	00000001	FA	000400026741	00000143750	00000000200	00000002875	8	2017-12-11 15:54:47.409013
004	76	27226803233	20171125	00000001	FA	000400085828	00000062361	00000000200	00000001247	8	2017-12-11 15:54:47.409013
004	77	27237091987	20171125	00000001	FA	000400085829	00000040014	00000000200	00000000800	8	2017-12-11 15:54:47.409013
004	78	30670364603	20171125	00000001	FA	000400026742	00000208635	00000000200	00000004173	8	2017-12-11 15:54:47.409013
004	79	20273644912	20171125	00000001	FA	000400085830	00000041206	00000000200	00000000824	8	2017-12-11 15:54:47.409013
004	80	27219273431	20171125	00000001	FA	000400085831	00000104176	00000000200	00000002084	8	2017-12-11 15:54:47.409013
004	81	27222713140	20171125	00000001	FA	000400026743	00000009210	00000000200	00000000184	8	2017-12-11 15:54:47.409013
004	82	27361302759	20171125	00000001	FA	000400085832	00000220703	00000000200	00000004414	8	2017-12-11 15:54:47.409013
004	83	27361302759	20171125	00000001	FA	000400085833	00000138171	00000000200	00000002763	8	2017-12-11 15:54:47.409013
004	84	20388075806	20171125	00000001	FA	000400085834	00000122341	00000000200	00000002447	8	2017-12-11 15:54:47.409013
004	85	27313340339	20171128	00000001	FA	000400085858	00000174935	00000000200	00000003499	8	2017-12-11 15:54:47.409013
004	86	27313340339	20171128	00000001	FA	000400085859	00000024496	00000000200	00000000490	8	2017-12-11 15:54:47.409013
004	87	27237091987	20171128	00000001	FA	000400085860	00000159187	00000000200	00000003184	8	2017-12-11 15:54:47.409013
004	88	27237091987	20171128	00000001	FA	000400085861	00000163714	00000000200	00000003274	8	2017-12-11 15:54:47.409013
004	89	27248176607	20171128	00000001	FA	000400085862	00000201072	00000000200	00000004021	8	2017-12-11 15:54:47.409013
004	90	27248176607	20171128	00000001	FA	000400085863	00000094959	00000000200	00000001899	8	2017-12-11 15:54:47.409013
004	91	30670364603	20171128	00000001	FA	000400026759	00000134856	00000000200	00000002697	8	2017-12-11 15:54:47.409013
004	92	27222713140	20171128	00000001	FA	000400026760	00000047866	00000000200	00000000957	8	2017-12-11 15:54:47.409013
004	93	27346653502	20171128	00000001	FA	000400085864	00000129871	00000000200	00000002597	8	2017-12-11 15:54:47.409013
004	94	27112378257	20171128	00000001	FA	000400085865	00000053183	00000000200	00000001064	8	2017-12-11 15:54:47.409013
004	95	23331976199	20171128	00000001	FA	000400085866	00000025764	00000000200	00000000515	8	2017-12-11 15:54:47.409013
004	96	20273644912	20171128	00000001	FA	000400085867	00000044825	00000000200	00000000897	8	2017-12-11 15:54:47.409013
004	97	27173955451	20171128	00000001	FA	000400026761	00000037771	00000000200	00000000755	8	2017-12-11 15:54:47.409013
004	98	20225250473	20171128	00000001	FA	000400085868	00000042103	00000000200	00000000842	8	2017-12-11 15:54:47.409013
004	99	27313340339	20171128	00000001	FA	000400085869	00000020576	00000000200	00000000412	8	2017-12-11 15:54:47.409013
004	100	27361302759	20171128	00000001	FA	000400085870	00000148560	00000000200	00000002971	8	2017-12-11 15:54:47.409013
004	101	27361302759	20171128	00000001	FA	000400085872	00000148532	00000000200	00000002971	8	2017-12-11 15:54:47.409013
014	343	11111111111	20171113	09891996	FA	029100165702	00000233368	00000000600	00000014002	3	2017-12-05 15:57:19.680123
014	344	27112378257	20171113	80763709	FA	029100165729	00000303323	00000000200	00000006066	3	2017-12-05 15:57:19.680123
014	345	20160060183	20171113	80763741	FA	029100165780	00000387124	00000000200	00000007743	3	2017-12-05 15:57:19.680123
014	346	20160060183	20171113	80763743	FA	029100165782	00000392701	00000000200	00000007854	3	2017-12-05 15:57:19.680123
014	347	23930384003	20171113	80763749	FA	028800258859	00000162832	00000000200	00000003257	3	2017-12-05 15:57:19.680123
014	348	23930384003	20171113	80763750	FA	028800258860	00000029118	00000000200	00000000582	3	2017-12-05 15:57:19.680123
014	349	23930384003	20171113	80763751	FA	028800258858	00000130330	00000000200	00000002607	3	2017-12-05 15:57:19.680123
014	350	11111111111	20171113	80763753	FA	028800258866	00000420892	00000000600	00000025253	3	2017-12-05 15:57:19.680123
014	351	23930384003	20171113	80763754	FA	028800258867	00000020201	00000000200	00000000404	3	2017-12-05 15:57:19.680123
014	352	27142902910	20171113	80763757	FA	028800258872	00000109135	00000000200	00000002183	3	2017-12-05 15:57:19.680123
014	353	23241216349	20171113	80303635	FA	028800094498	00000224836	00000000200	00000004497	3	2017-12-05 15:57:19.680123
014	354	11111111111	20171113	09892025	FA	028800258877	00000187440	00000000600	00000011246	3	2017-12-05 15:57:19.680123
014	355	20182383016	20171113	80763758	FA	028800258878	00000142347	00000000200	00000002847	3	2017-12-05 15:57:19.680123
014	356	11111111111	20171113	80763759	FA	028800258879	00000124378	00000000600	00000007463	3	2017-12-05 15:57:19.680123
014	357	27163155341	20171114	09892032	FA	029100165791	00000086826	00000000200	00000001736	3	2017-12-05 15:57:19.680123
014	358	11111111111	20171114	80763772	FA	029100165793	00000208054	00000000600	00000012483	3	2017-12-05 15:57:19.680123
014	359	27111171829	20171114	80763775	FA	028800258895	00000447097	00000000200	00000008942	3	2017-12-05 15:57:19.680123
014	360	20922996297	20171114	80303640	FA	029100066104	00000439643	00000000200	00000008793	3	2017-12-05 15:57:19.680123
014	361	20922996297	20171114	80303641	FA	028800094502	00000268905	00000000200	00000005378	3	2017-12-05 15:57:19.680123
014	362	23078116749	20171114	09892036	FA	029100165813	00000052394	00000000200	00000001048	3	2017-12-05 15:57:19.680123
014	363	23078116749	20171114	09892037	FA	029100165814	00000065275	00000000200	00000001305	3	2017-12-05 15:57:19.680123
014	364	11111111111	20171114	80763787	FA	029100165815	00000058635	00000000600	00000003518	3	2017-12-05 15:57:19.680123
014	365	11111111111	20171114	80763788	FA	029100165823	00000461945	00000000600	00000027717	3	2017-12-05 15:57:19.680123
014	366	20263575718	20171114	09892040	FA	029100165824	00000179546	00000000200	00000003591	3	2017-12-05 15:57:19.680123
014	367	20179005833	20171114	80303653	FA	029100066125	00000156363	00000000200	00000003127	3	2017-12-05 15:57:19.680123
012	245	30670364603	20171115	00004550	FA	000000035248	00000209184	00000000200	00000004184	16	2017-12-06 15:31:35.056328
012	246	27952625514	20171115	00004551	FA	000000035275	00000317378	00000000200	00000006348	16	2017-12-06 15:31:35.056328
012	247	27952625514	20171115	00004552	FA	000000035276	00000247113	00000000200	00000004942	16	2017-12-06 15:31:35.056328
012	248	27952625514	20171115	00004553	FA	000000035277	00000043173	00000000200	00000000863	16	2017-12-06 15:31:35.056328
012	249	27188206463	20171115	00004554	FA	000000035278	00000070290	00000000200	00000001406	16	2017-12-06 15:31:35.056328
012	250	27173955451	20171115	00004555	FA	000000035279	00000048801	00000000200	00000000976	16	2017-12-06 15:31:35.056328
012	251	27222713140	20171115	00004556	FA	000000035280	00000077395	00000000200	00000001548	16	2017-12-06 15:31:35.056328
012	252	27222713140	20171115	00004557	FA	000000035281	00000101173	00000000200	00000002023	16	2017-12-06 15:31:35.056328
012	253	23163173824	20171115	00004558	FA	000000035282	00000026934	00000000200	00000000539	16	2017-12-06 15:31:35.056328
012	254	27125944707	20171115	00004559	FA	000000035283	00000053834	00000000200	00000001077	16	2017-12-06 15:31:35.056328
012	255	20261719887	20171115	00004560	FA	000000035284	00000092792	00000000200	00000001856	16	2017-12-06 15:31:35.056328
012	256	20261719887	20171115	00004561	FA	000000035285	00000175994	00000000200	00000003520	16	2017-12-06 15:31:35.056328
012	257	20261719887	20171115	00004562	FA	000000035286	00000183263	00000000200	00000003665	16	2017-12-06 15:31:35.056328
012	258	20261719887	20171115	00004563	FA	000000035287	00000229408	00000000200	00000004588	16	2017-12-06 15:31:35.056328
012	259	23289795464	20171115	00004564	FA	000000035288	00000146947	00000000200	00000002939	16	2017-12-06 15:31:35.056328
012	260	23289795464	20171115	00004565	FA	000000035289	00000139999	00000000200	00000002800	16	2017-12-06 15:31:35.056328
012	261	23289795464	20171115	00004566	FA	000000035290	00000147370	00000000200	00000002947	16	2017-12-06 15:31:35.056328
012	262	23289795464	20171115	00004567	FA	000000035291	00000155872	00000000200	00000003117	16	2017-12-06 15:31:35.056328
012	263	23289795464	20171115	00004568	FA	000000035292	00000132579	00000000200	00000002652	16	2017-12-06 15:31:35.056328
012	264	23289795464	20171115	00004569	FA	000000035293	00000101312	00000000200	00000002026	16	2017-12-06 15:31:35.056328
012	265	27056603560	20171115	00004570	FA	000000035294	00000144912	00000000200	00000002898	16	2017-12-06 15:31:35.056328
012	266	20054139285	20171115	00004571	FA	000000035295	00000097113	00000000200	00000001942	16	2017-12-06 15:31:35.056328
012	267	30670364603	20171115	00004572	FA	000000035296	00000221543	00000000200	00000004431	16	2017-12-06 15:31:35.056328
012	268	30670364603	20171115	00004573	FA	000000035297	00000178772	00000000200	00000003575	16	2017-12-06 15:31:35.056328
012	269	20305781720	20171115	00004574	FA	000000035298	00000168127	00000000200	00000003363	16	2017-12-06 15:31:35.056328
012	270	20305781720	20171115	00004575	FA	000000035299	00000005776	00000000201	00000000116	16	2017-12-06 15:31:35.056328
012	271	30670362317	20171115	00004576	FA	000000035300	00000108593	00000000200	00000002172	16	2017-12-06 15:31:35.056328
012	272	27952625514	20171115	00004577	FA	000000035301	00000093841	00000000200	00000001877	16	2017-12-06 15:31:35.056328
012	273	30670364603	20171115	00004578	FA	000000035302	00000024696	00000000200	00000000494	16	2017-12-06 15:31:35.056328
012	274	27064350809	20171115	00004579	FA	000000035250	00000033364	00000000200	00000000667	16	2017-12-06 15:31:35.056328
012	275	27215184760	20171115	00004580	FB	000000059462	00000028302	00000000200	00000000566	16	2017-12-06 15:31:35.056328
012	276	23331976199	20171115	00004581	FB	000000059498	00000152740	00000000200	00000003055	16	2017-12-06 15:31:35.056328
012	277	23331976199	20171115	00004582	FB	000000059499	00000051556	00000000200	00000001031	16	2017-12-06 15:31:35.056328
012	278	27363206641	20171115	00004583	FB	000000059500	00000241899	00000000200	00000004838	16	2017-12-06 15:31:35.056328
012	279	27363206641	20171115	00004584	FB	000000059501	00000068285	00000000200	00000001366	16	2017-12-06 15:31:35.056328
012	280	27313340339	20171115	00004585	FB	000000059502	00000042696	00000000200	00000000854	16	2017-12-06 15:31:35.056328
014	368	11111111111	20171114	80763793	FA	029100165829	00000468400	00000000600	00000028104	3	2017-12-05 15:57:19.680123
014	369	11111111111	20171114	09892041	FA	029100165838	00000450616	00000000600	00000027037	3	2017-12-05 15:57:19.680123
014	370	30670364603	20171114	09823828	FA	029100066128	00000247850	00000000200	00000004957	3	2017-12-05 15:57:19.680123
014	371	30715184040	20171114	09823829	FA	029100066131	00000132463	00000000200	00000002649	3	2017-12-05 15:57:19.680123
014	372	30653645143	20171114	80763808	FA	029100165863	00000427203	00000000200	00000008544	3	2017-12-05 15:57:19.680123
014	373	11111111111	20171114	80763809	FA	029100165864	00000133780	00000000600	00000008027	3	2017-12-05 15:57:19.680123
014	374	27259753274	20171114	80303656	FA	029100066132	00000630773	00000000200	00000012616	3	2017-12-05 15:57:19.680123
014	375	20169989592	20171114	09892072	FA	028800258944	00000284903	00000000200	00000005698	3	2017-12-05 15:57:19.680123
014	376	11111111111	20171114	80763867	FA	029100165952	00000230608	00000000600	00000013836	3	2017-12-05 15:57:19.680123
014	377	11111111111	20171114	80763868	FA	029100165953	00000024696	00000000600	00000001482	3	2017-12-05 15:57:19.680123
014	378	27148843258	20171114	80763877	FA	028800258953	00000132623	00000000200	00000002653	3	2017-12-05 15:57:19.680123
014	379	27166891936	20171114	09892084	FA	028800258966	00000186178	00000000200	00000003724	3	2017-12-05 15:57:19.680123
014	380	11111111111	20171114	09892086	FA	028800258968	00000199877	00000000600	00000011993	3	2017-12-05 15:57:19.680123
014	381	23125944124	20171114	80763900	FA	028800258996	00000428085	00000000200	00000008562	3	2017-12-05 15:57:19.680123
014	382	11111111111	20171114	09892098	FA	028800259000	00000362445	00000000600	00000021747	3	2017-12-05 15:57:19.680123
014	383	11111111111	20171114	09892099	FA	028800259002	00000232313	00000000600	00000013939	3	2017-12-05 15:57:19.680123
014	384	20169211222	20171115	09892100	FA	029100166002	00000076181	00000000200	00000001524	3	2017-12-05 15:57:19.680123
014	385	27141080534	20171115	09892102	FA	029100166021	00000402456	00000000200	00000008049	3	2017-12-05 15:57:19.680123
014	386	27943434803	20171115	80763925	FA	029100166033	00000585767	00000000200	00000011715	3	2017-12-05 15:57:19.680123
014	387	27943434803	20171115	80763927	FA	029100166036	00000021343	00000000200	00000000427	3	2017-12-05 15:57:19.680123
014	388	20170936621	20171115	80303683	FA	029100066170	00000290984	00000000200	00000005820	3	2017-12-05 15:57:19.680123
014	389	20066520200	20171115	09892115	FA	029100166066	00000109642	00000000200	00000002193	3	2017-12-05 15:57:19.680123
014	390	20178081498	20171115	80763952	FA	028800259007	00000028068	00000000200	00000000561	3	2017-12-05 15:57:19.680123
014	391	20083968436	20171115	80303701	FA	029100066186	00000061345	00000000200	00000001227	3	2017-12-05 15:57:19.680123
014	392	27259753274	20171115	80303703	FA	028800094530	00000494305	00000000200	00000009886	3	2017-12-05 15:57:19.680123
014	393	11111111111	20171115	80763964	FA	028800259020	00000149550	00000000600	00000008973	3	2017-12-05 15:57:19.680123
014	394	20078206331	20171115	80303707	FA	028800094534	00000449278	00000000200	00000008986	3	2017-12-05 15:57:19.680123
014	395	11111111111	20171115	09892137	FA	028800259034	00000327386	00000000600	00000019643	3	2017-12-05 15:57:19.680123
014	396	11111111111	20171115	09892138	FA	028800259035	00000003000	00000000600	00000000180	3	2017-12-05 15:57:19.680123
014	397	27125944073	20171115	09823847	FA	028800094545	00000522529	00000000200	00000010451	3	2017-12-05 15:57:19.680123
014	398	27125944073	20171115	80303712	FA	028800094547	00000285920	00000000200	00000005719	3	2017-12-05 15:57:19.680123
014	399	11111111111	20171115	80763975	FA	029100166110	00000274095	00000000600	00000016446	3	2017-12-05 15:57:19.680123
014	400	27066634561	20171115	09892140	FA	029100166113	00000959878	00000000200	00000019198	3	2017-12-05 15:57:19.680123
014	401	27302090977	20171115	80303714	FA	029100066188	00000097267	00000000200	00000001945	3	2017-12-05 15:57:19.680123
014	402	11111111111	20171115	80763978	FA	029100166120	00000066876	00000000600	00000004013	3	2017-12-05 15:57:19.680123
014	403	11111111111	20171115	80763979	FA	029100166121	00000033621	00000000600	00000002017	3	2017-12-05 15:57:19.680123
014	404	27011995093	20171115	80763984	FA	029100166132	00000302808	00000000200	00000006056	3	2017-12-05 15:57:19.680123
014	405	11111111111	20171115	09892148	FA	029100166135	00000082205	00000000600	00000004932	3	2017-12-05 15:57:19.680123
014	406	20240184576	20171115	80763995	FA	028800259045	00000378560	00000000200	00000007571	3	2017-12-05 15:57:19.680123
014	407	20937978473	20171115	80763997	FA	029100166150	00000130065	00000000200	00000002601	3	2017-12-05 15:57:19.680123
014	408	11111111111	20171115	80763998	FA	028800259055	00000110531	00000000600	00000006632	3	2017-12-05 15:57:19.680123
014	409	20290918759	20171115	09892153	FA	029100166152	00000232066	00000000200	00000004641	3	2017-12-05 15:57:19.680123
014	410	11111111111	20171115	09892154	FA	029100166153	00000075578	00000000600	00000004535	3	2017-12-05 15:57:19.680123
014	411	11111111111	20171115	08064344	NC	028800012042	-0000110531	00000000600	-0000006632	3	2017-12-05 15:57:19.680123
014	412	11111111111	20171115	09892155	FA	029100166154	00000110531	00000000600	00000006632	3	2017-12-05 15:57:19.680123
014	413	20240184576	20171115	08064345	NC	029100011356	-0000049174	00000000200	-0000000984	3	2017-12-05 15:57:19.680123
014	414	27125944073	20171115	00981619	NC	028800006226	-0000522529	00000000200	-0000010451	3	2017-12-05 15:57:19.680123
014	415	20149262084	20171115	80764016	FA	028800259068	00000054044	00000000200	00000001081	3	2017-12-05 15:57:19.680123
014	416	11111111111	20171115	09892172	FA	028800259069	00000154931	00000000600	00000009296	3	2017-12-05 15:57:19.680123
014	417	11111111111	20171115	09892173	FA	029100166190	00000258502	00000000600	00000015510	3	2017-12-05 15:57:19.680123
014	418	20202363637	20171115	80303732	FA	028800094572	00000210730	00000000200	00000004215	3	2017-12-05 15:57:19.680123
012	281	20313340792	20171115	00004586	FB	000000059504	00000049330	00000000200	00000000987	16	2017-12-06 15:31:35.056328
012	282	27112378257	20171115	00004587	FB	000000059505	00000113446	00000000200	00000002269	16	2017-12-06 15:31:35.056328
012	283	27112378257	20171115	00004588	FB	000000059506	00000040338	00000000200	00000000807	16	2017-12-06 15:31:35.056328
012	284	27215184760	20171115	00004589	FB	000000059507	00000024266	00000000200	00000000485	16	2017-12-06 15:31:35.056328
012	285	27219273431	20171115	00004590	FB	000000059508	00000147399	00000000200	00000002948	16	2017-12-06 15:31:35.056328
012	286	27044920102	20171115	00004591	FB	000000059509	00000038656	00000000200	00000000773	16	2017-12-06 15:31:35.056328
012	287	27394430655	20171115	00004592	FB	000000059514	00000067648	00000000200	00000001353	16	2017-12-06 15:31:35.056328
012	288	23331976199	20171115	00004593	FB	000000059515	00000024696	00000000200	00000000494	16	2017-12-06 15:31:35.056328
012	289	27363206641	20171115	00004594	FB	000000059516	00000006348	00000000200	00000000127	16	2017-12-06 15:31:35.056328
012	290	27056603560	20171115	00004595	OT	000000002167	-0000006220	00000000199	-0000000124	16	2017-12-06 15:31:35.056328
012	291	20305781720	20171115	00004596	OT	000000002168	-0000006604	00000000200	-0000000132	16	2017-12-06 15:31:35.056328
012	292	27952625514	20171116	00004597	OT	000000002171	-0000008811	00000000200	-0000000176	16	2017-12-06 15:31:35.056328
012	293	27173955451	20171121	00004598	FA	000000035437	00000176379	00000000200	00000003528	16	2017-12-06 15:31:35.056328
012	294	27173955451	20171121	00004599	FA	000000035438	00000007952	00000000200	00000000159	16	2017-12-06 15:31:35.056328
012	295	27222713140	20171121	00004600	FA	000000035439	00000078444	00000000200	00000001569	16	2017-12-06 15:31:35.056328
012	296	27222713140	20171121	00004601	FA	000000035440	00000061221	00000000200	00000001224	16	2017-12-06 15:31:35.056328
012	297	27056603560	20171121	00004602	FA	000000035441	00000035020	00000000200	00000000700	16	2017-12-06 15:31:35.056328
012	298	27056603560	20171121	00004603	FA	000000035442	00000049689	00000000200	00000000994	16	2017-12-06 15:31:35.056328
012	299	27367571123	20171121	00004604	FA	000000035443	00000164269	00000000200	00000003285	16	2017-12-06 15:31:35.056328
012	300	27367571123	20171121	00004605	FA	000000035444	00000060549	00000000200	00000001211	16	2017-12-06 15:31:35.056328
012	301	27125944707	20171121	00004606	FA	000000035445	00000050763	00000000200	00000001015	16	2017-12-06 15:31:35.056328
012	302	27064350809	20171121	00004607	FA	000000035446	00000182241	00000000200	00000003645	16	2017-12-06 15:31:35.056328
012	303	27064350809	20171121	00004608	FA	000000035447	00000048326	00000000200	00000000967	16	2017-12-06 15:31:35.056328
012	304	30715278347	20171121	00004609	FA	000000035448	00000234995	00000000200	00000004700	16	2017-12-06 15:31:35.056328
012	305	30715278347	20171121	00004610	FA	000000035449	00000026094	00000000200	00000000522	16	2017-12-06 15:31:35.056328
012	306	23241217264	20171121	00004611	FA	000000035450	00000053425	00000000200	00000001069	16	2017-12-06 15:31:35.056328
012	307	27056603560	20171121	00004612	FA	000000035451	00000141346	00000000200	00000002827	16	2017-12-06 15:31:35.056328
012	308	27952625514	20171121	00004613	FA	000000035452	00000381765	00000000200	00000007635	16	2017-12-06 15:31:35.056328
012	309	27952625514	20171121	00004614	FA	000000035453	00000320459	00000000200	00000006409	16	2017-12-06 15:31:35.056328
012	310	20054139285	20171121	00004615	FA	000000035454	00000130434	00000000200	00000002609	16	2017-12-06 15:31:35.056328
012	311	27173955451	20171121	00004616	FA	000000035455	00000039901	00000000200	00000000798	16	2017-12-06 15:31:35.056328
012	312	27952625514	20171121	00004617	FA	000000035456	00000024422	00000000200	00000000488	16	2017-12-06 15:31:35.056328
012	313	20305781720	20171121	00004618	FA	000000035457	00000078451	00000000200	00000001569	16	2017-12-06 15:31:35.056328
012	314	20054139285	20171121	00004619	FA	000000035484	00000006196	00000000200	00000000124	16	2017-12-06 15:31:35.056328
012	315	30670364603	20171121	00004620	FA	000000035485	00000162479	00000000200	00000003250	16	2017-12-06 15:31:35.056328
012	316	30670364603	20171121	00004621	FA	000000035486	00000148484	00000000200	00000002970	16	2017-12-06 15:31:35.056328
012	317	27215184760	20171121	00004622	FB	000000059713	00000031464	00000000200	00000000629	16	2017-12-06 15:31:35.056328
012	318	27121002650	20171121	00004623	FB	000000059715	00000098799	00000000200	00000001976	16	2017-12-06 15:31:35.056328
012	319	27394430655	20171121	00004624	FB	000000059716	00000099762	00000000200	00000001995	16	2017-12-06 15:31:35.056328
012	320	23331976199	20171121	00004625	FB	000000059717	00000125338	00000000200	00000002507	16	2017-12-06 15:31:35.056328
012	321	23331976199	20171121	00004626	FB	000000059718	00000158136	00000000200	00000003163	16	2017-12-06 15:31:35.056328
012	322	23331976199	20171121	00004627	FB	000000059719	00000178953	00000000200	00000003579	16	2017-12-06 15:31:35.056328
012	323	23331976199	20171121	00004628	FB	000000059720	00000067981	00000000200	00000001360	16	2017-12-06 15:31:35.056328
012	324	27313340339	20171121	00004629	FB	000000059721	00000102519	00000000200	00000002050	16	2017-12-06 15:31:35.056328
012	325	27226803233	20171121	00004630	FB	000000059722	00000158918	00000000200	00000003178	16	2017-12-06 15:31:35.056328
012	326	27226803233	20171121	00004631	FB	000000059723	00000015854	00000000200	00000000317	16	2017-12-06 15:31:35.056328
012	327	27041619916	20171121	00004632	FB	000000059724	00000149750	00000000200	00000002995	16	2017-12-06 15:31:35.056328
012	328	27044920102	20171121	00004633	FB	000000059725	00000046265	00000000200	00000000925	16	2017-12-06 15:31:35.056328
012	329	20082698508	20171121	00004634	FB	000000059726	00000023088	00000000200	00000000462	16	2017-12-06 15:31:35.056328
012	330	27325453465	20171121	00004635	FB	000000059727	00000069795	00000000200	00000001396	16	2017-12-06 15:31:35.056328
012	331	27248176607	20171121	00004636	FB	000000059728	00000079168	00000000200	00000001583	16	2017-12-06 15:31:35.056328
012	332	27229348774	20171121	00004637	FB	000000059733	00000049804	00000000200	00000000996	16	2017-12-06 15:31:35.056328
012	333	23331976199	20171121	00004638	FB	000000059734	00000099832	00000000200	00000001997	16	2017-12-06 15:31:35.056328
012	334	27226803233	20171121	00004639	FB	000000059735	00000007533	00000000200	00000000151	16	2017-12-06 15:31:35.056328
012	335	20054139285	20171121	00004640	OT	000000002179	-0000002973	00000000198	-0000000059	16	2017-12-06 15:31:35.056328
012	336	27173955451	20171122	00004641	FA	000000035542	00000191701	00000000200	00000003834	16	2017-12-06 15:31:35.056328
012	337	27952625514	20171122	00004642	FA	000000035543	00000023037	00000000200	00000000461	16	2017-12-06 15:31:35.056328
012	338	27173955451	20171122	00004643	FA	000000035584	00000100055	00000000200	00000002001	16	2017-12-06 15:31:35.056328
012	339	27222713140	20171122	00004644	FA	000000035585	00000032533	00000000200	00000000651	16	2017-12-06 15:31:35.056328
012	340	27222713140	20171122	00004645	FA	000000035586	00000104016	00000000200	00000002080	16	2017-12-06 15:31:35.056328
012	341	23163173824	20171122	00004646	FA	000000035587	00000029039	00000000200	00000000581	16	2017-12-06 15:31:35.056328
012	342	27125944707	20171122	00004647	FA	000000035588	00000070216	00000000200	00000001404	16	2017-12-06 15:31:35.056328
012	343	20274035170	20171122	00004648	FA	000000035589	00000145023	00000000200	00000002900	16	2017-12-06 15:31:35.056328
001	2	03070904243	20171002	00001609	FA	110000001609	-0000148083	00000000200	-0000002962	5	2017-11-16 12:54:35.10011
001	3	02005413928	20171004	00001624	FA	110000001624	-0000941841	00000000200	-0000018835	5	2017-11-16 12:54:35.10011
001	4	02021354142	20171005	00001625	FA	110000001625	-0000016630	00000000200	-0000000333	5	2017-11-16 12:54:35.10011
001	5	03070904243	20171005	00001628	FA	110000001628	-0000163078	00000000200	-0000003262	5	2017-11-16 12:54:35.10011
001	6	02712594470	20171005	00001630	FA	110000001630	-0000069966	00000000200	-0000001399	5	2017-11-16 12:54:35.10011
001	7	03070904243	20171005	00001636	FA	110000001636	-0000260094	00000000200	-0000005202	5	2017-11-16 12:54:35.10011
001	8	02795262551	20171010	00001652	FA	110000001652	-0000305454	00000000200	-0000006110	5	2017-11-16 12:54:35.10011
001	9	02706435080	20171011	00001657	FA	110000001657	-0000235961	00000000200	-0000004718	5	2017-11-16 12:54:35.10011
010	2	30715278347	20170805	00000347	OT	030000000347	-0000092562	00000000200	-0000001852	14	2017-09-27 09:37:53.511455
010	3	27125944707	20170812	00000351	OT	030000000351	-0000029969	00000000200	-0000000599	14	2017-09-27 09:37:53.511455
010	4	27188206463	20170826	00000356	OT	030000000356	-0000115289	00000000200	-0000002305	14	2017-09-27 09:37:53.511455
010	5	27402096204	20170805	00001323	OT	030000001323	-0000036364	00000000200	-0000000727	14	2017-09-27 09:37:53.511455
010	6	27245845494	20170809	00001341	OT	030000001341	-0000123864	00000000200	-0000002479	14	2017-09-27 09:37:53.511455
010	7	27313340754	20170809	00001342	OT	030000001342	-0000073141	00000000200	-0000001463	14	2017-09-27 09:37:53.511455
010	8	20118857020	20170812	00001348	OT	030000001348	-0000102369	00000000200	-0000002047	14	2017-09-27 09:37:53.511455
010	9	27361302759	20170812	00001349	OT	030000001349	-0000066943	00000000200	-0000001339	14	2017-09-27 09:37:53.511455
010	10	20118857020	20170823	00001357	OT	030000001357	-0000016942	00000000200	-0000000339	14	2017-09-27 09:37:53.511455
010	11	20290121753	20170823	00001370	OT	030000001370	-0000094595	00000000200	-0000001893	14	2017-09-27 09:37:53.511455
010	12	27313340339	20170826	00001379	OT	030000001379	-0000075166	00000000200	-0000001503	14	2017-09-27 09:37:53.511455
010	13	30670364603	20170818	00005888	FA	030000005888	00000041755	00000000200	00000000836	14	2017-09-27 09:37:53.511455
010	14	30715278347	20170818	00005889	FA	030000005889	00000066943	00000000200	00000001339	14	2017-09-27 09:37:53.511455
010	15	20261719887	20170818	00005890	FA	030000005890	00000110083	00000000200	00000002202	14	2017-09-27 09:37:53.511455
010	16	23289795464	20170818	00005891	FA	030000005891	00000054711	00000000200	00000001095	14	2017-09-27 09:37:53.511455
010	17	27222713140	20170818	00005892	FA	030000005892	00000013740	00000000200	00000000275	14	2017-09-27 09:37:53.511455
010	18	30715278347	20170801	00005637	FA	030000005637	00000092562	00000000200	00000001852	14	2017-09-27 09:37:53.511455
010	19	20274035170	20170818	00005893	FA	030000005893	00000054050	00000000200	00000001081	14	2017-09-27 09:37:53.511455
010	20	27056603560	20170801	00005638	FA	030000005638	00000074546	00000000200	00000001492	14	2017-09-27 09:37:53.511455
010	21	23163173824	20170818	00005894	FA	030000005894	00000021488	00000000200	00000000430	14	2017-09-27 09:37:53.511455
010	22	20261719887	20170801	00005639	FA	030000005639	00000115952	00000000200	00000002319	14	2017-09-27 09:37:53.511455
010	23	27173955451	20170818	00005895	FA	030000005895	00000137814	00000000200	00000002756	14	2017-09-27 09:37:53.511455
010	24	23289795464	20170801	00005640	FA	030000005640	00000091075	00000000200	00000001822	14	2017-09-27 09:37:53.511455
010	25	27064350809	20170818	00005896	FA	030000005896	00000068596	00000000200	00000001372	14	2017-09-27 09:37:53.511455
010	26	23289795464	20170801	00005641	FA	030000005641	00000191405	00000000200	00000003830	14	2017-09-27 09:37:53.511455
010	27	27222713140	20170818	00005897	FA	030000005897	00000086777	00000000200	00000001735	14	2017-09-27 09:37:53.511455
010	28	20274035170	20170801	00005642	FA	030000005642	00000019835	00000000200	00000000397	14	2017-09-27 09:37:53.511455
010	29	27163184732	20170818	00005898	FA	030000005898	00000057852	00000000200	00000001157	14	2017-09-27 09:37:53.511455
010	30	23163173824	20170801	00005643	FA	030000005643	00000078048	00000000200	00000001561	14	2017-09-27 09:37:53.511455
010	31	20305781720	20170818	00005899	FA	030000005899	00000203013	00000000200	00000004061	14	2017-09-27 09:37:53.511455
010	32	27188206463	20170801	00005644	FA	030000005644	00000145125	00000000200	00000002903	14	2017-09-27 09:37:53.511455
010	33	20073261806	20170801	00005645	FA	030000005645	00000253967	00000000200	00000005079	14	2017-09-27 09:37:53.511455
010	34	27222713140	20170801	00005646	FA	030000005646	00000157851	00000000200	00000003157	14	2017-09-27 09:37:53.511455
010	35	27064350809	20170801	00005647	FA	030000005647	00000071074	00000000200	00000001421	14	2017-09-27 09:37:53.511455
010	36	27163184732	20170801	00005648	FA	030000005648	00000060471	00000000200	00000001209	14	2017-09-27 09:37:53.511455
010	37	20305781720	20170801	00005649	FA	030000005649	00000104463	00000000200	00000002090	14	2017-09-27 09:37:53.511455
010	38	27056603560	20170822	00005924	FA	030000005924	00000106733	00000000200	00000002135	14	2017-09-27 09:37:53.511455
010	39	20261719887	20170822	00005925	FA	030000005925	00000238347	00000000200	00000004768	14	2017-09-27 09:37:53.511455
010	40	23289795464	20170822	00005926	FA	030000005926	00000103471	00000000200	00000002070	14	2017-09-27 09:37:53.511455
010	41	27125944707	20170822	00005927	FA	030000005927	00000030488	00000000200	00000000610	14	2017-09-27 09:37:53.511455
010	42	27100281819	20170822	00005928	FA	030000005928	00000042975	00000000200	00000000860	14	2017-09-27 09:37:53.511455
010	43	23163173824	20170822	00005929	FA	030000005929	00000021488	00000000200	00000000430	14	2017-09-27 09:37:53.511455
010	44	27173955451	20170822	00005930	FA	030000005930	00000066943	00000000200	00000001339	14	2017-09-27 09:37:53.511455
010	45	20073261806	20170822	00005931	FA	030000005931	00000181818	00000000200	00000003636	14	2017-09-27 09:37:53.511455
010	46	27188206463	20170822	00005932	FA	030000005932	00000115289	00000000200	00000002305	14	2017-09-27 09:37:53.511455
010	47	27222713140	20170822	00005933	FA	030000005933	00000057852	00000000200	00000001157	14	2017-09-27 09:37:53.511455
010	48	27163184732	20170822	00005934	FA	030000005934	00000074705	00000000200	00000001495	14	2017-09-27 09:37:53.511455
010	49	30670364603	20170804	00005689	FA	030000005689	00000016860	00000000200	00000000338	14	2017-09-27 09:37:53.511455
010	50	30715278347	20170804	00005690	FA	030000005690	00000136528	00000000200	00000002731	14	2017-09-27 09:37:53.511455
010	51	27056603560	20170804	00005691	FA	030000005691	00000066943	00000000200	00000001339	14	2017-09-27 09:37:53.511455
010	52	20261719887	20170804	00005692	FA	030000005692	00000188679	00000000200	00000003774	14	2017-09-27 09:37:53.511455
010	53	23289795464	20170804	00005693	FA	030000005693	00000164959	00000000200	00000003299	14	2017-09-27 09:37:53.511455
010	54	23289795464	20170804	00005694	FA	030000005694	00000152315	00000000200	00000003047	14	2017-09-27 09:37:53.511455
010	55	20274035170	20170804	00005695	FA	030000005695	00000046281	00000000200	00000000925	14	2017-09-27 09:37:53.511455
010	56	27100281819	20170804	00005696	FA	030000005696	00000050414	00000000200	00000001009	14	2017-09-27 09:37:53.511455
010	57	27188206463	20170804	00005697	FA	030000005697	00000138844	00000000200	00000002777	14	2017-09-27 09:37:53.511455
010	58	27173955451	20170804	00005698	FA	030000005698	00000155080	00000000200	00000003102	14	2017-09-27 09:37:53.511455
001	10	03070904243	20171012	00001663	FA	110000001663	-0000219204	00000000200	-0000004384	5	2017-11-16 12:54:35.10011
014	419	11111111111	20171115	09892177	FA	028800259072	00000244586	00000000600	00000014675	3	2017-12-05 15:57:19.680123
014	420	20202363637	20171115	09823864	FA	028800094569	00000264538	00000000200	00000005291	3	2017-12-05 15:57:19.680123
014	421	20248923319	20171115	09823865	FA	028800094574	00000254945	00000000200	00000005099	3	2017-12-05 15:57:19.680123
014	422	20248923319	20171115	09823866	FA	028800094576	00000040764	00000000200	00000000815	3	2017-12-05 15:57:19.680123
014	423	11111111111	20171115	09892179	FA	028800259075	00000500355	00000000600	00000030021	3	2017-12-05 15:57:19.680123
014	424	11111111111	20171115	80764021	FA	028800259078	00000056660	00000000600	00000003400	3	2017-12-05 15:57:19.680123
014	425	11111111111	20171115	80764022	FA	028800259080	00000188872	00000000600	00000011332	3	2017-12-05 15:57:19.680123
014	426	11111111111	20171115	80764023	FA	028800259081	00000042419	00000000600	00000002545	3	2017-12-05 15:57:19.680123
014	427	11111111111	20171115	09892181	FA	028800259086	00000124003	00000000600	00000007440	3	2017-12-05 15:57:19.680123
014	428	20078146169	20171116	80764039	FA	029100166200	00000602544	00000000200	00000012051	3	2017-12-05 15:57:19.680123
014	429	27056603560	20171116	80303745	FA	029100066226	00000849951	00000000200	00000016999	3	2017-12-05 15:57:19.680123
014	430	27952625514	20171116	08035376	NC	028800006230	-0000072810	00000000200	-0000001456	3	2017-12-05 15:57:19.680123
014	431	27166891936	20171116	09892195	FA	029100166219	00000137374	00000000200	00000002748	3	2017-12-05 15:57:19.680123
014	432	11111111111	20171116	80764054	FA	029100166224	00000605461	00000000600	00000036328	3	2017-12-05 15:57:19.680123
014	433	23125944329	20171116	80303754	FA	029100066247	00000156986	00000000200	00000003140	3	2017-12-05 15:57:19.680123
014	434	20078146169	20171116	09892200	FA	029100166240	00000125897	00000000200	00000002518	3	2017-12-05 15:57:19.680123
014	435	20182225992	20171116	80303763	FA	029100066265	00000176063	00000000200	00000003521	3	2017-12-05 15:57:19.680123
014	436	27100281819	20171116	80303765	FA	029100066271	00000353139	00000000200	00000007063	3	2017-12-05 15:57:19.680123
014	437	20229649060	20171116	09892211	FA	029100166278	00000389997	00000000200	00000007800	3	2017-12-05 15:57:19.680123
014	438	20169103381	20171116	09892216	FA	029100166290	00000210199	00000000200	00000004204	3	2017-12-05 15:57:19.680123
014	439	23930384003	20171116	80764091	FA	029100166292	00000108622	00000000200	00000002172	3	2017-12-05 15:57:19.680123
014	440	23930384003	20171116	80764092	FA	029100166293	00000090289	00000000200	00000001806	3	2017-12-05 15:57:19.680123
014	441	23930384003	20171116	80764093	FA	029100166294	00000007482	00000000200	00000000150	3	2017-12-05 15:57:19.680123
014	442	23930384003	20171116	80764095	FA	029100166295	00000033341	00000000200	00000000667	3	2017-12-05 15:57:19.680123
014	443	30653645143	20171116	09892230	FA	028800259167	00000090306	00000000200	00000001806	3	2017-12-05 15:57:19.680123
014	444	27952625514	20171116	08035382	NC	028800006236	-0000113710	00000000200	-0000002274	3	2017-12-05 15:57:19.680123
014	445	20290918759	20171116	80764155	FA	029100166386	00000196232	00000000200	00000003925	3	2017-12-05 15:57:19.680123
014	446	20208910524	20171116	80303792	FA	029100066308	00000058709	00000000200	00000001174	3	2017-12-05 15:57:19.680123
014	447	30670364778	20171116	80764159	FA	029100166391	00000207495	00000000200	00000004150	3	2017-12-05 15:57:19.680123
014	448	23215564894	20171116	09823884	FA	029100066310	00000458382	00000000200	00000009168	3	2017-12-05 15:57:19.680123
014	449	23237095979	20171116	80764164	FA	029100166412	00000285632	00000000200	00000005713	3	2017-12-05 15:57:19.680123
014	450	23237095979	20171116	80764168	FA	029100166413	00000117810	00000000200	00000002356	3	2017-12-05 15:57:19.680123
014	451	23237095979	20171116	80764169	FA	029100166416	00000020380	00000000200	00000000408	3	2017-12-05 15:57:19.680123
014	452	27237091316	20171116	09892259	FA	029100166432	00000417876	00000000200	00000008357	3	2017-12-05 15:57:19.680123
014	453	27047139622	20171117	09892275	FA	029100166443	00000401734	00000000200	00000008035	3	2017-12-05 15:57:19.680123
014	454	20922996297	20171117	80303812	FA	029100066334	00000725591	00000000200	00000014512	3	2017-12-05 15:57:19.680123
014	455	20261719887	20171117	80303817	FA	029100066344	00002302396	00000000200	00000046049	3	2017-12-05 15:57:19.680123
014	456	27205190134	20171117	09823890	FA	029100066341	00000094664	00000000200	00000001893	3	2017-12-05 15:57:19.680123
014	457	20290121222	20171117	09892282	FA	029100166487	00000408921	00000000200	00000008179	3	2017-12-05 15:57:19.680123
014	458	11111111111	20171117	80764250	FA	029100166492	00000314446	00000000600	00000018866	3	2017-12-05 15:57:19.680123
014	459	20261719887	20171117	08035388	NC	029100006388	-0000080000	00000000200	-0000001600	3	2017-12-05 15:57:19.680123
014	460	23241216349	20171117	80303818	FA	029100066347	00000388836	00000000200	00000007777	3	2017-12-05 15:57:19.680123
014	461	27173955451	20171117	09823891	FA	029100066348	00000150891	00000000200	00000003018	3	2017-12-05 15:57:19.680123
014	462	11111111111	20171117	80764252	FA	029100166496	00000258382	00000000600	00000015503	3	2017-12-05 15:57:19.680123
014	463	20937978457	20171117	80764253	FA	029100166498	00000396659	00000000200	00000007933	3	2017-12-05 15:57:19.680123
014	464	20228198669	20171117	09892283	FA	029100166500	00000270230	00000000200	00000005405	3	2017-12-05 15:57:19.680123
014	465	11111111111	20171117	80764254	FA	029100166501	00000025772	00000000600	00000001546	3	2017-12-05 15:57:19.680123
014	466	20178691849	20171117	80303819	FA	029100066349	00000403460	00000000200	00000008069	3	2017-12-05 15:57:19.680123
014	467	20200953569	20171117	80303822	FA	029100066351	00000233950	00000000200	00000004679	3	2017-12-05 15:57:19.680123
014	468	20078194899	20171117	80303823	FA	029100066358	00000383392	00000000200	00000007668	3	2017-12-05 15:57:19.680123
014	469	11111111111	20171117	80764263	FA	029100166511	00000324186	00000000600	00000019451	3	2017-12-05 15:57:19.680123
014	470	20261719887	20171117	08035389	NC	029100006389	-0000021240	00000000200	-0000000425	3	2017-12-05 15:57:19.680123
014	471	30709978248	20171117	80303828	FA	029100066368	00000279105	00000000200	00000005582	3	2017-12-05 15:57:19.680123
014	472	30709978248	20171117	80303829	FA	029100066369	00000011115	00000000200	00000000222	3	2017-12-05 15:57:19.680123
014	473	30709978248	20171117	08035390	NC	029100006390	-0000004754	00000000200	-0000000095	3	2017-12-05 15:57:19.680123
014	474	11111111111	20171117	80764273	FA	029100166536	00000038503	00000000600	00000002310	3	2017-12-05 15:57:19.680123
014	475	20367604302	20171117	80764274	FA	029100166537	00000289128	00000000200	00000005783	3	2017-12-05 15:57:19.680123
014	476	11111111111	20171117	80764275	FA	029100166538	00000101374	00000000600	00000006082	3	2017-12-05 15:57:19.680123
012	344	20274035170	20171122	00004649	FA	000000035590	00000128258	00000000200	00000002565	16	2017-12-06 15:31:35.056328
012	345	27056603560	20171122	00004650	FA	000000035591	00000083865	00000000200	00000001677	16	2017-12-06 15:31:35.056328
012	346	20054139285	20171122	00004651	FA	000000035592	00000046530	00000000200	00000000931	16	2017-12-06 15:31:35.056328
012	347	23289795464	20171122	00004652	FA	000000035593	00000180713	00000000200	00000003614	16	2017-12-06 15:31:35.056328
012	348	23289795464	20171122	00004653	FA	000000035594	00000081036	00000000200	00000001621	16	2017-12-06 15:31:35.056328
012	349	20305781720	20171122	00004654	FA	000000035595	00000208617	00000000200	00000004172	16	2017-12-06 15:31:35.056328
012	350	20305781720	20171122	00004655	FA	000000035596	00000014828	00000000200	00000000297	16	2017-12-06 15:31:35.056328
012	351	30670362317	20171122	00004656	FA	000000035597	00000038322	00000000200	00000000766	16	2017-12-06 15:31:35.056328
012	352	27952625514	20171122	00004657	FA	000000035598	00000368131	00000000200	00000007363	16	2017-12-06 15:31:35.056328
012	353	27952625514	20171122	00004658	FA	000000035599	00000390976	00000000200	00000007820	16	2017-12-06 15:31:35.056328
012	354	20261719887	20171122	00004659	FA	000000035600	00000157250	00000000200	00000003145	16	2017-12-06 15:31:35.056328
012	355	20261719887	20171122	00004660	FA	000000035601	00000185294	00000000200	00000003706	16	2017-12-06 15:31:35.056328
012	356	20261719887	20171122	00004661	FA	000000035602	00000241134	00000000200	00000004823	16	2017-12-06 15:31:35.056328
012	357	30670364603	20171122	00004662	FA	000000035603	00000188034	00000000200	00000003761	16	2017-12-06 15:31:35.056328
012	358	30670364603	20171122	00004663	FA	000000035604	00000237510	00000000200	00000004750	16	2017-12-06 15:31:35.056328
012	359	30670364603	20171122	00004664	FA	000000035605	00000006519	00000000199	00000000130	16	2017-12-06 15:31:35.056328
012	360	23289795464	20171122	00004665	FA	000000035606	00000184289	00000000200	00000003686	16	2017-12-06 15:31:35.056328
012	361	23289795464	20171122	00004666	FA	000000035607	00000183739	00000000200	00000003675	16	2017-12-06 15:31:35.056328
012	362	23289795464	20171122	00004667	FA	000000035608	00000175999	00000000200	00000003520	16	2017-12-06 15:31:35.056328
012	363	23289795464	20171122	00004668	FA	000000035609	00000258997	00000000200	00000005180	16	2017-12-06 15:31:35.056328
012	364	27215184760	20171122	00004669	FB	000000059870	00000050316	00000000200	00000001006	16	2017-12-06 15:31:35.056328
012	365	27248176607	20171122	00004670	FB	000000059872	00000053460	00000000200	00000001069	16	2017-12-06 15:31:35.056328
012	366	27363206641	20171122	00004671	FB	000000059920	00000280739	00000000200	00000005615	16	2017-12-06 15:31:35.056328
012	367	27363206641	20171122	00004672	FB	000000059921	00000175581	00000000200	00000003512	16	2017-12-06 15:31:35.056328
012	368	27313340339	20171122	00004673	FB	000000059922	00000075435	00000000200	00000001509	16	2017-12-06 15:31:35.056328
012	369	20313340792	20171122	00004674	FB	000000059924	00000022800	00000000200	00000000456	16	2017-12-06 15:31:35.056328
012	370	27112378257	20171122	00004675	FB	000000059925	00000094008	00000000200	00000001880	16	2017-12-06 15:31:35.056328
012	371	27215184760	20171122	00004676	FB	000000059926	00000032123	00000000200	00000000642	16	2017-12-06 15:31:35.056328
012	372	27219273431	20171122	00004677	FB	000000059927	00000215404	00000000200	00000004308	16	2017-12-06 15:31:35.056328
012	373	27121002650	20171122	00004678	FB	000000059928	00000078074	00000000200	00000001561	16	2017-12-06 15:31:35.056328
012	374	27044920102	20171122	00004679	FB	000000059929	00000052874	00000000200	00000001057	16	2017-12-06 15:31:35.056328
012	375	27044920102	20171122	00004680	FB	000000059930	00000038018	00000000200	00000000760	16	2017-12-06 15:31:35.056328
012	376	27248176607	20171122	00004681	FB	000000059933	00000070644	00000000200	00000001413	16	2017-12-06 15:31:35.056328
012	377	27394430655	20171122	00004682	FB	000000059935	00000139839	00000000200	00000002797	16	2017-12-06 15:31:35.056328
012	378	27313340339	20171122	00004683	FB	000000059936	00000030711	00000000200	00000000614	16	2017-12-06 15:31:35.056328
012	379	27952625514	20171122	00004684	OT	000000002182	-0000023037	00000000200	-0000000461	16	2017-12-06 15:31:35.056328
012	380	23331976199	20171122	00004685	OT	000000002519	-0000016424	00000000200	-0000000328	16	2017-12-06 15:31:35.056328
012	381	27173955451	20171123	00004686	OT	000000002188	-0000033208	00000000200	-0000000664	16	2017-12-06 15:31:35.056328
012	382	27186187666	20171127	00004687	FB	000000060179	00000159874	00000000200	00000003197	16	2017-12-06 15:31:35.056328
012	383	27173955451	20171128	00004688	FA	000000035844	00000127667	00000000200	00000002553	16	2017-12-06 15:31:35.056328
012	384	27173955451	20171128	00004689	FA	000000035845	00000020633	00000000200	00000000413	16	2017-12-06 15:31:35.056328
012	385	27222713140	20171128	00004690	FA	000000035846	00000106930	00000000200	00000002139	16	2017-12-06 15:31:35.056328
012	386	27222713140	20171128	00004691	FA	000000035847	00000096301	00000000200	00000001926	16	2017-12-06 15:31:35.056328
012	387	23163173824	20171128	00004692	FA	000000035848	00000050956	00000000200	00000001019	16	2017-12-06 15:31:35.056328
012	388	27367571123	20171128	00004693	FA	000000035849	00000107934	00000000200	00000002159	16	2017-12-06 15:31:35.056328
012	389	27367571123	20171128	00004694	FA	000000035850	00000122036	00000000200	00000002441	16	2017-12-06 15:31:35.056328
012	390	27064350809	20171128	00004695	FA	000000035851	00000144702	00000000200	00000002894	16	2017-12-06 15:31:35.056328
012	391	27064350809	20171128	00004696	FA	000000035852	00000034979	00000000200	00000000700	16	2017-12-06 15:31:35.056328
012	392	27064350809	20171128	00004697	FA	000000035853	00000034530	00000000200	00000000691	16	2017-12-06 15:31:35.056328
012	393	27056603560	20171128	00004698	FA	000000035854	00000174458	00000000200	00000003489	16	2017-12-06 15:31:35.056328
012	394	27952625514	20171128	00004699	FA	000000035855	00000224213	00000000200	00000004484	16	2017-12-06 15:31:35.056328
012	395	27952625514	20171128	00004700	FA	000000035856	00000239652	00000000200	00000004793	16	2017-12-06 15:31:35.056328
012	396	27952625514	20171128	00004701	FA	000000035857	00000518967	00000000200	00000010379	16	2017-12-06 15:31:35.056328
012	397	27952625514	20171128	00004702	FA	000000035858	00000212900	00000000200	00000004258	16	2017-12-06 15:31:35.056328
012	398	27952625514	20171128	00004703	FA	000000035859	00000013177	00000000200	00000000264	16	2017-12-06 15:31:35.056328
012	399	20054139285	20171128	00004704	FA	000000035860	00000080922	00000000200	00000001618	16	2017-12-06 15:31:35.056328
012	400	30670362317	20171128	00004705	FA	000000035861	00000139605	00000000200	00000002792	16	2017-12-06 15:31:35.056328
012	401	30670362317	20171128	00004706	FA	000000035862	00000140061	00000000200	00000002801	16	2017-12-06 15:31:35.056328
012	402	30670364603	20171128	00004707	FA	000000035863	00000134843	00000000200	00000002697	16	2017-12-06 15:31:35.056328
012	403	30670364603	20171128	00004708	FA	000000035864	00000181132	00000000200	00000003623	16	2017-12-06 15:31:35.056328
012	404	30670364603	20171128	00004709	FA	000000035865	00000065112	00000000200	00000001302	16	2017-12-06 15:31:35.056328
012	405	27313340339	20171128	00004710	FB	000000060195	00000020094	00000000200	00000000402	16	2017-12-06 15:31:35.056328
012	406	27215184760	20171128	00004711	FB	000000060196	00000041364	00000000200	00000000827	16	2017-12-06 15:31:35.056328
012	407	27121002650	20171128	00004712	FB	000000060198	00000078804	00000000200	00000001576	16	2017-12-06 15:31:35.056328
012	408	27121002650	20171128	00004713	FB	000000060199	00000023744	00000000200	00000000475	16	2017-12-06 15:31:35.056328
012	409	27394430655	20171128	00004714	FB	000000060200	00000165244	00000000200	00000003305	16	2017-12-06 15:31:35.056328
012	410	27313340339	20171128	00004715	FB	000000060201	00000105277	00000000200	00000002106	16	2017-12-06 15:31:35.056328
012	411	27226803233	20171128	00004716	FB	000000060202	00000109603	00000000200	00000002192	16	2017-12-06 15:31:35.056328
012	412	27044920102	20171128	00004717	FB	000000060203	00000042767	00000000200	00000000855	16	2017-12-06 15:31:35.056328
012	413	20082698508	20171128	00004718	FB	000000060204	00000070346	00000000200	00000001407	16	2017-12-06 15:31:35.056328
012	414	23331976199	20171128	00004719	FB	000000060208	00000138121	00000000200	00000002762	16	2017-12-06 15:31:35.056328
012	415	23331976199	20171128	00004720	FB	000000060209	00000178626	00000000200	00000003573	16	2017-12-06 15:31:35.056328
012	416	23331976199	20171128	00004721	FB	000000060210	00000046036	00000000200	00000000921	16	2017-12-06 15:31:35.056328
012	417	23331976199	20171128	00004722	FB	000000060211	00000132581	00000000200	00000002652	16	2017-12-06 15:31:35.056328
012	418	27325453465	20171128	00004723	FB	000000060212	00000039571	00000000200	00000000791	16	2017-12-06 15:31:35.056328
012	419	27325453465	20171128	00004724	FB	000000060213	00000021569	00000000200	00000000431	16	2017-12-06 15:31:35.056328
012	420	27248176607	20171128	00004725	FB	000000060214	00000077270	00000000200	00000001545	16	2017-12-06 15:31:35.056328
012	421	27248176607	20171128	00004726	FB	000000060215	00000157364	00000000200	00000003147	16	2017-12-06 15:31:35.056328
012	422	27248176607	20171128	00004727	FB	000000060216	00000130736	00000000200	00000002615	16	2017-12-06 15:31:35.056328
012	423	20388075806	20171128	00004728	FB	000000060217	00000142392	00000000200	00000002848	16	2017-12-06 15:31:35.056328
012	424	20388075806	20171128	00004729	FB	000000060218	00000044190	00000000200	00000000884	16	2017-12-06 15:31:35.056328
012	425	27952625514	20171128	00004730	OT	000000002195	-0000019360	00000000200	-0000000387	16	2017-12-06 15:31:35.056328
012	426	27222713140	20171129	00004731	FA	000000035939	00000058492	00000000200	00000001170	16	2017-12-06 15:31:35.056328
012	427	30670362317	20171129	00004732	FA	000000035940	00000110111	00000000200	00000002202	16	2017-12-06 15:31:35.056328
012	428	30670364603	20171129	00004733	FA	000000035941	00000226799	00000000200	00000004536	16	2017-12-06 15:31:35.056328
012	429	27952625514	20171129	00004734	FA	000000035977	00000601407	00000000200	00000012028	16	2017-12-06 15:31:35.056328
012	430	27952625514	20171129	00004735	FA	000000035978	00000451033	00000000200	00000009021	16	2017-12-06 15:31:35.056328
012	431	27952625514	20171129	00004736	FA	000000035979	00000211157	00000000200	00000004223	16	2017-12-06 15:31:35.056328
012	432	27173955451	20171129	00004737	FA	000000035980	00000190117	00000000200	00000003802	16	2017-12-06 15:31:35.056328
012	433	27222713140	20171129	00004738	FA	000000035981	00000015662	00000000200	00000000313	16	2017-12-06 15:31:35.056328
012	434	27100281819	20171129	00004739	FA	000000035982	00000053281	00000000200	00000001066	16	2017-12-06 15:31:35.056328
012	435	23163173824	20171129	00004740	FA	000000035983	00000037612	00000000200	00000000752	16	2017-12-06 15:31:35.056328
012	436	27125944707	20171129	00004741	FA	000000035984	00000023689	00000000200	00000000474	16	2017-12-06 15:31:35.056328
012	437	27125944707	20171129	00004742	FA	000000035985	00000046773	00000000200	00000000935	16	2017-12-06 15:31:35.056328
012	438	20274035170	20171129	00004743	FA	000000035986	00000141126	00000000200	00000002823	16	2017-12-06 15:31:35.056328
012	439	20261719887	20171129	00004744	FA	000000035987	00000066553	00000000200	00000001331	16	2017-12-06 15:31:35.056328
012	440	20261719887	20171129	00004745	FA	000000035988	00000108408	00000000200	00000002168	16	2017-12-06 15:31:35.056328
012	441	20261719887	20171129	00004746	FA	000000035989	00000105737	00000000200	00000002115	16	2017-12-06 15:31:35.056328
012	442	20261719887	20171129	00004747	FA	000000035990	00000217305	00000000200	00000004346	16	2017-12-06 15:31:35.056328
012	443	20261719887	20171129	00004748	FA	000000035991	00000235644	00000000200	00000004713	16	2017-12-06 15:31:35.056328
012	444	27056603560	20171129	00004749	FA	000000035992	00000097022	00000000200	00000001940	16	2017-12-06 15:31:35.056328
012	445	23241217264	20171129	00004750	FA	000000035993	00000120180	00000000200	00000002404	16	2017-12-06 15:31:35.056328
012	446	20054139285	20171129	00004751	FA	000000035994	00000065074	00000000200	00000001301	16	2017-12-06 15:31:35.056328
012	447	30670364603	20171129	00004752	FA	000000035995	00000228840	00000000200	00000004577	16	2017-12-06 15:31:35.056328
012	448	20305781720	20171129	00004753	FA	000000035996	00000172536	00000000200	00000003451	16	2017-12-06 15:31:35.056328
012	449	20305781720	20171129	00004754	FA	000000035997	00000015243	00000000200	00000000305	16	2017-12-06 15:31:35.056328
012	450	23289795464	20171129	00004755	FA	000000035998	00000245186	00000000200	00000004904	16	2017-12-06 15:31:35.056328
012	451	23289795464	20171129	00004756	FA	000000035999	00000111119	00000000200	00000002222	16	2017-12-06 15:31:35.056328
012	452	23289795464	20171129	00004757	FA	000000036000	00000014852	00000000200	00000000297	16	2017-12-06 15:31:35.056328
012	453	23289795464	20171129	00004758	FA	000000036001	00000299620	00000000200	00000005992	16	2017-12-06 15:31:35.056328
012	454	23289795464	20171129	00004759	FA	000000036002	00000137488	00000000200	00000002750	16	2017-12-06 15:31:35.056328
012	455	23289795464	20171129	00004760	FA	000000036003	00000172096	00000000200	00000003442	16	2017-12-06 15:31:35.056328
012	456	23289795464	20171129	00004761	FA	000000036004	00000064179	00000000200	00000001284	16	2017-12-06 15:31:35.056328
012	457	30670362317	20171129	00004762	FA	000000036005	00000122852	00000000200	00000002457	16	2017-12-06 15:31:35.056328
012	458	27394430655	20171129	00004763	FB	000000060315	00000192331	00000000200	00000003847	16	2017-12-06 15:31:35.056328
012	459	23331976199	20171129	00004764	FB	000000060318	00000194343	00000000200	00000003887	16	2017-12-06 15:31:35.056328
012	460	27248176607	20171129	00004765	FB	000000060319	00000118744	00000000200	00000002375	16	2017-12-06 15:31:35.056328
012	461	23331976199	20171129	00004766	FB	000000060354	00000183892	00000000200	00000003678	16	2017-12-06 15:31:35.056328
012	462	27363206641	20171129	00004767	FB	000000060355	00000120078	00000000200	00000002402	16	2017-12-06 15:31:35.056328
012	463	27313340339	20171129	00004768	FB	000000060356	00000058321	00000000200	00000001166	16	2017-12-06 15:31:35.056328
012	464	27363206641	20171129	00004769	FB	000000060357	00000096614	00000000200	00000001932	16	2017-12-06 15:31:35.056328
012	465	27363206641	20171129	00004770	FB	000000060358	00000151004	00000000200	00000003020	16	2017-12-06 15:31:35.056328
012	466	20313340792	20171129	00004771	FB	000000060360	00000023029	00000000200	00000000461	16	2017-12-06 15:31:35.056328
012	467	27215184760	20171129	00004772	FB	000000060361	00000031086	00000000200	00000000622	16	2017-12-06 15:31:35.056328
012	468	27219273431	20171129	00004773	FB	000000060362	00000042801	00000000200	00000000856	16	2017-12-06 15:31:35.056328
012	469	27121002650	20171129	00004774	FB	000000060363	00000068975	00000000200	00000001380	16	2017-12-06 15:31:35.056328
012	470	27044920102	20171129	00004775	FB	000000060364	00000045330	00000000200	00000000907	16	2017-12-06 15:31:35.056328
012	471	27394430655	20171129	00004776	FB	000000060373	00000080007	00000000200	00000001600	16	2017-12-06 15:31:35.056328
012	472	27056603560	20171129	00004777	OT	000000002200	-0000016471	00000000200	-0000000329	16	2017-12-06 15:31:35.056328
012	473	27121002650	20171129	00004778	OT	000000002534	-0000017091	00000000200	-0000000342	16	2017-12-06 15:31:35.056328
012	474	23331976199	20171129	00004779	OT	000000002535	-0000017091	00000000200	-0000000342	16	2017-12-06 15:31:35.056328
012	475	27044920102	20171129	00004780	OT	000000002536	-0000038018	00000000200	-0000000760	16	2017-12-06 15:31:35.056328
002	2	27219273431	20171103	00004698	FA	001100048235	00000021000	00000000200	00000000420	6	2017-12-07 11:13:01.502569
002	3	27219273431	20171108	00004699	FA	001100048411	00000052955	00000000200	00000001059	6	2017-12-07 11:13:01.502569
002	4	27219273431	20171108	00004700	FA	001100048412	00000050208	00000000200	00000001004	6	2017-12-07 11:13:01.502569
002	5	27219273431	20171110	00004701	FA	001100048595	00000013805	00000000200	00000000276	6	2017-12-07 11:13:01.502569
002	6	27219273431	20171115	00004702	FA	001100048768	00000110808	00000000200	00000002216	6	2017-12-07 11:13:01.502569
002	7	27219273431	20171115	00004703	FA	001100048829	00000044000	00000000200	00000000880	6	2017-12-07 11:13:01.502569
002	8	27219273431	20171122	00004704	FA	001100049134	00000034674	00000000200	00000000693	6	2017-12-07 11:13:01.502569
002	9	27219273431	20171129	00004705	FA	001100049512	00000060793	00000000200	00000001216	6	2017-12-07 11:13:01.502569
002	10	23163173824	20171103	00004706	FA	001100023296	00000136821	00000000200	00000002736	6	2017-12-07 11:13:01.502569
002	11	23163173824	20171108	00004707	FA	001100023441	00000022360	00000000200	00000000447	6	2017-12-07 11:13:01.502569
002	12	23163173824	20171110	00004708	FA	001100023520	00000086120	00000000200	00000001722	6	2017-12-07 11:13:01.502569
002	13	23163173824	20171117	00004709	FA	001100023766	00000065717	00000000200	00000001314	6	2017-12-07 11:13:01.502569
002	14	23163173824	20171122	00004710	FA	001100023922	00000072380	00000000200	00000001448	6	2017-12-07 11:13:01.502569
002	15	23163173824	20171124	00004711	FA	001100024004	00000062940	00000000200	00000001259	6	2017-12-07 11:13:01.502569
002	16	23163173824	20171128	00004712	FA	001100024090	00000053394	00000000200	00000001068	6	2017-12-07 11:13:01.502569
002	17	27163184465	20171101	00004713	FA	001100048127	00000131658	00000000200	00000002633	6	2017-12-07 11:13:01.502569
002	18	27163184465	20171108	00004714	FA	001100048418	00000062497	00000000200	00000001250	6	2017-12-07 11:13:01.502569
002	19	27163184465	20171115	00004715	FA	001100048774	00000043676	00000000200	00000000874	6	2017-12-07 11:13:01.502569
002	20	27163184465	20171115	00004716	FA	001100048776	00000092664	00000000200	00000001853	6	2017-12-07 11:13:01.502569
002	21	27163184465	20171122	00004717	FA	001100049156	00000211450	00000000200	00000004229	6	2017-12-07 11:13:01.502569
002	22	27163184465	20171129	00004718	FA	001100049504	00000051607	00000000200	00000001032	6	2017-12-07 11:13:01.502569
002	23	27041619916	20171108	00004719	FA	001100048410	00000083689	00000000200	00000001674	6	2017-12-07 11:13:01.502569
002	24	27041619916	20171115	00004720	FA	001100048775	00000073841	00000000200	00000001477	6	2017-12-07 11:13:01.502569
002	25	27041619916	20171122	00004721	FA	001100049171	00000338855	00000000200	00000006777	6	2017-12-07 11:13:01.502569
002	26	27041619916	20171129	00004722	FA	001100049519	00000136735	00000000200	00000002735	6	2017-12-07 11:13:01.502569
002	27	27056603560	20171101	00004723	FA	001100023200	00000144637	00000000200	00000002893	6	2017-12-07 11:13:01.502569
002	28	27056603560	20171108	00004724	FA	001100023443	00000229649	00000000200	00000004593	6	2017-12-07 11:13:01.502569
002	29	27056603560	20171110	00004725	FA	001100023534	00000197340	00000000200	00000003947	6	2017-12-07 11:13:01.502569
002	30	27056603560	20171110	00004726	FA	001100023535	00000137000	00000000200	00000002740	6	2017-12-07 11:13:01.502569
002	31	27056603560	20171110	00004727	FA	001100023536	00000146160	00000000200	00000002923	6	2017-12-07 11:13:01.502569
002	32	27056603560	20171110	00004728	FA	001100023537	00000191080	00000000200	00000003822	6	2017-12-07 11:13:01.502569
002	33	27056603560	20171110	00004729	FA	001100023538	00000146470	00000000200	00000002929	6	2017-12-07 11:13:01.502569
002	34	27056603560	20171110	00004730	FA	001100023542	00000164580	00000000200	00000003292	6	2017-12-07 11:13:01.502569
002	35	27056603560	20171110	00004731	FA	001100023544	00000014960	00000000200	00000000299	6	2017-12-07 11:13:01.502569
002	36	27056603560	20171110	00004732	FA	001100001249	-0000197340	00000000200	-0000003947	6	2017-12-07 11:13:01.502569
002	37	27056603560	20171114	00004733	FA	001100023622	00000035739	00000000200	00000000715	6	2017-12-07 11:13:01.502569
002	38	27056603560	20171115	00004734	FA	001100023645	00000034744	00000000200	00000000695	6	2017-12-07 11:13:01.502569
002	39	27056603560	20171122	00004735	FA	001100023903	00000021945	00000000200	00000000439	6	2017-12-07 11:13:01.502569
002	40	27056603560	20171122	00004736	FA	001100023921	00000296199	00000000200	00000005924	6	2017-12-07 11:13:01.502569
002	41	27056603560	20171122	00004737	FA	001100023926	00000021945	00000000200	00000000439	6	2017-12-07 11:13:01.502569
002	42	27056603560	20171124	00004738	FA	001100024001	00000081934	00000000200	00000001639	6	2017-12-07 11:13:01.502569
002	43	27056603560	20171129	00004739	FA	001100024110	00000168371	00000000200	00000003367	6	2017-12-07 11:13:01.502569
002	44	27064350809	20171101	00004740	FA	001100023202	00000069024	00000000200	00000001380	6	2017-12-07 11:13:01.502569
002	45	27064350809	20171107	00004741	FA	001100023425	00000110630	00000000200	00000002213	6	2017-12-07 11:13:01.502569
002	46	27064350809	20171107	00004742	FA	001100023427	00000018560	00000000200	00000000371	6	2017-12-07 11:13:01.502569
002	47	27064350809	20171114	00004743	FA	001100023634	00000092950	00000000200	00000001859	6	2017-12-07 11:13:01.502569
002	48	27064350809	20171122	00004744	FA	001100023896	00000085849	00000000200	00000001717	6	2017-12-07 11:13:01.502569
002	49	27064350809	20171122	00004745	FA	001100023899	00000018400	00000000200	00000000368	6	2017-12-07 11:13:01.502569
002	50	27064350809	20171124	00004746	FA	001100024016	00000043470	00000000200	00000000869	6	2017-12-07 11:13:01.502569
002	51	27064350809	20171129	00004747	FA	001100024109	00000084825	00000000200	00000001697	6	2017-12-07 11:13:01.502569
002	52	27188206463	20171101	00004748	FA	001100023218	00000040898	00000000200	00000000818	6	2017-12-07 11:13:01.502569
002	53	27188206463	20171103	00004749	FA	001100023301	00000035360	00000000200	00000000707	6	2017-12-07 11:13:01.502569
002	54	27188206463	20171108	00004750	FA	001100023446	00000438923	00000000200	00000008778	6	2017-12-07 11:13:01.502569
002	55	27188206463	20171108	00004751	FA	001100023459	00000438963	00000000200	00000008779	6	2017-12-07 11:13:01.502569
002	56	27188206463	20171108	00004752	FA	001100001245	-0000438923	00000000200	-0000008778	6	2017-12-07 11:13:01.502569
002	57	27188206463	20171110	00004753	FA	001100023525	00000021443	00000000200	00000000429	6	2017-12-07 11:13:01.502569
002	58	27188206463	20171122	00004754	FA	001100023878	00000325540	00000000200	00000006511	6	2017-12-07 11:13:01.502569
002	59	27188206463	20171122	00004755	FA	001100023879	00000160000	00000000200	00000003200	6	2017-12-07 11:13:01.502569
002	60	20173955449	20171103	00004756	FA	001100048227	00000073551	00000000200	00000001471	6	2017-12-07 11:13:01.502569
002	61	20173955449	20171103	00004757	FA	001100048237	00000043645	00000000200	00000000873	6	2017-12-07 11:13:01.502569
002	62	20173955449	20171114	00004758	FA	001100048759	00000032331	00000000200	00000000647	6	2017-12-07 11:13:01.502569
002	63	20173955449	20171117	00004759	FA	001100048972	00000075755	00000000200	00000001515	6	2017-12-07 11:13:01.502569
002	64	20173955449	20171124	00004760	FA	001100049329	00000044570	00000000200	00000000891	6	2017-12-07 11:13:01.502569
002	65	20173955449	20171129	00004761	FA	001100049531	00000088158	00000000200	00000001763	6	2017-12-07 11:13:01.502569
002	66	20118857020	20171108	00004762	FA	001100048428	00000142699	00000000200	00000002854	6	2017-12-07 11:13:01.502569
002	67	20118857020	20171110	00004763	FA	001100048600	00000162074	00000000200	00000003241	6	2017-12-07 11:13:01.502569
002	68	20118857020	20171115	00004764	FA	001100048795	00000088224	00000000200	00000001764	6	2017-12-07 11:13:01.502569
002	69	20118857020	20171117	00004765	FA	001100048958	00000116495	00000000200	00000002330	6	2017-12-07 11:13:01.502569
002	70	20118857020	20171122	00004766	FA	001100049152	00000024690	00000000200	00000000494	6	2017-12-07 11:13:01.502569
002	71	20118857020	20171122	00004767	FA	001100049178	00000035069	00000000200	00000000701	6	2017-12-07 11:13:01.502569
002	72	20118857020	20171124	00004768	FA	001100049315	00000047098	00000000200	00000000942	6	2017-12-07 11:13:01.502569
002	73	20118857020	20171124	00004769	FA	001100049342	00000046690	00000000200	00000000934	6	2017-12-07 11:13:01.502569
002	74	20118857020	20171124	00004770	FA	001100002414	-0000047098	00000000200	-0000000942	6	2017-12-07 11:13:01.502569
002	75	20118857020	20171129	00004771	FA	001100049510	00000113047	00000000200	00000002261	6	2017-12-07 11:13:01.502569
002	76	20118857020	20171129	00004772	FA	001100049526	00000007300	00000000200	00000000146	6	2017-12-07 11:13:01.502569
002	77	20118857020	20171129	00004773	FA	001100049537	00000143899	00000000200	00000002878	6	2017-12-07 11:13:01.502569
002	78	30670364603	20171107	00004774	FA	001100023430	00000177540	00000000200	00000003551	6	2017-12-07 11:13:01.502569
002	79	30670364603	20171115	00004775	FA	001100023652	00000357656	00000000200	00000007153	6	2017-12-07 11:13:01.502569
002	80	30670364603	20171122	00004776	FA	001100023882	00000242344	00000000200	00000004847	6	2017-12-07 11:13:01.502569
002	81	30670364603	20171122	00004777	FA	001100023883	00000037990	00000000200	00000000760	6	2017-12-07 11:13:01.502569
002	82	30670364603	20171128	00004778	FA	001100024088	00000132162	00000000200	00000002643	6	2017-12-07 11:13:01.502569
002	83	30670364603	20171128	00004779	FA	001100024102	00000006210	00000000200	00000000124	6	2017-12-07 11:13:01.502569
002	84	27163184732	20171108	00004780	FA	001100023440	00000053988	00000000200	00000001080	6	2017-12-07 11:13:01.502569
002	85	27163184732	20171114	00004781	FA	001100023635	00000103401	00000000200	00000002068	6	2017-12-07 11:13:01.502569
002	86	27163184732	20171122	00004782	FA	001100023906	00000108366	00000000200	00000002167	6	2017-12-07 11:13:01.502569
002	87	27163184732	20171124	00004783	FA	001100024009	00000233850	00000000200	00000004677	6	2017-12-07 11:13:01.502569
002	88	27316072483	20171103	00004784	FA	001100023308	00000106288	00000000200	00000002126	6	2017-12-07 11:13:01.502569
002	89	27316072483	20171110	00004785	FA	001100023527	00000092426	00000000200	00000001849	6	2017-12-07 11:13:01.502569
002	90	27316072483	20171117	00004786	FA	001100023776	00000104792	00000000200	00000002096	6	2017-12-07 11:13:01.502569
002	91	27316072483	20171117	00004787	FA	001100023781	00000006560	00000000200	00000000131	6	2017-12-07 11:13:01.502569
002	92	27125944707	20171103	00004788	FA	001100023302	00000051908	00000000200	00000001038	6	2017-12-07 11:13:01.502569
002	93	27125944707	20171107	00004789	FA	001100023424	00000208760	00000000200	00000004175	6	2017-12-07 11:13:01.502569
002	94	27125944707	20171108	00004790	FA	001100023461	00000208800	00000000200	00000004176	6	2017-12-07 11:13:01.502569
002	95	27125944707	20171108	00004791	FA	001100001243	-0000208760	00000000200	-0000004175	6	2017-12-07 11:13:01.502569
002	96	27125944707	20171110	00004792	FA	001100023530	00000018441	00000000200	00000000369	6	2017-12-07 11:13:01.502569
002	97	27125944707	20171115	00004793	FA	001100023641	00000070338	00000000200	00000001407	6	2017-12-07 11:13:01.502569
002	98	27125944707	20171117	00004794	FA	001100023783	00000074290	00000000200	00000001486	6	2017-12-07 11:13:01.502569
002	99	27125944707	20171117	00004795	FA	001100023789	00000095210	00000000200	00000001904	6	2017-12-07 11:13:01.502569
002	100	27125944707	20171122	00004796	FA	001100023900	00000212620	00000000200	00000004252	6	2017-12-07 11:13:01.502569
002	101	27125944707	20171124	00004797	FA	001100024008	00000072789	00000000200	00000001456	6	2017-12-07 11:13:01.502569
002	102	27125944707	20171128	00004798	FA	001100024101	00000049447	00000000200	00000000989	6	2017-12-07 11:13:01.502569
002	103	27925411626	20171128	00004799	FA	001100049495	00000061681	00000000200	00000001234	6	2017-12-07 11:13:01.502569
002	104	20271066547	20171101	00004800	FA	001100048113	00000063614	00000000200	00000001272	6	2017-12-07 11:13:01.502569
002	105	20271066547	20171103	00004801	FA	001100048243	00000084010	00000000200	00000001680	6	2017-12-07 11:13:01.502569
002	106	20271066547	20171107	00004802	FA	001100048365	00000159032	00000000200	00000003181	6	2017-12-07 11:13:01.502569
002	107	20271066547	20171110	00004803	FA	001100048601	00000113969	00000000200	00000002279	6	2017-12-07 11:13:01.502569
002	108	20271066547	20171114	00004804	FA	001100048712	00000143840	00000000200	00000002877	6	2017-12-07 11:13:01.502569
002	109	20271066547	20171115	00004805	FA	001100048810	00000154610	00000000200	00000003092	6	2017-12-07 11:13:01.502569
002	110	20271066547	20171117	00004806	FA	001100048975	00000057008	00000000200	00000001140	6	2017-12-07 11:13:01.502569
002	111	20271066547	20171122	00004807	FA	001100049170	00000236655	00000000200	00000004733	6	2017-12-07 11:13:01.502569
002	112	20271066547	20171124	00004808	FA	001100049339	00000121211	00000000200	00000002424	6	2017-12-07 11:13:01.502569
002	113	20271066547	20171129	00004809	FA	001100049534	00000258564	00000000200	00000005171	6	2017-12-07 11:13:01.502569
002	114	20082698508	20171101	00004810	FA	001100048098	00000238127	00000000200	00000004763	6	2017-12-07 11:13:01.502569
002	115	20082698508	20171101	00004811	FA	001100048099	00000232461	00000000200	00000004649	6	2017-12-07 11:13:01.502569
002	116	20082698508	20171107	00004812	FA	001100048405	00000177657	00000000200	00000003553	6	2017-12-07 11:13:01.502569
002	117	20082698508	20171107	00004813	FA	001100048406	00000034619	00000000200	00000000692	6	2017-12-07 11:13:01.502569
002	118	20082698508	20171110	00004814	FA	001100048602	00000030746	00000000200	00000000615	6	2017-12-07 11:13:01.502569
002	119	20082698508	20171115	00004815	FA	001100048799	00000150334	00000000200	00000003007	6	2017-12-07 11:13:01.502569
002	120	20082698508	20171115	00004816	FA	001100048800	00000052878	00000000200	00000001058	6	2017-12-07 11:13:01.502569
002	121	20082698508	20171116	00004817	FA	001100002395	-0000049020	00000000200	-0000000980	6	2017-12-07 11:13:01.502569
002	122	20082698508	20171117	00004818	FA	001100048976	00000098810	00000000200	00000001976	6	2017-12-07 11:13:01.502569
002	123	20082698508	20171122	00004819	FA	001100049172	00000218810	00000000200	00000004376	6	2017-12-07 11:13:01.502569
002	124	20082698508	20171122	00004820	FA	001100049173	00000155462	00000000200	00000003109	6	2017-12-07 11:13:01.502569
002	125	20082698508	20171122	00004821	FA	001100049174	00000021947	00000000200	00000000439	6	2017-12-07 11:13:01.502569
002	126	20082698508	20171129	00004822	FA	001100049513	00000185366	00000000200	00000003707	6	2017-12-07 11:13:01.502569
002	127	20082698508	20171129	00004823	FA	001100049514	00000119491	00000000200	00000002390	6	2017-12-07 11:13:01.502569
002	128	27351546862	20171101	00004824	FA	001100048091	00000083174	00000000200	00000001663	6	2017-12-07 11:13:01.502569
002	129	27351546862	20171103	00004825	FA	001100048238	00000112330	00000000200	00000002247	6	2017-12-07 11:13:01.502569
002	130	27351546862	20171108	00004826	FA	001100048444	00000018188	00000000200	00000000364	6	2017-12-07 11:13:01.502569
002	131	27351546862	20171115	00004827	FA	001100048764	00000049541	00000000200	00000000991	6	2017-12-07 11:13:01.502569
002	132	27351546862	20171117	00004828	FA	001100048959	00000076493	00000000200	00000001530	6	2017-12-07 11:13:01.502569
002	133	27351546862	20171122	00004829	FA	001100049150	00000035436	00000000200	00000000709	6	2017-12-07 11:13:01.502569
002	134	20290121753	20171101	00004830	FA	001100048094	00000061649	00000000200	00000001233	6	2017-12-07 11:13:01.502569
002	135	20290121753	20171101	00004831	FA	001100048125	00000022364	00000000200	00000000447	6	2017-12-07 11:13:01.502569
002	136	27121002650	20171101	00004832	FA	001100048095	00000246567	00000000200	00000004931	6	2017-12-07 11:13:01.502569
002	137	27121002650	20171101	00004833	FA	001100048096	00000118908	00000000200	00000002378	6	2017-12-07 11:13:01.502569
002	138	27121002650	20171101	00004834	FA	001100048097	00000070005	00000000200	00000001400	6	2017-12-07 11:13:01.502569
002	139	27121002650	20171101	00004835	FA	001100048126	00000020077	00000000200	00000000402	6	2017-12-07 11:13:01.502569
002	140	27121002650	20171108	00004836	FA	001100048470	00000234024	00000000200	00000004680	6	2017-12-07 11:13:01.502569
002	141	27121002650	20171108	00004837	FA	001100048471	00000122068	00000000200	00000002441	6	2017-12-07 11:13:01.502569
002	142	27121002650	20171110	00004838	FA	001100048607	00000285070	00000000200	00000005701	6	2017-12-07 11:13:01.502569
002	143	27121002650	20171115	00004839	FA	001100048787	00000106966	00000000200	00000002139	6	2017-12-07 11:13:01.502569
002	144	27121002650	20171117	00004840	FA	001100048968	00000157825	00000000200	00000003157	6	2017-12-07 11:13:01.502569
002	145	27121002650	20171117	00004841	FA	001100048969	00000076545	00000000200	00000001531	6	2017-12-07 11:13:01.502569
002	146	27121002650	20171117	00004842	FA	001100048973	00000254472	00000000200	00000005089	6	2017-12-07 11:13:01.502569
002	147	27121002650	20171122	00004843	FA	001100049138	00000171278	00000000200	00000003426	6	2017-12-07 11:13:01.502569
002	148	27121002650	20171122	00004844	FA	001100049139	00000230093	00000000200	00000004602	6	2017-12-07 11:13:01.502569
002	149	27121002650	20171122	00004845	FA	001100049168	00000035069	00000000200	00000000701	6	2017-12-07 11:13:01.502569
002	150	27121002650	20171122	00004846	FA	001100002401	-0000015734	00000000200	-0000000315	6	2017-12-07 11:13:01.502569
002	151	27121002650	20171124	00004847	FA	001100049333	00000186434	00000000200	00000003729	6	2017-12-07 11:13:01.502569
002	152	27121002650	20171129	00004848	FA	001100049501	00000224267	00000000200	00000004485	6	2017-12-07 11:13:01.502569
002	153	27121002650	20171129	00004849	FA	001100049502	00000164342	00000000200	00000003287	6	2017-12-07 11:13:01.502569
002	154	20166734178	20171101	00004850	FA	001100023220	00000124100	00000000200	00000002482	6	2017-12-07 11:13:01.502569
002	155	20166734178	20171103	00004851	FA	001100023309	00000062365	00000000200	00000001247	6	2017-12-07 11:13:01.502569
002	156	20166734178	20171110	00004852	FA	001100023528	00000081624	00000000200	00000001632	6	2017-12-07 11:13:01.502569
002	157	20166734178	20171115	00004853	FA	001100023664	00000062430	00000000200	00000001249	6	2017-12-07 11:13:01.502569
002	158	20166734178	20171117	00004854	FA	001100023770	00000100150	00000000200	00000002003	6	2017-12-07 11:13:01.502569
002	159	20166734178	20171124	00004855	FA	001100024012	00000118431	00000000200	00000002369	6	2017-12-07 11:13:01.502569
002	160	20166734178	20171124	00004856	FA	001100024019	00000018900	00000000200	00000000378	6	2017-12-07 11:13:01.502569
002	161	20166734178	20171124	00004857	FA	001100001279	-0000019338	00000000200	-0000000387	6	2017-12-07 11:13:01.502569
002	162	20166734178	20171129	00004858	FA	001100024134	00000123530	00000000200	00000002471	6	2017-12-07 11:13:01.502569
002	163	27222713140	20171101	00004859	FA	001100023207	00000087600	00000000200	00000001752	6	2017-12-07 11:13:01.502569
002	164	27222713140	20171107	00004860	FA	001100023426	00000050211	00000000200	00000001004	6	2017-12-07 11:13:01.502569
002	165	27222713140	20171108	00004861	FA	001100023432	00000070610	00000000200	00000001412	6	2017-12-07 11:13:01.502569
002	166	27222713140	20171114	00004862	FA	001100023636	00000147909	00000000200	00000002958	6	2017-12-07 11:13:01.502569
002	167	27222713140	20171115	00004863	FA	001100023644	00000110352	00000000200	00000002207	6	2017-12-07 11:13:01.502569
002	168	27222713140	20171115	00004864	FA	001100023657	00000042283	00000000200	00000000846	6	2017-12-07 11:13:01.502569
002	169	27222713140	20171117	00004865	FA	001100023768	00000087600	00000000200	00000001752	6	2017-12-07 11:13:01.502569
002	170	27222713140	20171122	00004866	FA	001100023897	00000124531	00000000200	00000002491	6	2017-12-07 11:13:01.502569
002	171	27222713140	20171122	00004867	FA	001100023898	00000022429	00000000200	00000000449	6	2017-12-07 11:13:01.502569
002	172	27222713140	20171122	00004868	FA	001100023920	00000159856	00000000200	00000003197	6	2017-12-07 11:13:01.502569
002	173	27222713140	20171129	00004869	FA	001100024105	00000149625	00000000200	00000002993	6	2017-12-07 11:13:01.502569
002	174	27222713140	20171129	00004870	FA	001100024106	00000050810	00000000200	00000001016	6	2017-12-07 11:13:01.502569
002	175	27222713140	20171129	00004871	FA	001100024115	00000171281	00000000200	00000003426	6	2017-12-07 11:13:01.502569
002	176	27222713140	20171129	00004872	FA	001100024129	00000081082	00000000200	00000001622	6	2017-12-07 11:13:01.502569
002	177	27222713140	20171129	00004873	FA	001100024137	00000013110	00000000200	00000000262	6	2017-12-07 11:13:01.502569
002	178	27131108813	20171103	00004874	FA	001100048233	00000039234	00000000200	00000000785	6	2017-12-07 11:13:01.502569
002	179	27131108813	20171115	00004875	FA	001100048766	00000018502	00000000200	00000000370	6	2017-12-07 11:13:01.502569
002	180	20160060183	20171101	00004876	FA	001100048117	00000014360	00000000200	00000000287	6	2017-12-07 11:13:01.502569
002	181	20160060183	20171108	00004877	FA	001100048417	00000077119	00000000200	00000001542	6	2017-12-07 11:13:01.502569
002	182	20160060183	20171108	00004878	FA	001100048446	00000006570	00000000200	00000000131	6	2017-12-07 11:13:01.502569
003	2	20054139285	20171104	00001692	OT	001200001692	-0000000754	00000000200	-0000000015	7	2017-12-05 18:44:23.476865
003	3	27942269566	20171106	00001633	OT	001200001633	-0000002731	00000000200	-0000000055	7	2017-12-05 18:44:23.476865
003	4	20166734178	20171106	00001704	OT	001200001704	-0000068060	00000000200	-0000001361	7	2017-12-05 18:44:23.476865
003	5	27394430655	20171106	00001634	OT	001200001634	-0000014023	00000000200	-0000000280	7	2017-12-05 18:44:23.476865
003	6	27346653502	20171107	00001636	OT	001200001636	-0000098318	00000000200	-0000001966	7	2017-12-05 18:44:23.476865
003	7	27056603560	20171107	00001706	OT	001200001706	-0000015461	00000000200	-0000000309	7	2017-12-05 18:44:23.476865
003	8	27219273431	20171110	00001664	OT	001200001664	-0000079904	00000000200	-0000001598	7	2017-12-05 18:44:23.476865
003	9	27367571123	20171110	00001722	OT	001200001722	-0000026484	00000000200	-0000000530	7	2017-12-05 18:44:23.476865
003	10	27952625514	20171111	00000758	OT	001100000758	-0000038362	00000000200	-0000000767	7	2017-12-05 18:44:23.476865
003	11	27125944707	20171113	00000774	OT	001100000774	-0000018223	00000000200	-0000000364	7	2017-12-05 18:44:23.476865
003	12	27952625514	20171114	00000775	OT	001100000775	-0000084384	00000000200	-0000001688	7	2017-12-05 18:44:23.476865
003	13	27259753274	20171115	00000125	OT	001900000125	-0000138123	00000000200	-0000002762	7	2017-12-05 18:44:23.476865
003	14	20266077719	20171116	00000071	OT	001900000071	-0000047954	00000000200	-0000000959	7	2017-12-05 18:44:23.476865
003	15	23163173824	20171116	00000791	OT	001100000791	-0000085890	00000000200	-0000001718	7	2017-12-05 18:44:23.476865
003	16	27056603560	20171116	00000792	OT	001100000792	-0000048477	00000000200	-0000000970	7	2017-12-05 18:44:23.476865
003	17	27952625514	20171117	00000795	OT	001100000795	-0000011640	00000000200	-0000000233	7	2017-12-05 18:44:23.476865
003	18	27222713140	20171117	00000796	OT	001100000796	-0000085172	00000000200	-0000001703	7	2017-12-05 18:44:23.476865
003	19	27952625514	20171117	00000797	OT	001100000797	-0000015835	00000000200	-0000000317	7	2017-12-05 18:44:23.476865
003	20	27219273431	20171117	00000566	OT	001100000566	-0000009908	00000000200	-0000000198	7	2017-12-05 18:44:23.476865
003	21	27952625514	20171117	00000798	OT	001100000798	-0000174468	00000000200	-0000003489	7	2017-12-05 18:44:23.476865
003	22	20073261806	20171118	00000817	OT	001100000817	-0000028749	00000000200	-0000000575	7	2017-12-05 18:44:23.476865
003	23	27952625514	20171118	00000818	OT	001100000818	-0000024920	00000000200	-0000000498	7	2017-12-05 18:44:23.476865
003	24	27952625514	20171118	00000819	OT	001100000819	-0000076422	00000000200	-0000001528	7	2017-12-05 18:44:23.476865
003	25	20182383016	20171118	00000568	OT	001100000568	-0000036822	00000000200	-0000000736	7	2017-12-05 18:44:23.476865
003	26	27367571123	20171121	00000824	OT	001100000824	-0000026293	00000000200	-0000000526	7	2017-12-05 18:44:23.476865
003	27	27226803233	20171121	00000574	OT	001100000574	-0000036214	00000000200	-0000000724	7	2017-12-05 18:44:23.476865
003	28	27316072483	20171121	00000825	OT	001100000825	-0000213079	00000000200	-0000004262	7	2017-12-05 18:44:23.476865
003	29	23236216764	20171121	00000575	OT	001100000575	-0000061940	00000000200	-0000001239	7	2017-12-05 18:44:23.476865
003	30	30670364778	20171123	00000587	OT	001100000587	-0000603807	00000000200	-0000012076	7	2017-12-05 18:44:23.476865
003	31	30670364778	20171123	00000588	OT	001100000588	-0000118631	00000000200	-0000002373	7	2017-12-05 18:44:23.476865
003	32	30670364778	20171123	00000589	OT	001100000589	-0000132021	00000000200	-0000002640	7	2017-12-05 18:44:23.476865
003	33	27952625514	20171125	00000864	OT	001100000864	-0000049341	00000000200	-0000000987	7	2017-12-05 18:44:23.476865
003	34	20261719887	20171127	00000871	OT	001100000871	-0000003967	00000000200	-0000000079	7	2017-12-05 18:44:23.476865
003	35	20261719887	20171127	00000872	OT	001100000872	-0000019153	00000000200	-0000000383	7	2017-12-05 18:44:23.476865
003	36	27188206463	20171127	00000873	OT	001100000873	-0000008915	00000000200	-0000000178	7	2017-12-05 18:44:23.476865
003	37	27952625514	20171128	00000885	OT	001100000885	-0000065321	00000000200	-0000001306	7	2017-12-05 18:44:23.476865
003	38	27952625514	20171129	00000893	OT	001100000893	-0000070992	00000000200	-0000001420	7	2017-12-05 18:44:23.476865
003	39	27219273431	20171129	00000621	OT	001100000621	-0000649609	00000000200	-0000012992	7	2017-12-05 18:44:23.476865
003	40	27952625514	20171130	00000900	OT	001100000900	-0000625405	00000000200	-0000012508	7	2017-12-05 18:44:23.476865
003	41	27952625514	20171130	00000902	OT	001100000902	-0000578361	00000000200	-0000011567	7	2017-12-05 18:44:23.476865
003	42	20213541421	20171130	00000905	OT	001100000905	-0000026251	00000000200	-0000000525	7	2017-12-05 18:44:23.476865
003	43	30714121606	20171101	00009503	FA	001300009503	00000016224	00000000200	00000000324	7	2017-12-05 18:44:23.476865
003	44	27325453465	20171101	00011145	FA	001300011145	00000071701	00000000200	00000001434	7	2017-12-05 18:44:23.476865
003	45	20225687073	20171101	00011156	FA	001300011156	00000056438	00000000200	00000001129	7	2017-12-05 18:44:23.476865
003	46	20261719887	20171101	00007529	FA	001200007529	00000041058	00000000200	00000000821	7	2017-12-05 18:44:23.476865
003	47	27367571123	20171101	00007530	FA	001200007530	00000031622	00000000200	00000000632	7	2017-12-05 18:44:23.476865
003	48	20274035170	20171101	00007531	FA	001200007531	00000025355	00000000200	00000000507	7	2017-12-05 18:44:23.476865
003	49	30670364603	20171101	00007534	FA	001200007534	00000149183	00000000200	00000002984	7	2017-12-05 18:44:23.476865
003	50	27952625514	20171101	00007533	FA	001200007533	00000032304	00000000200	00000000646	7	2017-12-05 18:44:23.476865
003	51	20274035170	20171101	00007527	FA	001200007527	00000033706	00000000200	00000000674	7	2017-12-05 18:44:23.476865
003	52	23241216349	20171102	00007590	FA	001200007590	00000124837	00000000200	00000002497	7	2017-12-05 18:44:23.476865
003	53	20261719887	20171102	00007591	FA	001200007591	00000039272	00000000200	00000000785	7	2017-12-05 18:44:23.476865
003	54	20148499161	20171102	00014630	FA	001200014630	00000037378	00000000200	00000000748	7	2017-12-05 18:44:23.476865
003	55	30647748739	20171102	00014611	FA	001200014611	00000093135	00000000200	00000001863	7	2017-12-05 18:44:23.476865
003	56	27952625514	20171102	00007592	FA	001200007592	00000033071	00000000200	00000000661	7	2017-12-05 18:44:23.476865
003	57	27325453465	20171103	00011248	FA	001300011248	00000039167	00000000200	00000000783	7	2017-12-05 18:44:23.476865
003	58	30707024085	20171103	00009547	FA	001300009547	00000151509	00000000200	00000003030	7	2017-12-05 18:44:23.476865
003	59	30604760018	20171103	00007606	FA	001200007606	00000245393	00000000200	00000004908	7	2017-12-05 18:44:23.476865
003	60	20266077719	20171103	00009545	FA	001300009545	00000024722	00000000200	00000000494	7	2017-12-05 18:44:23.476865
003	61	30604760018	20171103	00007604	FA	001200007604	00000545737	00000000200	00000010915	7	2017-12-05 18:44:23.476865
003	62	27274033784	20171103	00011316	FA	001300011316	00000036462	00000000200	00000000729	7	2017-12-05 18:44:23.476865
003	63	20261719887	20171103	00007651	FA	001200007651	00000110143	00000000200	00000002203	7	2017-12-05 18:44:23.476865
003	64	27222713140	20171103	00007652	FA	001200007652	00000011727	00000000200	00000000235	7	2017-12-05 18:44:23.476865
003	65	27163184732	20171103	00007653	FA	001200007653	00000055431	00000000200	00000001109	7	2017-12-05 18:44:23.476865
003	66	20163184592	20171103	00007654	FA	001200007654	00000028392	00000000200	00000000568	7	2017-12-05 18:44:23.476865
003	67	30670364603	20171103	00007655	FA	001200007655	00000025528	00000000200	00000000511	7	2017-12-05 18:44:23.476865
003	68	27952625514	20171103	00007656	FA	001200007656	00000204305	00000000200	00000004086	7	2017-12-05 18:44:23.476865
003	69	23163173824	20171103	00007657	FA	001200007657	00000022028	00000000200	00000000441	7	2017-12-05 18:44:23.476865
003	70	27346653502	20171103	00014662	FA	001200014662	00000052489	00000000200	00000001050	7	2017-12-05 18:44:23.476865
003	71	27346653502	20171103	00014663	FA	001200014663	00000256728	00000000200	00000005135	7	2017-12-05 18:44:23.476865
003	72	27219273431	20171103	00014664	FA	001200014664	00000104068	00000000200	00000002081	7	2017-12-05 18:44:23.476865
003	73	20113547198	20171103	00014665	FA	001200014665	00000091410	00000000200	00000001828	7	2017-12-05 18:44:23.476865
003	74	20118857020	20171103	00014666	FA	001200014666	00000056784	00000000200	00000001136	7	2017-12-05 18:44:23.476865
003	75	20166734178	20171103	00007715	FA	001200007715	00000294751	00000000200	00000005895	7	2017-12-05 18:44:23.476865
003	76	27222713140	20171103	00007716	FA	001200007716	00000032704	00000000200	00000000654	7	2017-12-05 18:44:23.476865
003	77	27056603560	20171103	00007664	FA	001200007664	00000111334	00000000200	00000002227	7	2017-12-05 18:44:23.476865
003	78	27367571123	20171103	00007665	FA	001200007665	00000201977	00000000200	00000004040	7	2017-12-05 18:44:23.476865
003	79	20305781720	20171103	00007666	FA	001200007666	00000708378	00000000200	00000014168	7	2017-12-05 18:44:23.476865
014	477	11111111111	20171117	09892291	FA	029100166539	00000065132	00000000600	00000003908	3	2017-12-05 15:57:19.680123
014	478	11111111111	20171117	09892293	FA	029100166544	00000422776	00000000600	00000025367	3	2017-12-05 15:57:19.680123
014	479	27318022327	20171117	09892294	FA	029100166546	00000055443	00000000200	00000001109	3	2017-12-05 15:57:19.680123
014	480	20104309497	20171117	80764281	FA	029100166551	00000482364	00000000200	00000009647	3	2017-12-05 15:57:19.680123
014	481	20104309497	20171117	80764282	FA	029100166553	00000665806	00000000200	00000013316	3	2017-12-05 15:57:19.680123
014	482	27265922665	20171117	09892301	FA	028800259263	00000466928	00000000200	00000009339	3	2017-12-05 15:57:19.680123
014	483	11111111111	20171117	09892302	FA	028800259264	00000123162	00000000600	00000007390	3	2017-12-05 15:57:19.680123
014	484	27265922665	20171117	09892303	FA	028800259265	00000095836	00000000200	00000001917	3	2017-12-05 15:57:19.680123
014	485	20113547198	20171117	09892304	FA	028800259267	00000461999	00000000200	00000009240	3	2017-12-05 15:57:19.680123
014	486	20113547198	20171117	80764302	FA	028800259275	00000152762	00000000200	00000003055	3	2017-12-05 15:57:19.680123
003	80	20305781720	20171103	00007667	FA	001200007667	00000008775	00000000200	00000000176	7	2017-12-05 18:44:23.476865
003	81	27222713140	20171103	00007668	FA	001200007668	00000049702	00000000200	00000000994	7	2017-12-05 18:44:23.476865
003	82	20261719887	20171103	00007669	FA	001200007669	00000145028	00000000200	00000002901	7	2017-12-05 18:44:23.476865
003	83	20078206331	20171103	00007670	FA	001200007670	00000305972	00000000200	00000006119	7	2017-12-05 18:44:23.476865
003	84	27280191235	20171103	00007671	FA	001200007671	00000391019	00000000200	00000007820	7	2017-12-05 18:44:23.476865
003	85	27163184732	20171103	00007672	FA	001200007672	00000120399	00000000200	00000002408	7	2017-12-05 18:44:23.476865
003	86	27222713140	20171103	00007673	FA	001200007673	00000089842	00000000200	00000001797	7	2017-12-05 18:44:23.476865
003	87	20213541421	20171103	00007674	FA	001200007674	00000347072	00000000200	00000006941	7	2017-12-05 18:44:23.476865
003	88	30670388480	20171103	00007675	FA	001200007675	00000178551	00000000200	00000003571	7	2017-12-05 18:44:23.476865
003	89	30715278347	20171103	00007676	FA	001200007676	00000165267	00000000200	00000003305	7	2017-12-05 18:44:23.476865
003	90	27100281819	20171103	00007677	FA	001200007677	00000026458	00000000200	00000000529	7	2017-12-05 18:44:23.476865
003	91	20073261806	20171103	00007678	FA	001200007678	00000066986	00000000200	00000001340	7	2017-12-05 18:44:23.476865
003	92	30670364603	20171103	00007679	FA	001200007679	00000063415	00000000200	00000001268	7	2017-12-05 18:44:23.476865
003	93	27952625514	20171103	00007680	FA	001200007680	00000737116	00000000200	00000014742	7	2017-12-05 18:44:23.476865
003	94	20118857020	20171103	00014671	FA	001200014671	00000040528	00000000200	00000000811	7	2017-12-05 18:44:23.476865
003	95	27346653502	20171103	00014672	FA	001200014672	00000334277	00000000200	00000006686	7	2017-12-05 18:44:23.476865
003	96	20368602923	20171103	00014673	FA	001200014673	00000055589	00000000200	00000001112	7	2017-12-05 18:44:23.476865
003	97	27219273431	20171103	00014674	FA	001200014674	00000026458	00000000200	00000000529	7	2017-12-05 18:44:23.476865
003	98	20173955449	20171103	00014675	FA	001200014675	00000063203	00000000200	00000001264	7	2017-12-05 18:44:23.476865
003	99	27248176607	20171103	00014676	FA	001200014676	00000040411	00000000200	00000000808	7	2017-12-05 18:44:23.476865
003	100	27064350809	20171103	00007717	FA	001200007717	00000191885	00000000200	00000003838	7	2017-12-05 18:44:23.476865
003	101	27316072483	20171103	00007718	FA	001200007718	00000160263	00000000200	00000003205	7	2017-12-05 18:44:23.476865
003	102	27351546986	20171103	00014710	FA	001200014710	00000036893	00000000200	00000000738	7	2017-12-05 18:44:23.476865
003	103	20261719887	20171103	00007714	FA	001200007714	00000042396	00000000200	00000000848	7	2017-12-05 18:44:23.476865
003	104	27305781342	20171103	00014702	FA	001200014702	00000026135	00000000200	00000000523	7	2017-12-05 18:44:23.476865
003	105	20149833510	20171103	00014703	FA	001200014703	00000029219	00000000200	00000000584	7	2017-12-05 18:44:23.476865
003	106	27351546862	20171103	00014704	FA	001200014704	00000037405	00000000200	00000000748	7	2017-12-05 18:44:23.476865
003	107	27942269566	20171103	00014705	FA	001200014705	00000021610	00000000200	00000000432	7	2017-12-05 18:44:23.476865
003	108	27215184760	20171103	00014706	FA	001200014706	00000027266	00000000200	00000000545	7	2017-12-05 18:44:23.476865
003	109	27394430655	20171103	00014707	FA	001200014707	00000091069	00000000200	00000001821	7	2017-12-05 18:44:23.476865
003	110	20113547198	20171103	00014708	FA	001200014708	00000163608	00000000200	00000003272	7	2017-12-05 18:44:23.476865
003	111	27313340339	20171103	00014709	FA	001200014709	00000011227	00000000200	00000000225	7	2017-12-05 18:44:23.476865
003	112	20261719887	20171104	00007771	FA	001200007771	00000129543	00000000200	00000002591	7	2017-12-05 18:44:23.476865
003	113	20225250473	20171104	00011374	FA	001300011374	00000046884	00000000200	00000000938	7	2017-12-05 18:44:23.476865
003	114	20274035170	20171104	00007772	FA	001200007772	00000056784	00000000200	00000001136	7	2017-12-05 18:44:23.476865
003	115	27297722927	20171104	00014753	FA	001200014753	00000111398	00000000200	00000002228	7	2017-12-05 18:44:23.476865
003	116	27297722927	20171104	00014754	FA	001200014754	00000012003	00000000200	00000000240	7	2017-12-05 18:44:23.476865
003	117	27952625514	20171104	00007774	FA	001200007774	00000063599	00000000200	00000001272	7	2017-12-05 18:44:23.476865
003	118	20101553419	20171104	00007775	FA	001200007775	00000100173	00000000200	00000002003	7	2017-12-05 18:44:23.476865
003	119	23289795464	20171104	00007776	FA	001200007776	00000023593	00000000200	00000000472	7	2017-12-05 18:44:23.476865
003	120	23241216349	20171104	00009586	FA	001300009586	00000064720	00000000200	00000001294	7	2017-12-05 18:44:23.476865
003	121	27316072483	20171104	00007780	FA	001200007780	00000020830	00000000200	00000000417	7	2017-12-05 18:44:23.476865
003	122	27125944707	20171104	00007781	FA	001200007781	00000035352	00000000200	00000000707	7	2017-12-05 18:44:23.476865
003	123	23289795464	20171104	00007782	FA	001200007782	00000112563	00000000200	00000002251	7	2017-12-05 18:44:23.476865
003	124	23163173824	20171104	00007783	FA	001200007783	00000072461	00000000200	00000001449	7	2017-12-05 18:44:23.476865
003	125	27942269566	20171104	00014756	FA	001200014756	00000039641	00000000200	00000000793	7	2017-12-05 18:44:23.476865
003	126	27215184760	20171104	00014757	FA	001200014757	00000027608	00000000200	00000000552	7	2017-12-05 18:44:23.476865
003	127	27313340339	20171104	00014758	FA	001200014758	00000060811	00000000200	00000001216	7	2017-12-05 18:44:23.476865
003	128	27297722927	20171104	00014759	FA	001200014759	00000030923	00000000200	00000000618	7	2017-12-05 18:44:23.476865
003	129	27297722927	20171104	00011394	FA	001300011394	00000085957	00000000200	00000001719	7	2017-12-05 18:44:23.476865
003	130	27216609528	20171104	00011403	FA	001300011403	00000019436	00000000200	00000000389	7	2017-12-05 18:44:23.476865
003	131	30670364603	20171104	00007832	FA	001200007832	00000010661	00000000200	00000000213	7	2017-12-05 18:44:23.476865
003	132	27056603560	20171104	00007833	FA	001200007833	00000010661	00000000200	00000000213	7	2017-12-05 18:44:23.476865
003	133	27367571123	20171104	00009608	FA	001300009608	00000221675	00000000200	00000004434	7	2017-12-05 18:44:23.476865
003	134	27367571123	20171104	00009609	FA	001300009609	00000011406	00000000200	00000000228	7	2017-12-05 18:44:23.476865
003	135	27325453465	20171104	00011417	FA	001300011417	00000034143	00000000200	00000000683	7	2017-12-05 18:44:23.476865
003	136	20305781720	20171104	00007827	FA	001200007827	00000069914	00000000200	00000001398	7	2017-12-05 18:44:23.476865
003	137	27363206641	20171104	00011418	FA	001300011418	00000305179	00000000200	00000006104	7	2017-12-05 18:44:23.476865
003	138	27363206641	20171104	00011419	FA	001300011419	00000269479	00000000200	00000005390	7	2017-12-05 18:44:23.476865
003	139	27363206641	20171104	00011420	FA	001300011420	00000049792	00000000200	00000000996	7	2017-12-05 18:44:23.476865
003	140	27163184732	20171106	00007845	FA	001200007845	00000198463	00000000200	00000003969	7	2017-12-05 18:44:23.476865
003	141	27363206641	20171106	00011470	FA	001300011470	00000104198	00000000200	00000002084	7	2017-12-05 18:44:23.476865
003	142	20207989011	20171106	00009621	FA	001300009621	00000019847	00000000200	00000000397	7	2017-12-05 18:44:23.476865
003	143	20054139285	20171106	00007885	FA	001200007885	00000493230	00000000200	00000009865	7	2017-12-05 18:44:23.476865
003	144	27290859560	20171106	00009630	FA	001300009630	00000034612	00000000200	00000000692	7	2017-12-05 18:44:23.476865
003	145	27363206641	20171107	00011521	FA	001300011521	00000036390	00000000200	00000000728	7	2017-12-05 18:44:23.476865
003	146	30714121606	20171107	00009648	FA	001300009648	00000016900	00000000200	00000000338	7	2017-12-05 18:44:23.476865
003	147	27216609528	20171107	00011571	FA	001300011571	00000051389	00000000200	00000001028	7	2017-12-05 18:44:23.476865
003	148	27056603560	20171107	00007932	FA	001200007932	00000060660	00000000200	00000001213	7	2017-12-05 18:44:23.476865
003	149	27173955451	20171107	00007933	FA	001200007933	00000059119	00000000200	00000001182	7	2017-12-05 18:44:23.476865
003	150	20261719887	20171107	00007934	FA	001200007934	00000074137	00000000200	00000001483	7	2017-12-05 18:44:23.476865
003	151	27367571123	20171107	00007935	FA	001200007935	00000072730	00000000200	00000001455	7	2017-12-05 18:44:23.476865
003	152	27163184732	20171107	00007936	FA	001200007936	00000056784	00000000200	00000001136	7	2017-12-05 18:44:23.476865
003	153	30715278347	20171107	00007937	FA	001200007937	00000159763	00000000200	00000003195	7	2017-12-05 18:44:23.476865
003	154	27222713140	20171107	00007938	FA	001200007938	00000031716	00000000200	00000000634	7	2017-12-05 18:44:23.476865
003	155	27351546862	20171107	00014893	FA	001200014893	00000041816	00000000200	00000000836	7	2017-12-05 18:44:23.476865
003	156	23141622099	20171107	00014894	FA	001200014894	00000064546	00000000200	00000001291	7	2017-12-05 18:44:23.476865
003	157	27346653502	20171107	00014896	FA	001200014896	00000138104	00000000200	00000002762	7	2017-12-05 18:44:23.476865
003	158	27219273431	20171107	00014897	FA	001200014897	00000649609	00000000200	00000012992	7	2017-12-05 18:44:23.476865
003	159	20113547198	20171107	00014899	FA	001200014899	00000117677	00000000200	00000002354	7	2017-12-05 18:44:23.476865
003	160	30670364778	20171107	00014900	FA	001200014900	00000118631	00000000200	00000002373	7	2017-12-05 18:44:23.476865
003	161	20368602923	20171107	00014920	FA	001200014920	00000135801	00000000200	00000002716	7	2017-12-05 18:44:23.476865
003	162	27952625514	20171107	00007939	FA	001200007939	00000283882	00000000200	00000005678	7	2017-12-05 18:44:23.476865
003	163	27173955451	20171107	00007946	FA	001200007946	00000191408	00000000200	00000003828	7	2017-12-05 18:44:23.476865
003	164	20261719887	20171107	00007947	FA	001200007947	00000253199	00000000200	00000005064	7	2017-12-05 18:44:23.476865
003	165	27367571123	20171107	00007948	FA	001200007948	00000007266	00000000200	00000000145	7	2017-12-05 18:44:23.476865
003	166	27367571123	20171107	00007949	FA	001200007949	00000101814	00000000200	00000002036	7	2017-12-05 18:44:23.476865
003	167	20261719887	20171107	00007950	FA	001200007950	00000211973	00000000200	00000004239	7	2017-12-05 18:44:23.476865
003	168	27056603560	20171107	00007951	FA	001200007951	00000294546	00000000200	00000005891	7	2017-12-05 18:44:23.476865
003	169	27064350809	20171107	00007952	FA	001200007952	00000176597	00000000200	00000003532	7	2017-12-05 18:44:23.476865
003	170	20305781720	20171107	00007953	FA	001200007953	00000626719	00000000200	00000012534	7	2017-12-05 18:44:23.476865
003	171	30715278347	20171107	00007954	FA	001200007954	00000428540	00000000200	00000008571	7	2017-12-05 18:44:23.476865
003	172	27280191235	20171107	00007955	FA	001200007955	00000064490	00000000200	00000001290	7	2017-12-05 18:44:23.476865
003	173	27952625514	20171107	00007956	FA	001200007956	00000097591	00000000200	00000001952	7	2017-12-05 18:44:23.476865
003	174	27952625514	20171107	00007957	FA	001200007957	00000456751	00000000200	00000009135	7	2017-12-05 18:44:23.476865
003	175	27222713140	20171107	00007958	FA	001200007958	00000121033	00000000200	00000002421	7	2017-12-05 18:44:23.476865
003	176	27222713140	20171107	00007959	FA	001200007959	00000088784	00000000200	00000001776	7	2017-12-05 18:44:23.476865
003	177	23163173824	20171107	00007960	FA	001200007960	00000020079	00000000200	00000000402	7	2017-12-05 18:44:23.476865
003	178	27351546862	20171107	00014911	FA	001200014911	00000034869	00000000200	00000000697	7	2017-12-05 18:44:23.476865
003	179	27266554740	20171107	00014912	FA	001200014912	00000083165	00000000200	00000001663	7	2017-12-05 18:44:23.476865
003	180	27351546862	20171107	00014913	FA	001200014913	00000060759	00000000200	00000001215	7	2017-12-05 18:44:23.476865
003	181	27346653502	20171107	00014915	FA	001200014915	00000242778	00000000200	00000004856	7	2017-12-05 18:44:23.476865
003	182	23141622099	20171107	00014916	FA	001200014916	00000128749	00000000200	00000002575	7	2017-12-05 18:44:23.476865
003	183	27219273431	20171107	00014917	FA	001200014917	00000185524	00000000200	00000003710	7	2017-12-05 18:44:23.476865
003	184	30670364778	20171107	00014919	FA	001200014919	00000132021	00000000200	00000002640	7	2017-12-05 18:44:23.476865
003	185	20305781720	20171108	00008050	FA	001200008050	00000091411	00000000200	00000001828	7	2017-12-05 18:44:23.476865
003	186	20182383016	20171108	00014986	FA	001200014986	00000036822	00000000200	00000000736	7	2017-12-05 18:44:23.476865
003	187	27297722927	20171108	00014987	FA	001200014987	00000037130	00000000200	00000000743	7	2017-12-05 18:44:23.476865
003	188	27367571123	20171108	00009670	FA	001300009670	00000016209	00000000200	00000000324	7	2017-12-05 18:44:23.476865
003	189	20305781720	20171108	00009671	FA	001300009671	00000022772	00000000200	00000000455	7	2017-12-05 18:44:23.476865
003	190	30670364603	20171108	00009672	FA	001300009672	00000007928	00000000200	00000000159	7	2017-12-05 18:44:23.476865
003	191	27222713140	20171108	00009673	FA	001300009673	00000025800	00000000200	00000000516	7	2017-12-05 18:44:23.476865
003	192	27952625514	20171108	00009674	FA	001300009674	00000021354	00000000200	00000000427	7	2017-12-05 18:44:23.476865
003	193	20273644912	20171108	00011595	FA	001300011595	00000017200	00000000200	00000000344	7	2017-12-05 18:44:23.476865
003	194	20226158074	20171108	00011598	FA	001300011598	00000060798	00000000200	00000001216	7	2017-12-05 18:44:23.476865
003	195	20274035170	20171108	00008052	FA	001200008052	00000110864	00000000200	00000002217	7	2017-12-05 18:44:23.476865
003	196	20182383016	20171108	00014991	FA	001200014991	00000035748	00000000200	00000000715	7	2017-12-05 18:44:23.476865
003	197	27297722927	20171108	00014992	FA	001200014992	00000012469	00000000200	00000000249	7	2017-12-05 18:44:23.476865
003	198	20273644912	20171108	00014993	FA	001200014993	00000007959	00000000200	00000000159	7	2017-12-05 18:44:23.476865
003	199	27297722927	20171108	00014994	FA	001200014994	00000013385	00000000200	00000000268	7	2017-12-05 18:44:23.476865
003	200	20229649060	20171108	00009680	FA	001300009680	00000049159	00000000200	00000000983	7	2017-12-05 18:44:23.476865
003	201	20226158074	20171108	00016266	FA	001900016266	00000048736	00000000200	00000000975	7	2017-12-05 18:44:23.476865
003	202	20940818649	20171109	00016284	FA	001900016284	00000050428	00000000200	00000001009	7	2017-12-05 18:44:23.476865
003	203	30715278347	20171109	00026313	FA	001500026313	00000132197	00000000200	00000002644	7	2017-12-05 18:44:23.476865
003	204	27351546986	20171109	00025927	FA	001500025927	00000365783	00000000200	00000007316	7	2017-12-05 18:44:23.476865
003	205	23241216349	20171109	00008119	FA	001900008119	00000196222	00000000200	00000003924	7	2017-12-05 18:44:23.476865
003	206	30715278347	20171109	00020374	FA	001700020374	00000398534	00000000200	00000007971	7	2017-12-05 18:44:23.476865
003	207	27952625514	20171109	00020376	FA	001700020376	00001564015	00000000200	00000031280	7	2017-12-05 18:44:23.476865
003	208	27313340339	20171109	00016293	FA	001900016293	00000023878	00000000200	00000000478	7	2017-12-05 18:44:23.476865
003	209	20225687073	20171110	00016339	FA	001900016339	00000022217	00000000200	00000000444	7	2017-12-05 18:44:23.476865
003	210	27952625514	20171110	00020402	FA	001700020402	00000217529	00000000200	00000004351	7	2017-12-05 18:44:23.476865
003	211	27367571123	20171110	00005562	FA	001800005562	00000018598	00000000200	00000000372	7	2017-12-05 18:44:23.476865
003	212	20213541421	20171110	00005563	FA	001800005563	00000026251	00000000200	00000000525	7	2017-12-05 18:44:23.476865
003	213	30670364603	20171110	00005564	FA	001800005564	00000028980	00000000200	00000000580	7	2017-12-05 18:44:23.476865
003	214	27346653502	20171110	00006720	FA	001800006720	00000104259	00000000200	00000002085	7	2017-12-05 18:44:23.476865
003	215	27367571123	20171110	00020400	FA	001700020400	00000027703	00000000200	00000000554	7	2017-12-05 18:44:23.476865
003	216	20213541421	20171110	00020401	FA	001700020401	00000012119	00000000200	00000000242	7	2017-12-05 18:44:23.476865
003	217	27266554740	20171110	00020292	FA	001700020292	00000021652	00000000200	00000000433	7	2017-12-05 18:44:23.476865
003	218	20118857020	20171110	00020293	FA	001700020293	00000056784	00000000200	00000001136	7	2017-12-05 18:44:23.476865
014	487	20249011461	20171117	80764336	FA	029100166631	00000127053	00000000200	00000002541	3	2017-12-05 15:57:19.680123
014	488	20173955740	20171117	80303843	FA	029100066386	00000194097	00000000200	00000003882	3	2017-12-05 15:57:19.680123
014	489	20125940995	20171117	09892331	FA	028800259300	00000483019	00000000200	00000009661	3	2017-12-05 15:57:19.680123
014	490	20227196441	20171117	09823905	FA	029100066395	00000320188	00000000200	00000006404	3	2017-12-05 15:57:19.680123
014	491	20227196441	20171117	09823906	FA	029100066396	00000019319	00000000200	00000000386	3	2017-12-05 15:57:19.680123
014	492	23289795464	20171117	80303862	FA	028800094642	00000215857	00000000200	00000004317	3	2017-12-05 15:57:19.680123
014	493	11111111111	20171117	80764378	FA	028800259340	00000076309	00000000600	00000004578	3	2017-12-05 15:57:19.680123
014	494	11111111111	20171117	80764380	FA	028800259341	00000126859	00000000600	00000007611	3	2017-12-05 15:57:19.680123
014	495	20170290209	20171118	80764389	FA	029100166672	00000217787	00000000200	00000004356	3	2017-12-05 15:57:19.680123
014	496	11111111111	20171118	09892358	FA	029100166701	00000172252	00000000600	00000010335	3	2017-12-05 15:57:19.680123
014	497	30709978248	20171118	80303879	FA	029100066422	00000270881	00000000200	00000005418	3	2017-12-05 15:57:19.680123
014	498	30709978248	20171118	80303880	FA	029100066423	00000031998	00000000200	00000000640	3	2017-12-05 15:57:19.680123
008	2	20163184592	20170809	00000001	FA	000200001508	00000120662	00000000200	00000002413	12	2017-10-13 11:17:45.660159
008	3	27952625514	20170809	00000002	FA	000200001509	00000089896	00000000200	00000001798	12	2017-10-13 11:17:45.660159
008	4	23289795464	20170809	00000003	FA	000200001510	00000078928	00000000200	00000001579	12	2017-10-13 11:17:45.660159
008	5	20261719887	20170809	00000004	FA	000200001511	00000191738	00000000200	00000003835	12	2017-10-13 11:17:45.660159
008	6	20274035170	20170809	00000005	FA	000200001512	00000339669	00000000200	00000006793	12	2017-10-13 11:17:45.660159
008	7	27222713140	20170823	00000006	FA	000200001513	00000344751	00000000200	00000006895	12	2017-10-13 11:17:45.660159
008	8	23289795464	20170823	00000007	FA	000200001514	00000188176	00000000200	00000003764	12	2017-10-13 11:17:45.660159
008	9	27952625514	20170823	00000008	FA	000200001515	00000297173	00000000200	00000005943	12	2017-10-13 11:17:45.660159
008	10	27952625514	20170823	00000009	FA	000200001516	00000273344	00000000200	00000005467	12	2017-10-13 11:17:45.660159
008	11	23344036209	20170823	00000010	FA	000200001517	00000041323	00000000200	00000000826	12	2017-10-13 11:17:45.660159
014	499	27162802262	20171118	80764408	FA	029100166710	00000137457	00000000200	00000002749	3	2017-12-05 15:57:19.680123
014	500	11111111111	20171118	80764410	FA	029100166714	00000199631	00000000600	00000011978	3	2017-12-05 15:57:19.680123
014	501	20170936974	20171118	80764432	FA	029100166763	00000217012	00000000200	00000004340	3	2017-12-05 15:57:19.680123
014	502	20260045300	20171118	09892385	FA	029100166798	00000434564	00000000200	00000008691	3	2017-12-05 15:57:19.680123
014	503	23073183979	20171118	09892395	FA	029100166822	00000477718	00000000200	00000009554	3	2017-12-05 15:57:19.680123
014	504	11111111111	20171118	80764448	FA	029100166826	00000104016	00000000600	00000006241	3	2017-12-05 15:57:19.680123
014	505	11111111111	20171118	80764449	FA	029100166827	00000021344	00000000600	00000001281	3	2017-12-05 15:57:19.680123
014	506	20078194899	20171118	09823918	FA	029100066440	00000051898	00000000200	00000001038	3	2017-12-05 15:57:19.680123
014	507	11111111111	20171118	80764454	FA	029100166850	00000296714	00000000600	00000017803	3	2017-12-05 15:57:19.680123
014	508	11111111111	20171118	80764456	FA	029100166858	00000207006	00000000600	00000012420	3	2017-12-05 15:57:19.680123
014	509	11111111111	20171118	80764457	FA	029100166859	00000125053	00000000600	00000007503	3	2017-12-05 15:57:19.680123
014	510	11111111111	20171118	80764458	FA	029100166861	00000108624	00000000600	00000006518	3	2017-12-05 15:57:19.680123
014	511	11111111111	20171118	08064376	NC	029100011378	-0000021344	00000000600	-0000001281	3	2017-12-05 15:57:19.680123
014	512	20073186901	20171118	09892424	FA	029100166906	00000065470	00000000200	00000001310	3	2017-12-05 15:57:19.680123
014	513	11111111111	20171118	80764474	FA	029100166911	00000479801	00000000600	00000028788	3	2017-12-05 15:57:19.680123
014	514	27101402156	20171118	80764490	FA	029100166941	00000346404	00000000200	00000006928	3	2017-12-05 15:57:19.680123
014	515	27101402156	20171118	80764495	FA	029100166945	00000022554	00000000200	00000000451	3	2017-12-05 15:57:19.680123
014	516	27173955249	20171118	80764497	FA	029100166954	00000569231	00000000200	00000011385	3	2017-12-05 15:57:19.680123
014	517	11111111111	20171118	09892431	FA	029100166959	00000356981	00000000600	00000021419	3	2017-12-05 15:57:19.680123
014	518	20259753253	20171118	80764502	FA	029100166969	00000236590	00000000200	00000004732	3	2017-12-05 15:57:19.680123
014	519	20076156280	20171118	09892433	FA	029100166971	00000067114	00000000200	00000001342	3	2017-12-05 15:57:19.680123
014	520	11111111111	20171118	09892435	FA	029100166975	00000108951	00000000600	00000006537	3	2017-12-05 15:57:19.680123
014	521	11111111111	20171118	80764503	FA	029100166974	00000020981	00000000600	00000001259	3	2017-12-05 15:57:19.680123
014	522	20076156280	20171118	80764504	FA	029100166972	00000241605	00000000200	00000004832	3	2017-12-05 15:57:19.680123
014	523	11111111111	20171118	80764506	FA	029100166979	00000193627	00000000600	00000011617	3	2017-12-05 15:57:19.680123
014	524	20076156280	20171118	80764508	FA	029100166980	00000013128	00000000200	00000000263	3	2017-12-05 15:57:19.680123
014	525	30714121606	20171118	09823931	FA	029100066502	00000338700	00000000200	00000006774	3	2017-12-05 15:57:19.680123
014	526	23344036209	20171118	80303913	FA	029100066503	00000173899	00000000200	00000003478	3	2017-12-05 15:57:19.680123
014	527	23344036209	20171118	80303914	FA	029100066504	00000016120	00000000200	00000000322	3	2017-12-05 15:57:19.680123
014	528	11111111111	20171118	09892444	FA	029100167011	00000071043	00000000600	00000004263	3	2017-12-05 15:57:19.680123
014	529	11111111111	20171118	80764523	FA	029100167012	00000095861	00000000600	00000005752	3	2017-12-05 15:57:19.680123
014	530	20331132269	20171118	80303918	FA	029100066512	00000100850	00000000200	00000002017	3	2017-12-05 15:57:19.680123
014	531	20290121222	20171121	80764552	FA	029100167068	00000411112	00000000200	00000008222	3	2017-12-05 15:57:19.680123
014	532	20226157833	20171121	80303928	FA	029100066536	00000497283	00000000200	00000009946	3	2017-12-05 15:57:19.680123
014	533	20226157833	20171121	80303929	FA	029100066538	00000081239	00000000200	00000001625	3	2017-12-05 15:57:19.680123
014	534	20226157833	20171121	08035403	NC	029100006399	-0000063599	00000000200	-0000001272	3	2017-12-05 15:57:19.680123
014	535	20253240653	20171121	80764572	FA	028800259355	00000126449	00000000200	00000002529	3	2017-12-05 15:57:19.680123
014	536	20169883646	20171121	80303930	FA	028800094646	00000122850	00000000200	00000002457	3	2017-12-05 15:57:19.680123
014	537	20253240653	20171121	80764573	FA	028800259357	00000184241	00000000200	00000003685	3	2017-12-05 15:57:19.680123
014	538	11111111111	20171121	80764574	FA	028800259358	00000011526	00000000600	00000000692	3	2017-12-05 15:57:19.680123
014	539	11111111111	20171121	09892468	FA	028800259359	00000112004	00000000600	00000006720	3	2017-12-05 15:57:19.680123
014	540	27166891936	20171121	09892469	FA	028800259366	00000126762	00000000200	00000002535	3	2017-12-05 15:57:19.680123
014	541	20184322324	20171121	80303932	FA	028800094651	00000123961	00000000200	00000002479	3	2017-12-05 15:57:19.680123
014	542	27297722927	20171121	80764586	FA	029100167110	00000036074	00000000200	00000000722	3	2017-12-05 15:57:19.680123
014	543	20125943250	20171121	80764587	FA	029100167112	00000642539	00000000200	00000012851	3	2017-12-05 15:57:19.680123
014	544	27101401656	20171121	80764588	FA	029100167114	00000290932	00000000200	00000005819	3	2017-12-05 15:57:19.680123
014	545	20177825418	20171121	80303942	FA	029100066550	00000081913	00000000200	00000001638	3	2017-12-05 15:57:19.680123
014	546	20104309497	20171121	09892490	FA	029100167143	00000112578	00000000200	00000002252	3	2017-12-05 15:57:19.680123
014	547	20125943250	20171121	80764608	FA	029100167152	00000329863	00000000200	00000006597	3	2017-12-05 15:57:19.680123
014	548	20078127660	20171121	09892511	FA	029100167206	00000130994	00000000200	00000002620	3	2017-12-05 15:57:19.680123
014	549	20078127660	20171121	09892512	FA	029100167207	00000028613	00000000200	00000000572	3	2017-12-05 15:57:19.680123
014	550	23241216349	20171121	80303964	FA	028800094671	00000474278	00000000200	00000009486	3	2017-12-05 15:57:19.680123
014	551	11111111111	20171121	09892522	FA	028800259403	00000078214	00000000600	00000004693	3	2017-12-05 15:57:19.680123
014	552	23148113149	20171121	80303968	FA	028800094677	00000173343	00000000200	00000003467	3	2017-12-05 15:57:19.680123
014	553	30670364778	20171121	80764639	FA	028800259409	00000227530	00000000200	00000004551	3	2017-12-05 15:57:19.680123
014	554	11111111111	20171121	80764640	FA	028800259406	00000128344	00000000600	00000007701	3	2017-12-05 15:57:19.680123
014	555	20125944230	20171121	09823953	FA	028800094680	00000334160	00000000200	00000006683	3	2017-12-05 15:57:19.680123
014	556	20252613456	20171122	80764647	FA	029100167233	00000268646	00000000200	00000005373	3	2017-12-05 15:57:19.680123
014	557	27111171829	20171122	80764655	FA	029100167246	00000108696	00000000200	00000002174	3	2017-12-05 15:57:19.680123
014	558	20182017982	20171122	80303983	FA	028800094686	00000142749	00000000200	00000002855	3	2017-12-05 15:57:19.680123
014	559	23141622099	20171122	80764659	FA	029100167250	00000091019	00000000200	00000001820	3	2017-12-05 15:57:19.680123
008	12	20163184592	20170823	00000011	FA	000200001518	00000119010	00000000200	00000002380	12	2017-10-13 11:17:45.660159
008	13	27952625514	20170823	00000012	FA	000200001519	00000044628	00000000200	00000000893	12	2017-10-13 11:17:45.660159
008	14	27236426357	20170803	00000013	FA	000200001883	00000117686	00000000200	00000002354	12	2017-10-13 11:17:45.660159
008	15	20142754496	20170803	00000014	FA	000200001884	00000109835	00000000200	00000002197	12	2017-10-13 11:17:45.660159
008	16	27274798861	20170808	00000015	FA	000200001885	00000118636	00000000200	00000002373	12	2017-10-13 11:17:45.660159
008	17	27280189362	20170808	00000016	FA	000200001886	00000071074	00000000200	00000001421	12	2017-10-13 11:17:45.660159
008	18	27309418064	20170808	00000017	FA	000200001887	00000076033	00000000200	00000001521	12	2017-10-13 11:17:45.660159
008	19	27305782004	20170808	00000018	FA	000200001888	00000085207	00000000200	00000001704	12	2017-10-13 11:17:45.660159
008	20	20267029114	20170809	00000019	FA	000200001889	00000054298	00000000200	00000001086	12	2017-10-13 11:17:45.660159
008	21	20284642369	20170809	00000020	FA	000200001890	00000039669	00000000200	00000000793	12	2017-10-13 11:17:45.660159
008	22	27313340339	20170809	00000021	FA	000200001891	00000180661	00000000200	00000003613	12	2017-10-13 11:17:45.660159
008	23	27321549409	20170809	00000022	FA	000200001892	00000048777	00000000200	00000000976	12	2017-10-13 11:17:45.660159
008	24	27112378257	20170809	00000023	FA	000200001894	00000114876	00000000200	00000002298	12	2017-10-13 11:17:45.660159
008	25	27245845494	20170823	00000024	FA	000200001895	00000175207	00000000200	00000003504	12	2017-10-13 11:17:45.660159
008	26	20149833510	20170823	00000025	FA	000200001896	00000034545	00000000200	00000000691	12	2017-10-13 11:17:45.660159
003	219	27219273431	20171110	00020294	FA	001700020294	00000117392	00000000200	00000002348	7	2017-12-05 18:44:23.476865
003	220	27346653502	20171110	00020295	FA	001700020295	00000030467	00000000200	00000000609	7	2017-12-05 18:44:23.476865
003	221	27112378257	20171110	00020296	FA	001700020296	00000049791	00000000200	00000000996	7	2017-12-05 18:44:23.476865
003	222	27367571123	20171110	00020403	FA	001700020403	00000177790	00000000200	00000003556	7	2017-12-05 18:44:23.476865
003	223	20261719887	20171110	00020404	FA	001700020404	00000113801	00000000200	00000002276	7	2017-12-05 18:44:23.476865
003	224	27188206463	20171110	00020405	FA	001700020405	00000039444	00000000200	00000000789	7	2017-12-05 18:44:23.476865
003	225	27056603560	20171110	00020406	FA	001700020406	00000048477	00000000200	00000000970	7	2017-12-05 18:44:23.476865
003	226	30670364603	20171110	00020410	FA	001700020410	00000061194	00000000200	00000001224	7	2017-12-05 18:44:23.476865
003	227	30714121606	20171110	00020411	FA	001700020411	00000462762	00000000200	00000009255	7	2017-12-05 18:44:23.476865
003	228	30670364603	20171110	00020412	FA	001700020412	00000092166	00000000200	00000001843	7	2017-12-05 18:44:23.476865
003	229	27222713140	20171110	00020413	FA	001700020413	00000061768	00000000200	00000001235	7	2017-12-05 18:44:23.476865
003	230	27952625514	20171110	00020414	FA	001700020414	00000278208	00000000200	00000005564	7	2017-12-05 18:44:23.476865
003	231	20101553419	20171110	00020426	FA	001700020426	00000040889	00000000200	00000000818	7	2017-12-05 18:44:23.476865
003	232	20166734178	20171110	00020427	FA	001700020427	00000101895	00000000200	00000002038	7	2017-12-05 18:44:23.476865
003	233	20242148127	20171110	00020433	FA	001700020433	00000067897	00000000200	00000001358	7	2017-12-05 18:44:23.476865
003	234	23163173824	20171110	00020435	FA	001700020435	00000071575	00000000200	00000001432	7	2017-12-05 18:44:23.476865
003	235	27222713140	20171110	00020438	FA	001700020438	00000015351	00000000200	00000000307	7	2017-12-05 18:44:23.476865
003	236	20149833510	20171110	00020300	FA	001700020300	00000026458	00000000200	00000000529	7	2017-12-05 18:44:23.476865
003	237	27361302759	20171110	00020301	FA	001700020301	00000241037	00000000200	00000004821	7	2017-12-05 18:44:23.476865
003	238	27219273431	20171110	00020310	FA	001700020310	00000127742	00000000200	00000002555	7	2017-12-05 18:44:23.476865
003	239	27219273431	20171110	00020311	FA	001700020311	00000360684	00000000200	00000007214	7	2017-12-05 18:44:23.476865
003	240	20368602923	20171110	00020312	FA	001700020312	00000136000	00000000200	00000002720	7	2017-12-05 18:44:23.476865
003	241	27237091987	20171110	00020320	FA	001700020320	00000024932	00000000200	00000000499	7	2017-12-05 18:44:23.476865
003	242	27215184760	20171110	00020321	FA	001700020321	00000018664	00000000200	00000000373	7	2017-12-05 18:44:23.476865
003	243	27313340339	20171110	00020327	FA	001700020327	00000077671	00000000200	00000001553	7	2017-12-05 18:44:23.476865
003	244	20113547198	20171110	00020328	FA	001700020328	00000316021	00000000200	00000006320	7	2017-12-05 18:44:23.476865
003	245	27248176607	20171110	00020329	FA	001700020329	00000242450	00000000200	00000004849	7	2017-12-05 18:44:23.476865
003	246	27216609528	20171110	00016355	FA	001900016355	00000016990	00000000200	00000000340	7	2017-12-05 18:44:23.476865
003	247	27325453465	20171110	00016358	FA	001900016358	00000076549	00000000200	00000001531	7	2017-12-05 18:44:23.476865
003	248	20305781720	20171110	00026355	FA	001500026355	00000407184	00000000200	00000008144	7	2017-12-05 18:44:23.476865
003	249	27188206463	20171110	00026356	FA	001500026356	00000170556	00000000200	00000003411	7	2017-12-05 18:44:23.476865
003	250	27064350809	20171110	00026357	FA	001500026357	00000092881	00000000200	00000001858	7	2017-12-05 18:44:23.476865
003	251	27056603560	20171110	00026358	FA	001500026358	00000221912	00000000200	00000004438	7	2017-12-05 18:44:23.476865
003	252	27367571123	20171110	00026359	FA	001500026359	00000038525	00000000200	00000000771	7	2017-12-05 18:44:23.476865
003	253	20213541421	20171110	00026360	FA	001500026360	00000191376	00000000200	00000003828	7	2017-12-05 18:44:23.476865
003	254	20073261806	20171110	00026361	FA	001500026361	00000052856	00000000200	00000001057	7	2017-12-05 18:44:23.476865
003	255	20163184592	20171110	00026362	FA	001500026362	00000060185	00000000200	00000001204	7	2017-12-05 18:44:23.476865
014	560	11111111111	20171122	80764660	FA	029100167252	00000412167	00000000600	00000024730	3	2017-12-05 15:57:19.680123
014	561	20085135318	20171122	80764665	FA	029100167260	00000110342	00000000200	00000002207	3	2017-12-05 15:57:19.680123
014	562	30715184040	20171122	80303985	FA	029100066593	00000246405	00000000200	00000004928	3	2017-12-05 15:57:19.680123
014	563	11111111111	20171122	09892532	FA	029100167263	00000241980	00000000600	00000014519	3	2017-12-05 15:57:19.680123
014	564	30691059711	20171122	80303989	FA	029100066600	00000119952	00000000200	00000002399	3	2017-12-05 15:57:19.680123
014	565	20237472897	20171122	80764677	FA	029100167279	00000150081	00000000200	00000003002	3	2017-12-05 15:57:19.680123
014	566	11111111111	20171122	80764678	FA	029100167282	00000172662	00000000600	00000010360	3	2017-12-05 15:57:19.680123
014	567	11111111111	20171122	80764680	FA	029100167286	00000151957	00000000600	00000009118	3	2017-12-05 15:57:19.680123
014	568	11111111111	20171122	09892538	FA	029100167287	00000086462	00000000600	00000005188	3	2017-12-05 15:57:19.680123
014	569	27111449304	20171122	09892543	FA	029100167305	00000161984	00000000200	00000003240	3	2017-12-05 15:57:19.680123
014	570	20125941983	20171122	09823959	FA	028800094690	00000438604	00000000200	00000008772	3	2017-12-05 15:57:19.680123
014	571	30714240389	20171122	09823963	FA	029100066607	00000313261	00000000200	00000006266	3	2017-12-05 15:57:19.680123
014	572	11111111111	20171122	09892552	FA	029100167316	00000449692	00000000600	00000026982	3	2017-12-05 15:57:19.680123
014	573	11111111111	20171122	80764720	FA	029100167325	00000078987	00000000600	00000004739	3	2017-12-05 15:57:19.680123
014	574	30653645143	20171122	09892564	FA	029100167348	00000174673	00000000200	00000003494	3	2017-12-05 15:57:19.680123
014	575	30707024085	20171122	09823972	FA	029100066641	00000541614	00000000200	00000010832	3	2017-12-05 15:57:19.680123
014	576	27100281819	20171122	80304016	FA	029100066644	00000432282	00000000200	00000008646	3	2017-12-05 15:57:19.680123
014	577	27173955249	20171122	80764745	FA	028800259486	00000065290	00000000200	00000001306	3	2017-12-05 15:57:19.680123
014	578	23289795464	20171122	80304022	FA	028800094715	00002405069	00000000200	00000048101	3	2017-12-05 15:57:19.680123
014	579	23289795464	20171122	80304023	FA	028800094716	00000896113	00000000200	00000017922	3	2017-12-05 15:57:19.680123
014	580	23289795464	20171122	80304024	FA	028800094717	00001047274	00000000200	00000020946	3	2017-12-05 15:57:19.680123
014	581	23289795464	20171122	80304029	FA	028800094725	00000467256	00000000200	00000009345	3	2017-12-05 15:57:19.680123
014	582	23289795464	20171123	80304030	FA	029100066648	00000388902	00000000200	00000007778	3	2017-12-05 15:57:19.680123
014	583	23289795464	20171123	80304031	FA	029100066650	00000930879	00000000200	00000018618	3	2017-12-05 15:57:19.680123
014	584	23289795464	20171123	80304033	FA	028800094727	00000668139	00000000200	00000013363	3	2017-12-05 15:57:19.680123
014	585	23289795464	20171123	80304034	FA	028800094729	00000482487	00000000200	00000009650	3	2017-12-05 15:57:19.680123
014	586	23289795464	20171123	08035415	NC	028800006248	-0000022799	00000000200	-0000000456	3	2017-12-05 15:57:19.680123
014	587	11111111111	20171123	80764760	FA	029100167372	00000748068	00000000600	00000044884	3	2017-12-05 15:57:19.680123
014	588	23289795464	20171123	80304035	FA	028800094730	00000490683	00000000200	00000009814	3	2017-12-05 15:57:19.680123
014	589	23289795464	20171123	80304037	FA	028800094732	00000455978	00000000200	00000009120	3	2017-12-05 15:57:19.680123
014	590	27044920102	20171123	80764767	FA	028800259520	00000575430	00000000200	00000011509	3	2017-12-05 15:57:19.680123
014	591	11111111111	20171123	09892591	FA	029100167377	00000804918	00000000600	00000048295	3	2017-12-05 15:57:19.680123
014	592	11111111111	20171123	80764768	FA	029100167378	00000028740	00000000600	00000001724	3	2017-12-05 15:57:19.680123
014	593	11111111111	20171123	80764771	FA	028800259526	00000785749	00000000600	00000047145	3	2017-12-05 15:57:19.680123
014	594	11111111111	20171123	00986354	NC	028800012071	-0000804918	00000000600	-0000048295	3	2017-12-05 15:57:19.680123
014	595	20178691849	20171123	80304038	FA	028800094735	00000008400	00000000200	00000000168	3	2017-12-05 15:57:19.680123
014	596	20178691849	20171123	08035416	NC	028800006250	-0000008400	00000000200	-0000000168	3	2017-12-05 15:57:19.680123
014	597	20178691849	20171123	80304039	FA	028800094736	00000008400	00000000200	00000000168	3	2017-12-05 15:57:19.680123
014	598	30710404670	20171123	09823979	FA	029100066654	00000276517	00000000200	00000005531	3	2017-12-05 15:57:19.680123
014	599	20066520200	20171123	09892592	FA	029100167389	00000492076	00000000200	00000009842	3	2017-12-05 15:57:19.680123
014	600	20066520200	20171123	09892593	FA	029100167390	00000286599	00000000200	00000005732	3	2017-12-05 15:57:19.680123
014	601	20083968436	20171123	80304041	FA	029100066657	00000140231	00000000200	00000002805	3	2017-12-05 15:57:19.680123
014	602	23289795464	20171123	08035417	NC	029100006406	-0000146381	00000000200	-0000002928	3	2017-12-05 15:57:19.680123
014	603	23289795464	20171123	08035418	NC	029100006407	-0000105000	00000000200	-0000002100	3	2017-12-05 15:57:19.680123
014	604	27112378257	20171123	80764784	FA	029100167408	00000192878	00000000200	00000003858	3	2017-12-05 15:57:19.680123
014	605	20937978473	20171123	80764785	FA	029100167410	00000367779	00000000200	00000007356	3	2017-12-05 15:57:19.680123
014	606	20937978473	20171123	80764786	FA	029100167411	00000071782	00000000200	00000001436	3	2017-12-05 15:57:19.680123
014	607	20078191830	20171123	80304043	FA	029100066662	00000158680	00000000200	00000003174	3	2017-12-05 15:57:19.680123
014	608	11111111111	20171123	80764789	FA	029100167420	00000357344	00000000600	00000021441	3	2017-12-05 15:57:19.680123
014	609	20269989557	20171123	80764790	FA	029100167421	00000124848	00000000200	00000002497	3	2017-12-05 15:57:19.680123
014	610	11111111111	20171123	09892603	FA	029100167422	00000345294	00000000600	00000020718	3	2017-12-05 15:57:19.680123
014	611	27011995093	20171123	80764791	FA	029100167423	00000035814	00000000200	00000000716	3	2017-12-05 15:57:19.680123
014	612	20125944281	20171123	80304046	FA	029100066665	00000042405	00000000200	00000000848	3	2017-12-05 15:57:19.680123
014	613	23289795464	20171123	08035420	NC	029100006409	-0000140430	00000000200	-0000002809	3	2017-12-05 15:57:19.680123
014	614	30670364778	20171123	80764814	FA	028800259548	00000672074	00000000200	00000013442	3	2017-12-05 15:57:19.680123
014	615	20237092350	20171123	80304052	FA	028800094739	00000272611	00000000200	00000005452	3	2017-12-05 15:57:19.680123
014	616	11111111111	20171123	80764818	FA	028800259551	00000213916	00000000600	00000012835	3	2017-12-05 15:57:19.680123
014	617	23289795464	20171123	80304053	FA	028800094747	00000100194	00000000200	00000002004	3	2017-12-05 15:57:19.680123
014	618	20237092350	20171123	08035422	NC	028800006251	-0000019630	00000000200	-0000000393	3	2017-12-05 15:57:19.680123
014	619	20237092350	20171123	80304054	FA	028800094740	00000012620	00000000200	00000000252	3	2017-12-05 15:57:19.680123
014	620	27125943484	20171123	09892612	FA	028800259554	00000327588	00000000200	00000006552	3	2017-12-05 15:57:19.680123
014	621	23289795464	20171123	08035423	NC	028800006252	-0000334136	00000000200	-0000006683	3	2017-12-05 15:57:19.680123
014	622	23289795464	20171123	08035424	NC	028800006253	-0000012894	00000000200	-0000000258	3	2017-12-05 15:57:19.680123
014	623	33715326359	20171123	80304059	FA	029100066678	00000297321	00000000200	00000005947	3	2017-12-05 15:57:19.680123
014	624	11111111111	20171123	80764843	FA	029100167483	00000281224	00000000600	00000016874	3	2017-12-05 15:57:19.680123
014	625	11111111111	20171123	09892625	FA	029100167485	00000211508	00000000600	00000012691	3	2017-12-05 15:57:19.680123
014	626	23046318854	20171123	80764844	FA	029100167486	00000157124	00000000200	00000003143	3	2017-12-05 15:57:19.680123
014	627	20169103381	20171123	09892626	FA	029100167494	00000154567	00000000200	00000003092	3	2017-12-05 15:57:19.680123
014	628	11111111111	20171123	09892627	FA	029100167496	00000296759	00000000600	00000017806	3	2017-12-05 15:57:19.680123
014	629	11111111111	20171123	80764850	FA	028800259567	00000204158	00000000600	00000012249	3	2017-12-05 15:57:19.680123
014	630	20290121915	20171123	80764893	FA	028800259629	00000048598	00000000200	00000000972	3	2017-12-05 15:57:19.680123
014	631	20182017982	20171123	80304093	FA	028800094774	00000051382	00000000200	00000001028	3	2017-12-05 15:57:19.680123
014	632	20073299498	20171123	09823996	FA	028800094775	00000176354	00000000200	00000003527	3	2017-12-05 15:57:19.680123
014	633	27064350809	20171124	80304097	FA	029100066729	00003481524	00000000200	00000069633	3	2017-12-05 15:57:19.680123
014	634	27921235521	20171124	80764917	FA	028800259645	00000363804	00000000200	00000007276	3	2017-12-05 15:57:19.680123
014	635	11111111111	20171124	80764918	FA	029100167575	00000375015	00000000600	00000022501	3	2017-12-05 15:57:19.680123
014	636	27943434803	20171124	80764923	FA	029100167577	00000184426	00000000200	00000003689	3	2017-12-05 15:57:19.680123
014	637	11111111111	20171124	80764925	FA	029100167578	00000034828	00000000600	00000002090	3	2017-12-05 15:57:19.680123
014	638	30670386577	20171124	09823997	FA	028800094779	00000292559	00000000200	00000005851	3	2017-12-05 15:57:19.680123
014	639	27222713140	20171124	80304100	FA	029100066736	00001842131	00000000200	00000036844	3	2017-12-05 15:57:19.680123
014	640	23344036209	20171124	80304101	FA	029100066739	00000080336	00000000200	00000001607	3	2017-12-05 15:57:19.680123
014	641	11111111111	20171124	80764935	FA	029100167590	00000042448	00000000600	00000002547	3	2017-12-05 15:57:19.680123
014	642	27222713140	20171124	08035435	NC	029100006412	-0000056350	00000000200	-0000001127	3	2017-12-05 15:57:19.680123
014	643	30715184040	20171124	80304104	FA	029100066748	00000160355	00000000200	00000003207	3	2017-12-05 15:57:19.680123
014	644	11111111111	20171124	80764941	FA	029100167605	00000763194	00000000600	00000045791	3	2017-12-05 15:57:19.680123
014	645	20169211222	20171124	09892679	FA	028800259650	00000112615	00000000200	00000002252	3	2017-12-05 15:57:19.680123
014	646	11111111111	20171124	80764945	FA	028800259654	00000162539	00000000600	00000009752	3	2017-12-05 15:57:19.680123
014	647	11111111111	20171124	80764946	FA	028800259656	00000120987	00000000600	00000007259	3	2017-12-05 15:57:19.680123
014	648	11111111111	20171124	80764947	FA	028800259655	00000085603	00000000600	00000005136	3	2017-12-05 15:57:19.680123
014	649	11111111111	20171124	80764950	FA	028800259659	00000086170	00000000600	00000005170	3	2017-12-05 15:57:19.680123
014	650	27101401656	20171124	80764951	FA	028800259658	00000028532	00000000200	00000000571	3	2017-12-05 15:57:19.680123
014	651	11111111111	20171124	80764952	FA	028800259660	00000055539	00000000600	00000003332	3	2017-12-05 15:57:19.680123
014	652	27185951184	20171124	80764953	FA	028800259661	00000073034	00000000200	00000001461	3	2017-12-05 15:57:19.680123
014	653	11111111111	20171124	09892681	FA	028800259665	00000152142	00000000600	00000009128	3	2017-12-05 15:57:19.680123
014	654	27219273105	20171124	80764956	FA	028800259667	00000072410	00000000200	00000001448	3	2017-12-05 15:57:19.680123
014	655	27219273105	20171124	80764957	FA	028800259668	00000155189	00000000200	00000003104	3	2017-12-05 15:57:19.680123
014	656	11111111111	20171124	09892682	FA	028800259670	00000120871	00000000600	00000007252	3	2017-12-05 15:57:19.680123
014	657	27166891936	20171124	80764959	FA	029100167617	00000054779	00000000200	00000001096	3	2017-12-05 15:57:19.680123
014	658	20178081498	20171124	80764971	FA	028800259682	00000035747	00000000200	00000000715	3	2017-12-05 15:57:19.680123
014	659	20178081498	20171124	80764972	FA	028800259683	00000004830	00000000200	00000000097	3	2017-12-05 15:57:19.680123
014	660	11111111111	20171124	00986360	NC	028800012088	-0000120871	00000000600	-0000007252	3	2017-12-05 15:57:19.680123
014	661	20076156280	20171124	80765012	FA	029100167654	00000340192	00000000200	00000006804	3	2017-12-05 15:57:19.680123
014	662	27356936561	20171124	80765047	FA	029100167715	00000089431	00000000200	00000001789	3	2017-12-05 15:57:19.680123
014	663	30707024085	20171124	09824010	FA	028800094800	00000656733	00000000200	00000013135	3	2017-12-05 15:57:19.680123
014	664	30647748739	20171124	80765058	FA	028800259742	00000323355	00000000200	00000006467	3	2017-12-05 15:57:19.680123
014	665	30647748739	20171124	80765060	FA	028800259743	00000036008	00000000200	00000000720	3	2017-12-05 15:57:19.680123
014	666	20125941991	20171124	80765061	FA	028800259744	00000206170	00000000200	00000004123	3	2017-12-05 15:57:19.680123
014	667	11111111111	20171124	80765063	FA	028800259750	00000121987	00000000600	00000007319	3	2017-12-05 15:57:19.680123
014	668	11111111111	20171124	80765064	FA	029100167729	00000309574	00000000600	00000018574	3	2017-12-05 15:57:19.680123
014	669	11111111111	20171124	80765065	FA	029100167730	00000101442	00000000600	00000006086	3	2017-12-05 15:57:19.680123
014	670	11111111111	20171124	80765066	FA	029100167731	00000008295	00000000600	00000000498	3	2017-12-05 15:57:19.680123
014	671	27066634561	20171124	80765068	FA	029100167734	00000051104	00000000200	00000001022	3	2017-12-05 15:57:19.680123
014	672	27302090977	20171124	80304130	FA	028800094808	00000290969	00000000200	00000005820	3	2017-12-05 15:57:19.680123
014	673	27302090977	20171124	80304131	FA	028800094809	00000093208	00000000200	00000001864	3	2017-12-05 15:57:19.680123
014	674	27066634561	20171124	80765070	FA	029100167735	00000029004	00000000200	00000000580	3	2017-12-05 15:57:19.680123
014	675	23289795464	20171124	80304134	FA	028800094812	00000283111	00000000200	00000005662	3	2017-12-05 15:57:19.680123
014	676	23289795464	20171124	80304135	FA	028800094813	00000023130	00000000200	00000000463	3	2017-12-05 15:57:19.680123
014	677	23289795464	20171124	80304136	FA	028800094814	00000131636	00000000200	00000002633	3	2017-12-05 15:57:19.680123
014	678	20125943250	20171124	80765078	FA	028800259766	00000384058	00000000200	00000007681	3	2017-12-05 15:57:19.680123
014	679	20125943250	20171124	80765083	FA	028800259771	00000086321	00000000200	00000001726	3	2017-12-05 15:57:19.680123
014	680	11111111111	20171124	09892726	FA	028800259772	00000077404	00000000600	00000004644	3	2017-12-05 15:57:19.680123
014	681	20241411436	20171125	09892736	FA	028800259820	00000320748	00000000200	00000006415	3	2017-12-05 15:57:19.680123
014	682	20241411436	20171125	09892737	FA	028800259822	00000416780	00000000200	00000008336	3	2017-12-05 15:57:19.680123
014	683	20241411436	20171125	00986362	NC	028800012091	-0000032184	00000000200	-0000000644	3	2017-12-05 15:57:19.680123
014	684	20076155497	20171125	09824020	FA	028800094846	00000221185	00000000200	00000004424	3	2017-12-05 15:57:19.680123
014	685	20076155497	20171125	80304155	FA	028800094847	00000005170	00000000200	00000000103	3	2017-12-05 15:57:19.680123
003	256	27952625514	20171110	00026363	FA	001500026363	00000649977	00000000200	00000013000	7	2017-12-05 18:44:23.476865
003	257	27222713140	20171110	00026364	FA	001500026364	00000286193	00000000200	00000005724	7	2017-12-05 18:44:23.476865
003	258	27361302759	20171110	00025966	FA	001500025966	00000014845	00000000200	00000000297	7	2017-12-05 18:44:23.476865
003	259	20118857020	20171110	00025967	FA	001500025967	00000074701	00000000200	00000001494	7	2017-12-05 18:44:23.476865
003	260	27266554740	20171110	00025968	FA	001500025968	00000063848	00000000200	00000001277	7	2017-12-05 18:44:23.476865
003	261	20225250473	20171110	00025969	FA	001500025969	00000027182	00000000200	00000000544	7	2017-12-05 18:44:23.476865
014	686	11111111111	20171125	80765111	FA	028800259836	00000226273	00000000600	00000013576	3	2017-12-05 15:57:19.680123
014	687	20274035170	20171125	80304162	FA	028800094855	00000075801	00000000200	00000001516	3	2017-12-05 15:57:19.680123
014	688	30709978248	20171125	80304170	FA	028800094869	00000560106	00000000200	00000011203	3	2017-12-05 15:57:19.680123
014	689	11111111111	20171125	09892756	FA	028800259877	00000272439	00000000600	00000016346	3	2017-12-05 15:57:19.680123
014	690	30709978248	20171125	80304173	FA	028800094872	00000034049	00000000200	00000000681	3	2017-12-05 15:57:19.680123
014	691	27926131864	20171125	80765168	FA	028800259994	00000456151	00000000200	00000009123	3	2017-12-05 15:57:19.680123
014	692	11111111111	20171125	80765169	FA	028800259991	00000040560	00000000600	00000002434	3	2017-12-05 15:57:19.680123
014	693	27185951184	20171125	80765176	FA	028800260008	00000172383	00000000200	00000003448	3	2017-12-05 15:57:19.680123
014	694	20253240653	20171125	80765178	FA	028800260019	00000101037	00000000200	00000002021	3	2017-12-05 15:57:19.680123
014	695	20253240653	20171125	09892793	FA	028800260020	00000101037	00000000200	00000002021	3	2017-12-05 15:57:19.680123
014	696	20253240653	20171125	08064421	NC	028800012099	-0000101037	00000000200	-0000002021	3	2017-12-05 15:57:19.680123
014	697	27173955249	20171125	80765180	FA	028800260028	00000075441	00000000200	00000001509	3	2017-12-05 15:57:19.680123
014	698	27173955249	20171125	80765182	FA	028800260029	00000325837	00000000200	00000006517	3	2017-12-05 15:57:19.680123
003	262	27112378257	20171110	00025970	FA	001500025970	00000054396	00000000200	00000001088	7	2017-12-05 18:44:23.476865
003	263	27346653502	20171110	00025971	FA	001500025971	00000271991	00000000200	00000005440	7	2017-12-05 18:44:23.476865
003	264	27219273431	20171110	00025972	FA	001500025972	00000086030	00000000200	00000001721	7	2017-12-05 18:44:23.476865
003	265	20173955449	20171110	00025973	FA	001500025973	00000093228	00000000200	00000001865	7	2017-12-05 18:44:23.476865
003	266	27216609528	20171110	00016374	FA	001900016374	00000074173	00000000200	00000001483	7	2017-12-05 18:44:23.476865
003	267	27216609528	20171110	00016375	FA	001900016375	00000020736	00000000200	00000000415	7	2017-12-05 18:44:23.476865
003	268	27367571123	20171111	00005569	FA	001800005569	00000081113	00000000200	00000001622	7	2017-12-05 18:44:23.476865
003	269	27952625514	20171111	00005570	FA	001800005570	00000082950	00000000200	00000001659	7	2017-12-05 18:44:23.476865
003	270	20149833510	20171111	00006724	FA	001800006724	00000013367	00000000200	00000000267	7	2017-12-05 18:44:23.476865
003	271	27297722927	20171111	00006725	FA	001800006725	00000073708	00000000200	00000001474	7	2017-12-05 18:44:23.476865
003	272	20101553419	20171111	00020486	FA	001700020486	00000094545	00000000200	00000001891	7	2017-12-05 18:44:23.476865
003	273	27316072483	20171111	00026381	FA	001500026381	00000115691	00000000200	00000002314	7	2017-12-05 18:44:23.476865
003	274	20274035170	20171111	00026382	FA	001500026382	00000042718	00000000200	00000000854	7	2017-12-05 18:44:23.476865
003	275	27125944707	20171111	00026383	FA	001500026383	00000018223	00000000200	00000000364	7	2017-12-05 18:44:23.476865
003	276	23289795464	20171111	00026384	FA	001500026384	00000242664	00000000200	00000004853	7	2017-12-05 18:44:23.476865
003	277	23331976199	20171111	00025981	FA	001500025981	00000013915	00000000200	00000000278	7	2017-12-05 18:44:23.476865
003	278	27942269566	20171111	00025982	FA	001500025982	00000040947	00000000200	00000000819	7	2017-12-05 18:44:23.476865
003	279	27215184760	20171111	00025983	FA	001500025983	00000038888	00000000200	00000000778	7	2017-12-05 18:44:23.476865
003	280	27313340339	20171111	00025984	FA	001500025984	00000036890	00000000200	00000000738	7	2017-12-05 18:44:23.476865
003	281	27297722927	20171111	00025985	FA	001500025985	00000035970	00000000200	00000000719	7	2017-12-05 18:44:23.476865
003	282	20169140236	20171111	00026386	FA	001500026386	00000029877	00000000200	00000000598	7	2017-12-05 18:44:23.476865
003	283	27367571123	20171111	00020487	FA	001700020487	00000183900	00000000200	00000003678	7	2017-12-05 18:44:23.476865
003	284	20274035170	20171111	00020488	FA	001700020488	00000034070	00000000200	00000000681	7	2017-12-05 18:44:23.476865
003	285	23289795464	20171111	00020489	FA	001700020489	00000139299	00000000200	00000002786	7	2017-12-05 18:44:23.476865
003	286	27297722927	20171111	00020379	FA	001700020379	00000055559	00000000200	00000001111	7	2017-12-05 18:44:23.476865
003	287	27952625514	20171111	00020490	FA	001700020490	00002100864	00000000200	00000042017	7	2017-12-05 18:44:23.476865
003	288	20261719887	20171111	00026389	FA	001500026389	00000108171	00000000200	00000002163	7	2017-12-05 18:44:23.476865
003	289	20273644912	20171111	00016412	FA	001900016412	00000062587	00000000200	00000001252	7	2017-12-05 18:44:23.476865
003	290	20367604302	20171111	00016424	FA	001900016424	00000089405	00000000200	00000001788	7	2017-12-05 18:44:23.476865
003	291	27363206641	20171111	00016428	FA	001900016428	00000341302	00000000200	00000006826	7	2017-12-05 18:44:23.476865
003	292	20266077719	20171111	00008185	FA	001900008185	00000036759	00000000200	00000000735	7	2017-12-05 18:44:23.476865
003	293	20226158074	20171113	00016454	FA	001900016454	00000030219	00000000200	00000000604	7	2017-12-05 18:44:23.476865
003	294	20243042403	20171113	00016471	FA	001900016471	00000032638	00000000200	00000000653	7	2017-12-05 18:44:23.476865
003	295	27343370224	20171113	00016479	FA	001900016479	00000102747	00000000200	00000002055	7	2017-12-05 18:44:23.476865
003	296	23241216349	20171113	00008207	FA	001900008207	00000090437	00000000200	00000001809	7	2017-12-05 18:44:23.476865
003	297	20261719887	20171114	00020549	FA	001700020549	00000124610	00000000200	00000002492	7	2017-12-05 18:44:23.476865
003	298	27219273431	20171114	00020439	FA	001700020439	00000019815	00000000200	00000000396	7	2017-12-05 18:44:23.476865
003	299	20054139285	20171114	00026452	FA	001500026452	00000727370	00000000200	00000014547	7	2017-12-05 18:44:23.476865
003	300	20310074587	20171114	00008215	FA	001900008215	00000166873	00000000200	00000003337	7	2017-12-05 18:44:23.476865
003	301	27367571123	20171114	00020550	FA	001700020550	00000085123	00000000200	00000001702	7	2017-12-05 18:44:23.476865
003	302	27100281819	20171114	00020551	FA	001700020551	00000013336	00000000200	00000000267	7	2017-12-05 18:44:23.476865
003	303	27952625514	20171114	00020552	FA	001700020552	00000383310	00000000200	00000007666	7	2017-12-05 18:44:23.476865
003	304	23163173824	20171114	00020553	FA	001700020553	00000025048	00000000200	00000000501	7	2017-12-05 18:44:23.476865
003	305	27361302759	20171114	00020432	FA	001700020432	00000106716	00000000200	00000002134	7	2017-12-05 18:44:23.476865
003	306	23141622099	20171114	00020433	FA	001700020433	00000103685	00000000200	00000002074	7	2017-12-05 18:44:23.476865
003	307	27346653502	20171114	00020436	FA	001700020436	00000155907	00000000200	00000003118	7	2017-12-05 18:44:23.476865
003	308	20113547198	20171114	00020437	FA	001700020437	00000009907	00000000200	00000000198	7	2017-12-05 18:44:23.476865
003	309	20266077719	20171114	00008216	FA	001900008216	00000025540	00000000200	00000000511	7	2017-12-05 18:44:23.476865
003	310	27342957760	20171114	00016536	FA	001900016536	00000035898	00000000200	00000000718	7	2017-12-05 18:44:23.476865
003	311	27342957760	20171114	00016539	FA	001900016539	00000049270	00000000200	00000000985	7	2017-12-05 18:44:23.476865
003	312	27342957760	20171114	00026057	FA	001500026057	00000071077	00000000200	00000001422	7	2017-12-05 18:44:23.476865
003	313	20261719887	20171114	00026459	FA	001500026459	00000212015	00000000200	00000004240	7	2017-12-05 18:44:23.476865
003	314	20305781720	20171114	00026460	FA	001500026460	00000585411	00000000200	00000011708	7	2017-12-05 18:44:23.476865
003	315	27056603560	20171114	00026461	FA	001500026461	00000080646	00000000200	00000001613	7	2017-12-05 18:44:23.476865
003	316	27367571123	20171114	00026462	FA	001500026462	00000032060	00000000200	00000000641	7	2017-12-05 18:44:23.476865
003	317	27064350809	20171114	00026463	FA	001500026463	00000084189	00000000200	00000001684	7	2017-12-05 18:44:23.476865
003	318	27222713140	20171114	00026464	FA	001500026464	00000085172	00000000200	00000001703	7	2017-12-05 18:44:23.476865
003	319	20173955740	20171114	00026465	FA	001500026465	00000039004	00000000200	00000000780	7	2017-12-05 18:44:23.476865
003	320	20266077719	20171114	00026466	FA	001500026466	00000108421	00000000200	00000002168	7	2017-12-05 18:44:23.476865
003	321	27100281819	20171114	00026467	FA	001500026467	00000072128	00000000200	00000001443	7	2017-12-05 18:44:23.476865
003	322	20213541421	20171114	00026468	FA	001500026468	00000215680	00000000200	00000004314	7	2017-12-05 18:44:23.476865
003	323	27952625514	20171114	00026469	FA	001500026469	00000409414	00000000200	00000008188	7	2017-12-05 18:44:23.476865
003	324	23163173824	20171114	00026470	FA	001500026470	00000031441	00000000200	00000000629	7	2017-12-05 18:44:23.476865
003	325	27361302759	20171114	00026058	FA	001500026058	00000275430	00000000200	00000005509	7	2017-12-05 18:44:23.476865
003	326	27219273431	20171114	00026059	FA	001500026059	00000085576	00000000200	00000001712	7	2017-12-05 18:44:23.476865
003	327	27346653502	20171114	00026061	FA	001500026061	00000133545	00000000200	00000002671	7	2017-12-05 18:44:23.476865
003	328	23141622099	20171114	00026063	FA	001500026063	00000101018	00000000200	00000002020	7	2017-12-05 18:44:23.476865
003	329	30670364778	20171114	00026064	FA	001500026064	00000027873	00000000200	00000000557	7	2017-12-05 18:44:23.476865
003	330	20173955449	20171114	00026065	FA	001500026065	00000080781	00000000200	00000001616	7	2017-12-05 18:44:23.476865
003	331	27259753274	20171115	00016574	FA	001900016574	00000138123	00000000200	00000002762	7	2017-12-05 18:44:23.476865
003	332	20274035170	20171115	00026525	FA	001500026525	00000026458	00000000200	00000000529	7	2017-12-05 18:44:23.476865
003	333	23289795464	20171115	00026526	FA	001500026526	00000027573	00000000200	00000000551	7	2017-12-05 18:44:23.476865
003	334	20273644912	20171115	00026102	FA	001500026102	00000012809	00000000200	00000000256	7	2017-12-05 18:44:23.476865
003	335	27297722927	20171115	00026103	FA	001500026103	00000010573	00000000200	00000000211	7	2017-12-05 18:44:23.476865
003	336	27163184465	20171115	00026104	FA	001500026104	00000168066	00000000200	00000003361	7	2017-12-05 18:44:23.476865
003	337	27163184465	20171115	00020471	FA	001700020471	00000050067	00000000200	00000001001	7	2017-12-05 18:44:23.476865
003	338	20054139285	20171115	00020594	FA	001700020594	00000098916	00000000200	00000001978	7	2017-12-05 18:44:23.476865
003	339	20274035170	20171115	00020591	FA	001700020591	00000028392	00000000200	00000000568	7	2017-12-05 18:44:23.476865
003	340	20261719887	20171115	00005591	FA	001800005591	00000030246	00000000200	00000000605	7	2017-12-05 18:44:23.476865
014	699	20924247410	20171125	09892801	FA	028800260034	00000257476	00000000200	00000005150	3	2017-12-05 15:57:19.680123
014	700	30653645143	20171125	80765206	FA	028800260082	00000993343	00000000200	00000019867	3	2017-12-05 15:57:19.680123
014	701	30653645143	20171125	80765207	FA	028800260084	00000378696	00000000200	00000007574	3	2017-12-05 15:57:19.680123
014	702	11111111111	20171125	09892810	FA	028800260086	00000321926	00000000600	00000019316	3	2017-12-05 15:57:19.680123
014	703	11111111111	20171125	09892811	FA	028800260087	00000058261	00000000600	00000003496	3	2017-12-05 15:57:19.680123
014	704	11111111111	20171125	80765208	FA	028800260088	00000196640	00000000600	00000011798	3	2017-12-05 15:57:19.680123
014	705	11111111111	20171125	80765209	FA	028800260089	00000036977	00000000600	00000002219	3	2017-12-05 15:57:19.680123
014	706	27101402156	20171125	80765213	FA	028800260105	00000419056	00000000200	00000008381	3	2017-12-05 15:57:19.680123
014	707	23241216349	20171125	80304207	FA	028800094941	00000627007	00000000200	00000012540	3	2017-12-05 15:57:19.680123
014	708	11111111111	20171125	09892818	FA	028800260110	00000033699	00000000600	00000002022	3	2017-12-05 15:57:19.680123
014	709	11111111111	20171125	09892819	FA	028800260117	00000197287	00000000600	00000011837	3	2017-12-05 15:57:19.680123
014	710	24248899286	20171125	09824034	FA	028800094946	00000198904	00000000200	00000003978	3	2017-12-05 15:57:19.680123
014	711	24248899286	20171125	80304208	FA	028800094949	00000570255	00000000200	00000011406	3	2017-12-05 15:57:19.680123
014	712	27141080534	20171127	80765227	FA	029100167742	00000049682	00000000200	00000000994	3	2017-12-05 15:57:19.680123
014	713	23138145549	20171127	80765234	FA	029100167754	00000622781	00000000200	00000012456	3	2017-12-05 15:57:19.680123
014	714	11111111111	20171127	09892823	FA	029100167756	00000144097	00000000600	00000008646	3	2017-12-05 15:57:19.680123
014	715	23138145549	20171127	80765236	FA	029100167759	00000539474	00000000200	00000010790	3	2017-12-05 15:57:19.680123
014	716	23141622099	20171127	80765238	FA	028800260125	00000076257	00000000200	00000001525	3	2017-12-05 15:57:19.680123
014	717	23141622099	20171127	80765239	FA	029100167762	00000028197	00000000200	00000000564	3	2017-12-05 15:57:19.680123
014	718	23141622099	20171127	80765240	FA	028800260126	00000022687	00000000200	00000000454	3	2017-12-05 15:57:19.680123
014	719	20266077719	20171127	80304213	FA	029100066788	00000042639	00000000200	00000000853	3	2017-12-05 15:57:19.680123
014	720	11111111111	20171127	09892824	FA	029100167771	00000496836	00000000600	00000029810	3	2017-12-05 15:57:19.680123
014	721	20113547198	20171127	80765247	FA	029100167772	00000133802	00000000200	00000002676	3	2017-12-05 15:57:19.680123
014	722	11111111111	20171127	09892825	FA	029100167777	00000636137	00000000600	00000038168	3	2017-12-05 15:57:19.680123
014	723	20073261784	20171127	80304219	FA	028800094959	00000367834	00000000200	00000007357	3	2017-12-05 15:57:19.680123
014	724	30670371359	20171127	09824045	FA	028800094964	00000149404	00000000200	00000002988	3	2017-12-05 15:57:19.680123
003	341	27367571123	20171115	00005592	FA	001800005592	00000023723	00000000200	00000000474	7	2017-12-05 18:44:23.476865
003	342	30670364603	20171115	00005593	FA	001800005593	00000037936	00000000200	00000000759	7	2017-12-05 18:44:23.476865
003	343	27952625514	20171115	00005594	FA	001800005594	00000057690	00000000200	00000001154	7	2017-12-05 18:44:23.476865
003	344	27394430655	20171115	00006756	FA	001800006756	00000052148	00000000200	00000001043	7	2017-12-05 18:44:23.476865
003	345	27297722927	20171115	00006757	FA	001800006757	00000067029	00000000200	00000001341	7	2017-12-05 18:44:23.476865
003	346	27259753274	20171115	00008239	FA	001900008239	00000120104	00000000200	00000002402	7	2017-12-05 18:44:23.476865
001	11	02731607248	20171012	00001664	FA	110000001664	-0000504321	00000000200	-0000010087	5	2017-11-16 12:54:35.10011
001	12	03070904243	20171014	00001676	FA	110000001676	-0000270451	00000000200	-0000005409	5	2017-11-16 12:54:35.10011
001	13	02005413928	20171018	00001693	FA	110000001693	-0000457428	00000000200	-0000009148	5	2017-11-16 12:54:35.10011
001	14	02027403517	20171025	00001714	FA	110000001714	-0000037195	00000000200	-0000000743	5	2017-11-16 12:54:35.10011
001	15	02027403517	20171025	00001715	FA	110000001715	-0000476439	00000000200	-0000009529	5	2017-11-16 12:54:35.10011
001	16	03070904243	20171025	00001718	FA	110000001718	-0000049383	00000000200	-0000000988	5	2017-11-16 12:54:35.10011
001	17	02022201457	20171027	00001722	FA	110000001722	-0000094623	00000000200	-0000001893	5	2017-11-16 12:54:35.10011
001	18	02022201457	20171027	00001723	FA	110000001723	-0000169263	00000000200	-0000003386	5	2017-11-16 12:54:35.10011
001	19	02022201457	20171027	00001724	FA	110000001724	-0000107466	00000000200	-0000002150	5	2017-11-16 12:54:35.10011
001	20	02014983351	20171007	00001324	FA	110000001324	-0000054355	00000000200	-0000001087	5	2017-11-16 12:54:35.10011
001	21	02034665318	20171010	00001330	FA	110000001330	-0000021042	00000000200	-0000000421	5	2017-11-16 12:54:35.10011
001	22	02716318446	20171011	00001331	FA	110000001331	-0000877401	00000000200	-0000017550	5	2017-11-16 12:54:35.10011
001	23	02721927343	20171012	00001336	FA	110000001336	-0000027360	00000000200	-0000000547	5	2017-11-16 12:54:35.10011
001	24	02094314994	20171014	00001350	FA	110000001350	-0000196594	00000000200	-0000003932	5	2017-11-16 12:54:35.10011
001	25	02314162209	20171018	00001356	FA	110000001356	-0000524105	00000000200	-0000010481	5	2017-11-16 12:54:35.10011
001	26	02704492010	20171018	00001357	FA	110000001357	-0000190361	00000000200	-0000003806	5	2017-11-16 12:54:35.10011
001	27	20011994378	20171018	00001358	FA	110000001358	-0000314415	00000000200	-0000006288	5	2017-11-16 12:54:35.10011
001	28	02716318446	20171025	00001379	FA	110000001379	-0000072978	00000000200	-0000001460	5	2017-11-16 12:54:35.10011
001	29	02094314994	20171027	00001390	FA	110000001390	-0000524869	00000000200	-0000010497	5	2017-11-16 12:54:35.10011
001	30	02011885702	20171030	00001392	FA	110000001392	-0000104821	00000000200	-0000002097	5	2017-11-16 12:54:35.10011
001	31	02722271314	20171012	00002179	FA	120000002179	00000027360	00000000200	00000000547	5	2017-11-16 12:54:35.10011
001	32	02005413928	20171012	00002183	FA	120000002183	00000295828	00000000200	00000005917	5	2017-11-16 12:54:35.10011
001	33	02706435080	20171014	00002192	FA	120000002192	00000142329	00000000200	00000002846	5	2017-11-16 12:54:35.10011
001	34	02728019123	20171014	00002193	FA	120000002193	00000232773	00000000200	00000004655	5	2017-11-16 12:54:35.10011
001	35	02007326180	20171014	00002194	FA	120000002194	00000038904	00000000200	00000000778	5	2017-11-16 12:54:35.10011
001	36	03070904243	20171014	00002195	FA	120000002195	00000048419	00000000200	00000000968	5	2017-11-16 12:54:35.10011
001	37	02324121634	20171017	00002211	FA	120000002211	00000446444	00000000200	00000008929	5	2017-11-16 12:54:35.10011
001	38	02705660356	20171025	00002277	FA	120000002277	00000306677	00000000200	00000006135	5	2017-11-16 12:54:35.10011
001	39	02722271314	20171025	00002278	FA	120000002278	00001908506	00000000200	00000038170	5	2017-11-16 12:54:35.10011
001	40	02021354142	20171025	00002279	FA	120000002279	00000258024	00000000200	00000005161	5	2017-11-16 12:54:35.10011
001	41	02710028181	20171025	00002280	FA	120000002280	00000130628	00000000200	00000002612	5	2017-11-16 12:54:35.10011
001	42	02022201457	20171026	00002282	FA	120000002282	00000107466	00000000200	00000002150	5	2017-11-16 12:54:35.10011
001	43	02022201457	20171026	00002283	FA	120000002283	00000094623	00000000200	00000001893	5	2017-11-16 12:54:35.10011
001	44	02731607248	20171028	00002289	FA	120000002289	00000029575	00000000200	00000000592	5	2017-11-16 12:54:35.10011
001	45	02732545346	20171011	00002105	FA	120000002105	00000152054	00000000200	00000003041	5	2017-11-16 12:54:35.10011
001	46	02094314994	20171014	00002125	FA	120000002125	00000206060	00000000200	00000004121	5	2017-11-16 12:54:35.10011
001	47	02729772292	20171014	00002126	FA	120000002126	00000125195	00000000200	00000002504	5	2017-11-16 12:54:35.10011
015	2	27952625514	20171003	00000001	FA	002500006573	00000135632	00000000200	00000002713	18	2017-11-13 13:11:11.80638
015	3	27394430655	20171003	00000001	FA	002500006692	00000019198	00000000200	00000000384	18	2017-11-13 13:11:11.80638
015	4	20261719887	20171003	00000001	FA	002500006583	00000046754	00000000200	00000000935	18	2017-11-13 13:11:11.80638
015	5	23241216349	20171003	00000001	FA	002500006584	00000053151	00000000200	00000001063	18	2017-11-13 13:11:11.80638
015	6	20305781720	20171003	00000001	FA	002500006589	00000024630	00000000200	00000000493	18	2017-11-13 13:11:11.80638
015	7	27952625514	20171003	00000001	FA	002500006566	00000087367	00000000200	00000001747	18	2017-11-13 13:11:11.80638
015	8	27125944707	20171003	00000001	FA	002500006578	00000028626	00000000200	00000000573	18	2017-11-13 13:11:11.80638
015	9	27952625514	20171003	00000001	FA	002500000643	-0000005296	00000000200	-0000000106	18	2017-11-13 13:11:11.80638
015	10	27316539497	20171003	00000001	FA	002500006687	00000069534	00000000200	00000001391	18	2017-11-13 13:11:11.80638
015	11	27952625514	20171003	00000001	FA	002500006568	00000118487	00000000200	00000002370	18	2017-11-13 13:11:11.80638
015	12	27297722927	20171003	00000001	FA	002500006684	00000074898	00000000200	00000001498	18	2017-11-13 13:11:11.80638
015	13	23289795464	20171003	00000001	FA	002500006586	00000138374	00000000200	00000002767	18	2017-11-13 13:11:11.80638
015	14	23163173824	20171003	00000001	FA	002500006577	00000042626	00000000200	00000000853	18	2017-11-13 13:11:11.80638
015	15	20054139285	20171003	00000001	FA	002500006580	00000110048	00000000200	00000002201	18	2017-11-13 13:11:11.80638
015	16	20273644912	20171003	00000001	FA	002500006685	00000012381	00000000200	00000000248	18	2017-11-13 13:11:11.80638
015	17	27173955451	20171003	00000001	FA	002500006587	00000039190	00000000200	00000000784	18	2017-11-13 13:11:11.80638
015	18	27952625514	20171003	00000001	FA	002500006570	00000223425	00000000200	00000004469	18	2017-11-13 13:11:11.80638
015	19	20054139285	20171003	00000001	FA	002500006582	00000082418	00000000200	00000001648	18	2017-11-13 13:11:11.80638
015	20	27952625514	20171003	00000001	FA	002500006572	00000105843	00000000200	00000002117	18	2017-11-13 13:11:11.80638
015	21	20305781720	20171003	00000001	FA	002500006588	00000057869	00000000200	00000001157	18	2017-11-13 13:11:11.80638
015	22	23289795464	20171003	00000001	FA	002500006576	00000037310	00000000200	00000000746	18	2017-11-13 13:11:11.80638
015	23	27044920102	20171003	00000001	FA	002500006686	00000035770	00000000200	00000000715	18	2017-11-13 13:11:11.80638
015	24	27952625514	20171003	00000001	FA	002500000642	-0000079642	00000000200	-0000001593	18	2017-11-13 13:11:11.80638
015	25	20082698508	20171003	00000001	FA	002500006683	00000008177	00000000200	00000000164	18	2017-11-13 13:11:11.80638
015	26	30670362317	20171003	00000001	FA	002500006575	00000022936	00000000200	00000000459	18	2017-11-13 13:11:11.80638
015	27	20274035170	20171003	00000001	FA	002500006585	00000058266	00000000200	00000001165	18	2017-11-13 13:11:11.80638
015	28	27313340339	20171003	00000001	FA	002500006691	00000033060	00000000200	00000000661	18	2017-11-13 13:11:11.80638
015	29	27952625514	20171003	00000001	FA	002500006567	00000264632	00000000200	00000005293	18	2017-11-13 13:11:11.80638
015	30	27952625514	20171003	00000001	FA	002500000644	-0000008249	00000000200	-0000000165	18	2017-11-13 13:11:11.80638
015	31	20054139285	20171003	00000001	FA	002500006579	00000150336	00000000200	00000003007	18	2017-11-13 13:11:11.80638
015	32	27316539497	20171003	00000001	FA	002500006688	00000034406	00000000200	00000000688	18	2017-11-13 13:11:11.80638
015	33	27305781466	20171003	00000001	FA	002500006690	00000009055	00000000200	00000000181	18	2017-11-13 13:11:11.80638
015	34	27952625514	20171003	00000001	FA	002500006569	00000176431	00000000200	00000003529	18	2017-11-13 13:11:11.80638
015	35	27363206641	20171003	00000001	FA	002500006689	00000078007	00000000200	00000001560	18	2017-11-13 13:11:11.80638
015	36	27064350809	20171003	00000001	FA	002500006574	00000020440	00000000200	00000000409	18	2017-11-13 13:11:11.80638
015	37	20054139285	20171003	00000001	FA	002500006581	00000183489	00000000200	00000003670	18	2017-11-13 13:11:11.80638
014	725	20169989592	20171127	80765267	FA	028800260138	00000031918	00000000200	00000000638	3	2017-12-05 15:57:19.680123
014	726	20253240653	20171127	80765303	FA	029100167866	00000375124	00000000200	00000007502	3	2017-12-05 15:57:19.680123
014	727	27111449509	20171127	09892856	FA	028800260158	00000602634	00000000200	00000012053	3	2017-12-05 15:57:19.680123
014	728	20083968436	20171127	80304240	FA	028800094974	00000021645	00000000200	00000000433	3	2017-12-05 15:57:19.680123
014	729	11111111111	20171127	80765308	FA	028800260170	00000002468	00000000600	00000000148	3	2017-12-05 15:57:19.680123
014	730	23148113149	20171127	80304241	FA	028800094975	00000066878	00000000200	00000001338	3	2017-12-05 15:57:19.680123
014	731	30647748739	20171128	09892870	FA	029100167878	00000118917	00000000200	00000002378	3	2017-12-05 15:57:19.680123
015	38	27173955451	20171003	00000001	FA	002500000645	-0000003367	00000000200	-0000000067	18	2017-11-13 13:11:11.80638
015	39	27952625514	20171003	00000001	FA	002500006571	00000053368	00000000200	00000001067	18	2017-11-13 13:11:11.80638
015	40	27952625514	20171006	00000001	FA	002500006639	00000267726	00000000200	00000005355	18	2017-11-13 13:11:11.80638
015	41	27363206641	20171007	00000001	FA	002500006812	00000082880	00000000200	00000001658	18	2017-11-13 13:11:11.80638
015	42	27367571123	20171007	00000001	FA	002500006712	00000016576	00000000200	00000000332	18	2017-11-13 13:11:11.80638
015	43	20225250473	20171007	00000001	FA	002500006815	00000029840	00000000200	00000000597	18	2017-11-13 13:11:11.80638
015	44	27952625514	20171007	00000001	FA	002500006687	00000179846	00000000200	00000003597	18	2017-11-13 13:11:11.80638
015	45	27219273431	20171007	00000001	FA	002500006807	00000029840	00000000200	00000000597	18	2017-11-13 13:11:11.80638
015	46	20054139285	20171007	00000001	FA	002500006697	00000237431	00000000200	00000004749	18	2017-11-13 13:11:11.80638
015	47	20213549619	20171007	00000001	FA	002500006813	00000040179	00000000200	00000000804	18	2017-11-13 13:11:11.80638
015	48	27173955451	20171007	00000001	FA	002500006711	00000029840	00000000200	00000000597	18	2017-11-13 13:11:11.80638
015	49	27222713140	20171007	00000001	FA	002500006718	00000026622	00000000200	00000000532	18	2017-11-13 13:11:11.80638
015	50	27222713140	20171007	00000001	FA	002500006690	00000029840	00000000200	00000000597	18	2017-11-13 13:11:11.80638
015	51	27200953946	20171007	00000001	FA	002500006809	00000053040	00000000200	00000001061	18	2017-11-13 13:11:11.80638
015	52	20213541421	20171007	00000001	FA	002500006702	00000006220	00000000200	00000000124	18	2017-11-13 13:11:11.80638
015	53	27361302759	20171007	00000001	FA	002500006819	00000188960	00000000200	00000003779	18	2017-11-13 13:11:11.80638
015	54	23289795464	20171007	00000001	FA	002500006691	00000016731	00000000200	00000000335	18	2017-11-13 13:11:11.80638
015	55	27044920102	20171007	00000001	FA	002500006851	00000046150	00000000200	00000000923	18	2017-11-13 13:11:11.80638
015	56	27266554740	20171007	00000001	FA	002500006817	00000029840	00000000200	00000000597	18	2017-11-13 13:11:11.80638
015	57	27346653502	20171007	00000001	FA	002500006821	00000029840	00000000200	00000000597	18	2017-11-13 13:11:11.80638
015	58	27056603560	20171007	00000001	FA	002500006736	00000102706	00000000200	00000002054	18	2017-11-13 13:11:11.80638
015	59	20274035170	20171007	00000001	FA	002500006705	00000059681	00000000200	00000001194	18	2017-11-13 13:11:11.80638
015	60	20305781720	20171007	00000001	FA	002500006716	00000008566	00000000200	00000000171	18	2017-11-13 13:11:11.80638
015	61	27950274226	20171007	00000001	FA	002500006853	00000047998	00000000200	00000000960	18	2017-11-13 13:11:11.80638
015	62	27125944707	20171007	00000001	FA	002500006696	00000029840	00000000200	00000000597	18	2017-11-13 13:11:11.80638
015	63	27316539497	20171007	00000001	FA	002500006852	00000018960	00000000200	00000000379	18	2017-11-13 13:11:11.80638
015	64	30670364603	20171007	00000001	FA	002500006699	00000028760	00000000200	00000000575	18	2017-11-13 13:11:11.80638
015	65	27225092570	20171007	00000001	FA	002500006704	00000029840	00000000200	00000000597	18	2017-11-13 13:11:11.80638
015	66	23289795464	20171007	00000001	FA	002500006707	00000029840	00000000200	00000000597	18	2017-11-13 13:11:11.80638
015	67	27363206641	20171007	00000001	FA	002500006811	00000011845	00000000200	00000000237	18	2017-11-13 13:11:11.80638
015	68	27222713140	20171007	00000001	FA	002500006709	00000029840	00000000200	00000000597	18	2017-11-13 13:11:11.80638
015	69	20225250473	20171007	00000001	FA	002500006814	00000012195	00000000200	00000000244	18	2017-11-13 13:11:11.80638
015	70	27952625514	20171007	00000001	FA	002500006686	00000199314	00000000200	00000003986	18	2017-11-13 13:11:11.80638
015	71	27219273431	20171007	00000001	FA	002500006806	00000010770	00000000200	00000000215	18	2017-11-13 13:11:11.80638
015	72	27064350809	20171007	00000001	FA	002500006734	00000052581	00000000200	00000001052	18	2017-11-13 13:11:11.80638
015	73	30714924644	20171007	00000001	FA	002500006810	00000034185	00000000200	00000000684	18	2017-11-13 13:11:11.80638
015	74	27173955451	20171007	00000001	FA	002500006710	00000041934	00000000200	00000000839	18	2017-11-13 13:11:11.80638
015	75	27222713140	20171007	00000001	FA	002500006689	00000056724	00000000200	00000001134	18	2017-11-13 13:11:11.80638
015	76	27200953946	20171007	00000001	FA	002500006808	00000053327	00000000200	00000001067	18	2017-11-13 13:11:11.80638
015	77	30670364603	20171007	00000001	FA	002500006701	00000023300	00000000200	00000000466	18	2017-11-13 13:11:11.80638
015	78	27367571123	20171007	00000001	FA	002500006713	00000106080	00000000200	00000002122	18	2017-11-13 13:11:11.80638
015	79	27952625514	20171007	00000001	FA	002500006688	00000106320	00000000200	00000002126	18	2017-11-13 13:11:11.80638
015	80	20054139285	20171007	00000001	FA	002500000664	-0000013108	00000000200	-0000000262	18	2017-11-13 13:11:11.80638
015	81	20213549619	20171007	00000001	FA	002500000422	-0000040179	00000000200	-0000000804	18	2017-11-13 13:11:11.80638
015	82	27266554740	20171007	00000001	FA	002500006816	00000021572	00000000200	00000000431	18	2017-11-13 13:11:11.80638
015	83	27346653502	20171007	00000001	FA	002500006820	00000068473	00000000200	00000001369	18	2017-11-13 13:11:11.80638
015	84	27394430655	20171007	00000001	FA	002500006822	00000030764	00000000200	00000000615	18	2017-11-13 13:11:11.80638
015	85	27100281819	20171007	00000001	FA	002500006693	00000067824	00000000200	00000001356	18	2017-11-13 13:11:11.80638
015	86	20213541421	20171007	00000001	FA	002500006703	00000059680	00000000200	00000001194	18	2017-11-13 13:11:11.80638
015	87	20261719887	20171007	00000001	FA	002500006737	00000103680	00000000200	00000002074	18	2017-11-13 13:11:11.80638
015	88	20305781720	20171007	00000001	FA	002500006715	00000038147	00000000200	00000000763	18	2017-11-13 13:11:11.80638
015	89	23289795464	20171007	00000001	FA	002500006692	00000029840	00000000200	00000000597	18	2017-11-13 13:11:11.80638
015	90	27125944707	20171007	00000001	FA	002500006695	00000042362	00000000200	00000000847	18	2017-11-13 13:11:11.80638
015	91	27044920102	20171007	00000001	FA	002500000423	-0000006116	00000000200	-0000000122	18	2017-11-13 13:11:11.80638
015	92	20305781720	20171007	00000001	FA	002500006717	00000135920	00000000200	00000002718	18	2017-11-13 13:11:11.80638
015	93	30670364603	20171007	00000001	FA	002500006698	00000056103	00000000200	00000001122	18	2017-11-13 13:11:11.80638
015	94	23289795464	20171007	00000001	FA	002500006706	00000028126	00000000200	00000000563	18	2017-11-13 13:11:11.80638
015	95	27222713140	20171007	00000001	FA	002500006708	00000015230	00000000200	00000000305	18	2017-11-13 13:11:11.80638
015	96	27236216638	20171007	00000001	FA	002500006818	00000053040	00000000200	00000001061	18	2017-11-13 13:11:11.80638
015	97	27952625514	20171007	00000001	FA	002500006685	00000228238	00000000200	00000004565	18	2017-11-13 13:11:11.80638
015	98	23163173824	20171007	00000001	FA	002500006694	00000082880	00000000200	00000001658	18	2017-11-13 13:11:11.80638
015	99	20266077719	20171007	00000001	FA	002500006738	00000072340	00000000200	00000001447	18	2017-11-13 13:11:11.80638
015	100	30670364603	20171007	00000001	FA	002500006700	00000097304	00000000200	00000001946	18	2017-11-13 13:11:11.80638
015	101	30670362317	20171009	00000001	FA	002500000670	-0000011763	00000000200	-0000000235	18	2017-11-13 13:11:11.80638
015	102	27222713140	20171010	00000001	FA	002500006794	00000036020	00000000200	00000000720	18	2017-11-13 13:11:11.80638
015	103	27952625514	20171010	00000001	FA	002500000673	-0000003125	00000000200	-0000000063	18	2017-11-13 13:11:11.80638
015	104	30670364603	20171010	00000001	FA	002500006788	00000046769	00000000200	00000000935	18	2017-11-13 13:11:11.80638
015	105	27952625514	20171010	00000001	FA	002500006766	00000094089	00000000200	00000001882	18	2017-11-13 13:11:11.80638
015	106	20054139285	20171010	00000001	FA	002500006784	00000166282	00000000200	00000003326	18	2017-11-13 13:11:11.80638
015	107	27222713140	20171010	00000001	FA	002500000677	-0000023692	00000000200	-0000000474	18	2017-11-13 13:11:11.80638
015	108	20261719887	20171010	00000001	FA	002500006789	00000052781	00000000200	00000001056	18	2017-11-13 13:11:11.80638
015	109	27313340339	20171010	00000001	FA	002500006902	00000030182	00000000200	00000000604	18	2017-11-13 13:11:11.80638
015	110	27952625514	20171010	00000001	FA	002500006768	00000231811	00000000200	00000004636	18	2017-11-13 13:11:11.80638
015	111	23289795464	20171010	00000001	FA	002500006782	00000087372	00000000200	00000001747	18	2017-11-13 13:11:11.80638
015	112	20054139285	20171010	00000001	FA	002500006786	00000200128	00000000200	00000004003	18	2017-11-13 13:11:11.80638
015	113	27044920102	20171010	00000001	FA	002500006897	00000032961	00000000200	00000000659	18	2017-11-13 13:11:11.80638
015	114	20274035170	20171010	00000001	FA	002500000671	-0000013584	00000000200	-0000000272	18	2017-11-13 13:11:11.80638
015	115	20305781720	20171010	00000001	FA	002500006795	00000042350	00000000200	00000000847	18	2017-11-13 13:11:11.80638
015	116	27952625514	20171010	00000001	FA	002500006770	00000184903	00000000200	00000003698	18	2017-11-13 13:11:11.80638
015	117	20271066547	20171010	00000001	FA	002500006900	00000087383	00000000200	00000001748	18	2017-11-13 13:11:11.80638
015	118	27248176607	20171010	00000001	FA	002500006901	00000265198	00000000200	00000005304	18	2017-11-13 13:11:11.80638
015	119	27952625514	20171010	00000001	FA	002500000672	-0000045270	00000000200	-0000000905	18	2017-11-13 13:11:11.80638
015	120	27219273431	20171010	00000001	FA	002500006894	00000025939	00000000200	00000000519	18	2017-11-13 13:11:11.80638
015	121	27222713140	20171010	00000001	FA	002500006780	00000023692	00000000200	00000000474	18	2017-11-13 13:11:11.80638
015	122	30670364603	20171010	00000001	FA	002500000676	-0000011842	00000000200	-0000000237	18	2017-11-13 13:11:11.80638
015	123	23241216349	20171010	00000001	FA	002500006791	00000010205	00000000200	00000000204	18	2017-11-13 13:11:11.80638
015	124	27952625514	20171010	00000001	FA	002500006767	00000265006	00000000200	00000005300	18	2017-11-13 13:11:11.80638
015	125	20054139285	20171010	00000001	FA	002500006785	00000197807	00000000200	00000003956	18	2017-11-13 13:11:11.80638
015	126	30670362317	20171010	00000001	FA	002500006781	00000030883	00000000200	00000000618	18	2017-11-13 13:11:11.80638
015	127	27056603560	20171010	00000001	FA	002500006787	00000086473	00000000200	00000001729	18	2017-11-13 13:11:11.80638
015	128	20261719887	20171010	00000001	FA	002500006790	00000025682	00000000200	00000000514	18	2017-11-13 13:11:11.80638
015	129	20274035170	20171010	00000001	FA	002500006792	00000064095	00000000200	00000001282	18	2017-11-13 13:11:11.80638
015	130	27952625514	20171010	00000001	FA	002500006769	00000296602	00000000200	00000005932	18	2017-11-13 13:11:11.80638
015	131	23289795464	20171010	00000001	FA	002500006783	00000046547	00000000200	00000000931	18	2017-11-13 13:11:11.80638
015	132	27297722927	20171010	00000001	FA	002500006895	00000115723	00000000200	00000002314	18	2017-11-13 13:11:11.80638
015	133	20271066547	20171010	00000001	FA	002500006899	00000025349	00000000200	00000000507	18	2017-11-13 13:11:11.80638
015	134	20273644912	20171010	00000001	FA	002500006896	00000011507	00000000200	00000000230	18	2017-11-13 13:11:11.80638
015	135	27316539497	20171010	00000001	FA	002500006898	00000044361	00000000200	00000000887	18	2017-11-13 13:11:11.80638
015	136	20305781720	20171010	00000001	FA	002500006796	00000077869	00000000200	00000001557	18	2017-11-13 13:11:11.80638
015	137	27952625514	20171010	00000001	FA	002500006771	00000267699	00000000200	00000005354	18	2017-11-13 13:11:11.80638
015	138	23289795464	20171010	00000001	FA	002500006793	00000062266	00000000200	00000001245	18	2017-11-13 13:11:11.80638
015	139	30670364603	20171011	00000001	FA	002500006822	00000011842	00000000200	00000000237	18	2017-11-13 13:11:11.80638
015	140	27248176607	20171014	00000001	FA	002500007004	00000113640	00000000200	00000002273	18	2017-11-13 13:11:11.80638
015	141	27952625514	20171014	00000001	FA	002500006891	00000267726	00000000200	00000005355	18	2017-11-13 13:11:11.80638
015	142	27064350809	20171014	00000001	FA	002500006903	00000040145	00000000200	00000000803	18	2017-11-13 13:11:11.80638
015	143	30714924644	20171014	00000001	FA	002500006999	00000028708	00000000200	00000000574	18	2017-11-13 13:11:11.80638
015	144	20305781720	20171014	00000001	FA	002500006890	00000560237	00000000200	00000011205	18	2017-11-13 13:11:11.80638
015	145	20054139285	20171014	00000001	FA	002500000699	-0000006016	00000000200	-0000000120	18	2017-11-13 13:11:11.80638
015	146	27952625514	20171014	00000001	FA	002500000694	-0000127136	00000000200	-0000002543	18	2017-11-13 13:11:11.80638
015	147	27219273431	20171014	00000001	FA	002500006997	00000112097	00000000200	00000002242	18	2017-11-13 13:11:11.80638
015	148	27188206463	20171014	00000001	FA	002500006881	00000040794	00000000200	00000000816	18	2017-11-13 13:11:11.80638
015	149	27952625514	20171014	00000001	FA	002500006870	00000102879	00000000200	00000002058	18	2017-11-13 13:11:11.80638
015	150	20082698508	20171014	00000001	FA	002500006996	00000040790	00000000200	00000000816	18	2017-11-13 13:11:11.80638
015	151	20213541421	20171014	00000001	FA	002500006884	00000003110	00000000200	00000000062	18	2017-11-13 13:11:11.80638
015	152	27361302759	20171014	00000001	FA	002500000434	-0000033916	00000000200	-0000000678	18	2017-11-13 13:11:11.80638
015	153	27952625514	20171014	00000001	FA	002500000696	-0000002292	00000000200	-0000000046	18	2017-11-13 13:11:11.80638
015	154	27952625514	20171014	00000001	FA	002500006872	00000349436	00000000200	00000006989	18	2017-11-13 13:11:11.80638
015	155	27056603560	20171014	00000001	FA	002500006880	00000077842	00000000200	00000001557	18	2017-11-13 13:11:11.80638
015	156	30670364603	20171014	00000001	FA	002500006883	00000195282	00000000200	00000003906	18	2017-11-13 13:11:11.80638
015	157	20261719887	20171014	00000001	FA	002500006905	00000080725	00000000200	00000001615	18	2017-11-13 13:11:11.80638
015	158	23289795464	20171014	00000001	FA	002500006875	00000097635	00000000200	00000001953	18	2017-11-13 13:11:11.80638
015	159	20054139285	20171014	00000001	FA	002500006878	00000154490	00000000200	00000003090	18	2017-11-13 13:11:11.80638
015	160	27297722927	20171014	00000001	FA	002500007000	00000153945	00000000200	00000003079	18	2017-11-13 13:11:11.80638
015	161	27236216638	20171014	00000001	FA	002500007016	00000038961	00000000200	00000000779	18	2017-11-13 13:11:11.80638
015	162	27952625514	20171014	00000001	FA	002500006874	00000208849	00000000200	00000004177	18	2017-11-13 13:11:11.80638
015	163	20266077719	20171014	00000001	FA	002500006887	00000021250	00000000200	00000000425	18	2017-11-13 13:11:11.80638
015	164	20305781720	20171014	00000001	FA	002500006889	00000530397	00000000200	00000010608	18	2017-11-13 13:11:11.80638
015	165	20054139285	20171014	00000001	FA	002500000697	-0000018366	00000000200	-0000000367	18	2017-11-13 13:11:11.80638
015	166	27297722927	20171014	00000001	FA	002500000432	-0000010342	00000000200	-0000000207	18	2017-11-13 13:11:11.80638
015	167	23289795464	20171014	00000001	FA	002500006885	00000090689	00000000200	00000001814	18	2017-11-13 13:11:11.80638
015	168	27363206641	20171014	00000001	FA	002500007003	00000053040	00000000200	00000001061	18	2017-11-13 13:11:11.80638
015	169	27222713140	20171014	00000001	FA	002500006886	00000056596	00000000200	00000001132	18	2017-11-13 13:11:11.80638
015	170	27248176607	20171014	00000001	FA	002500007005	00000001274	00000000200	00000000025	18	2017-11-13 13:11:11.80638
015	171	27952625514	20171014	00000001	FA	002500006906	00000149200	00000000200	00000002984	18	2017-11-13 13:11:11.80638
015	172	27064350809	20171014	00000001	FA	002500006904	00000045191	00000000200	00000000904	18	2017-11-13 13:11:11.80638
015	173	30714924644	20171014	00000001	FA	002500000433	-0000028708	00000000200	-0000000574	18	2017-11-13 13:11:11.80638
015	174	27952625514	20171014	00000001	FA	002500006869	00000261408	00000000200	00000005228	18	2017-11-13 13:11:11.80638
015	175	27200953946	20171014	00000001	FA	002500006998	00000047542	00000000200	00000000951	18	2017-11-13 13:11:11.80638
015	176	27361302759	20171014	00000001	FA	002500007007	00000033916	00000000200	00000000678	18	2017-11-13 13:11:11.80638
015	177	27952625514	20171014	00000001	FA	002500000695	-0000034505	00000000200	-0000000690	18	2017-11-13 13:11:11.80638
015	178	27346653782	20171014	00000001	FA	002500007002	00000022689	00000000200	00000000454	18	2017-11-13 13:11:11.80638
015	179	27266554740	20171014	00000001	FA	002500007006	00000054161	00000000200	00000001083	18	2017-11-13 13:11:11.80638
015	180	27346653502	20171014	00000001	FA	002500007008	00000050436	00000000200	00000001009	18	2017-11-13 13:11:11.80638
015	181	27952625514	20171014	00000001	FA	002500006871	00000139054	00000000200	00000002781	18	2017-11-13 13:11:11.80638
015	182	20082698508	20171014	00000001	FA	002500000435	-0000014326	00000000200	-0000000287	18	2017-11-13 13:11:11.80638
015	183	30670364603	20171014	00000001	FA	002500006882	00000056562	00000000200	00000001131	18	2017-11-13 13:11:11.80638
015	184	27313340339	20171014	00000001	FA	002500007015	00000060457	00000000200	00000001209	18	2017-11-13 13:11:11.80638
015	185	27125944707	20171014	00000001	FA	002500006876	00000017606	00000000200	00000000352	18	2017-11-13 13:11:11.80638
015	186	20054139285	20171014	00000001	FA	002500006877	00000108060	00000000200	00000002161	18	2017-11-13 13:11:11.80638
015	187	27245845494	20171014	00000001	FA	002500007017	00000086155	00000000200	00000001723	18	2017-11-13 13:11:11.80638
015	188	27952625514	20171014	00000001	FA	002500006873	00000110663	00000000200	00000002213	18	2017-11-13 13:11:11.80638
015	189	30670364603	20171014	00000001	FA	002500000700	-0000005086	00000000200	-0000000102	18	2017-11-13 13:11:11.80638
015	190	20054139285	20171014	00000001	FA	002500006879	00000022167	00000000200	00000000443	18	2017-11-13 13:11:11.80638
015	191	27297722927	20171014	00000001	FA	002500007001	00000016420	00000000200	00000000328	18	2017-11-13 13:11:11.80638
015	192	27236216638	20171018	00000001	FA	002500007088	00000068056	00000000200	00000001361	18	2017-11-13 13:11:11.80638
015	193	30670364603	20171018	00000001	FA	002500006960	00000018847	00000000200	00000000377	18	2017-11-13 13:11:11.80638
015	194	20266077719	20171018	00000001	FA	002500006969	00000080935	00000000200	00000001619	18	2017-11-13 13:11:11.80638
015	195	27952625514	20171018	00000001	FA	002500006945	00000138564	00000000200	00000002771	18	2017-11-13 13:11:11.80638
015	196	27297722927	20171018	00000001	FA	002500007077	00000051865	00000000200	00000001037	18	2017-11-13 13:11:11.80638
015	197	23289795464	20171018	00000001	FA	002500006965	00000200876	00000000200	00000004018	18	2017-11-13 13:11:11.80638
015	198	27363206641	20171018	00000001	FA	002500007083	00000082880	00000000200	00000001658	18	2017-11-13 13:11:11.80638
015	199	27222713140	20171018	00000001	FA	002500006966	00000039891	00000000200	00000000798	18	2017-11-13 13:11:11.80638
015	200	27367571123	20171018	00000001	FA	002500006968	00000068688	00000000200	00000001374	18	2017-11-13 13:11:11.80638
015	201	27064350809	20171018	00000001	FA	002500006949	00000101061	00000000200	00000002021	18	2017-11-13 13:11:11.80638
015	202	30670364603	20171018	00000001	FA	002500000708	-0000002543	00000000200	-0000000051	18	2017-11-13 13:11:11.80638
015	203	20213549619	20171018	00000001	FA	002500007085	00000153006	00000000200	00000003060	18	2017-11-13 13:11:11.80638
015	204	27173955451	20171018	00000001	FA	002500000709	-0000003367	00000000200	-0000000067	18	2017-11-13 13:11:11.80638
015	205	20305781720	20171018	00000001	FA	002500006971	00000046459	00000000200	00000000929	18	2017-11-13 13:11:11.80638
015	206	27952625514	20171018	00000001	FA	002500006947	00000185404	00000000200	00000003708	18	2017-11-13 13:11:11.80638
015	207	27394430655	20171018	00000001	FA	002500007093	00000029583	00000000200	00000000592	18	2017-11-13 13:11:11.80638
015	208	20054139285	20171018	00000001	FA	002500006954	00000169376	00000000200	00000003388	18	2017-11-13 13:11:11.80638
015	209	27346653782	20171018	00000001	FA	002500007080	00000029840	00000000200	00000000597	18	2017-11-13 13:11:11.80638
015	210	27952625514	20171018	00000001	FA	002500000706	-0000021748	00000000200	-0000000435	18	2017-11-13 13:11:11.80638
015	211	30670362317	20171018	00000001	FA	002500006951	00000085899	00000000200	00000001718	18	2017-11-13 13:11:11.80638
015	212	20054139285	20171018	00000001	FA	002500006956	00000093074	00000000200	00000001861	18	2017-11-13 13:11:11.80638
015	213	20182383016	20171018	00000001	FA	002500007084	00000023482	00000000200	00000000470	18	2017-11-13 13:11:11.80638
015	214	27313340339	20171018	00000001	FA	002500007087	00000037291	00000000200	00000000746	18	2017-11-13 13:11:11.80638
015	215	27361302759	20171018	00000001	FA	002500000443	-0000055562	00000000200	-0000001111	18	2017-11-13 13:11:11.80638
015	216	27952625514	20171018	00000001	FA	002500006942	00000063576	00000000200	00000001272	18	2017-11-13 13:11:11.80638
015	217	27246645227	20171018	00000001	FA	002500007092	00000017833	00000000200	00000000357	18	2017-11-13 13:11:11.80638
015	218	27245845494	20171018	00000001	FA	002500007091	00000050245	00000000200	00000001005	18	2017-11-13 13:11:11.80638
015	219	23163173824	20171018	00000001	FA	002500006952	00000020415	00000000200	00000000408	18	2017-11-13 13:11:11.80638
015	220	30670364603	20171018	00000001	FA	002500006959	00000110184	00000000200	00000002204	18	2017-11-13 13:11:11.80638
015	221	20273644912	20171018	00000001	FA	002500007078	00000011979	00000000200	00000000240	18	2017-11-13 13:11:11.80638
015	222	27101402156	20171018	00000001	FA	002500007090	00000012207	00000000200	00000000244	18	2017-11-13 13:11:11.80638
015	223	27952625514	20171018	00000001	FA	002500006944	00000231999	00000000200	00000004640	18	2017-11-13 13:11:11.80638
015	224	30670364603	20171018	00000001	FA	002500000704	-0000002467	00000000200	-0000000049	18	2017-11-13 13:11:11.80638
015	225	27173955451	20171018	00000001	FA	002500006967	00000049508	00000000200	00000000990	18	2017-11-13 13:11:11.80638
015	226	20305781720	20171018	00000001	FA	002500006970	00000071469	00000000200	00000001429	18	2017-11-13 13:11:11.80638
015	227	27222713140	20171018	00000001	FA	002500006972	00000021722	00000000200	00000000434	18	2017-11-13 13:11:11.80638
015	228	27952625514	20171018	00000001	FA	002500006946	00000268640	00000000200	00000005373	18	2017-11-13 13:11:11.80638
015	229	20054139285	20171018	00000001	FA	002500006953	00000199476	00000000200	00000003990	18	2017-11-13 13:11:11.80638
015	230	27219273431	20171018	00000001	FA	002500007076	00000036111	00000000200	00000000722	18	2017-11-13 13:11:11.80638
015	231	27064350809	20171018	00000001	FA	002500006950	00000012315	00000000200	00000000246	18	2017-11-13 13:11:11.80638
015	232	27188206463	20171018	00000001	FA	002500006958	00000054985	00000000200	00000001100	18	2017-11-13 13:11:11.80638
015	233	20118857020	20171018	00000001	FA	002500007082	00000027000	00000000200	00000000540	18	2017-11-13 13:11:11.80638
015	234	27237091987	20171018	00000001	FA	002500007086	00000024597	00000000200	00000000492	18	2017-11-13 13:11:11.80638
015	235	27952625514	20171018	00000001	FA	002500000705	-0000069409	00000000200	-0000001388	18	2017-11-13 13:11:11.80638
015	236	27222713140	20171018	00000001	FA	002500006948	00000029840	00000000200	00000000597	18	2017-11-13 13:11:11.80638
015	237	20054139285	20171018	00000001	FA	002500006955	00000270514	00000000200	00000005410	18	2017-11-13 13:11:11.80638
015	238	20213541421	20171018	00000001	FA	002500006961	00000186450	00000000200	00000003729	18	2017-11-13 13:11:11.80638
015	239	27361302759	20171018	00000001	FA	002500007089	00000055562	00000000200	00000001111	18	2017-11-13 13:11:11.80638
015	240	27952625514	20171018	00000001	FA	002500006941	00000244424	00000000200	00000004888	18	2017-11-13 13:11:11.80638
015	241	27044920102	20171018	00000001	FA	002500007079	00000053279	00000000200	00000001066	18	2017-11-13 13:11:11.80638
015	242	30670362317	20171018	00000001	FA	002500000710	-0000005086	00000000200	-0000000102	18	2017-11-13 13:11:11.80638
015	243	20054139285	20171018	00000001	FA	002500000703	-0000005814	00000000200	-0000000116	18	2017-11-13 13:11:11.80638
015	244	27056603560	20171018	00000001	FA	002500006957	00000066235	00000000200	00000001325	18	2017-11-13 13:11:11.80638
015	245	20261719887	20171018	00000001	FA	002500006962	00000068970	00000000200	00000001379	18	2017-11-13 13:11:11.80638
015	246	20274035170	20171018	00000001	FA	002500006964	00000106889	00000000200	00000002138	18	2017-11-13 13:11:11.80638
015	247	27952625514	20171018	00000001	FA	002500006943	00000108802	00000000200	00000002176	18	2017-11-13 13:11:11.80638
015	248	27225092570	20171018	00000001	FA	002500006963	00000029840	00000000200	00000000597	18	2017-11-13 13:11:11.80638
015	249	20271066547	20171018	00000001	FA	002500007081	00000022182	00000000200	00000000444	18	2017-11-13 13:11:11.80638
015	250	20267029114	20171021	00000001	FA	002500007193	00000060540	00000000200	00000001211	18	2017-11-13 13:11:11.80638
015	251	27952625514	20171021	00000001	FA	002500007063	00000306581	00000000200	00000006132	18	2017-11-13 13:11:11.80638
015	252	23163173824	20171021	00000001	FA	002500007078	00000099265	00000000200	00000001985	18	2017-11-13 13:11:11.80638
015	253	20261719887	20171021	00000001	FA	002500007082	00000024630	00000000200	00000000493	18	2017-11-13 13:11:11.80638
015	254	27248176607	20171021	00000001	FA	002500007217	00000088028	00000000200	00000001761	18	2017-11-13 13:11:11.80638
015	255	27952625514	20171021	00000001	FA	002500007065	00000371632	00000000200	00000007433	18	2017-11-13 13:11:11.80638
015	256	30714924644	20171021	00000001	FA	002500007211	00000022720	00000000200	00000000454	18	2017-11-13 13:11:11.80638
015	257	27222713140	20171021	00000001	FA	002500007087	00000029840	00000000200	00000000597	18	2017-11-13 13:11:11.80638
015	258	27100281819	20171021	00000001	FA	002500007077	00000041586	00000000200	00000000832	18	2017-11-13 13:11:11.80638
015	259	27952625514	20171021	00000001	FA	002500000722	-0000067970	00000000200	-0000001359	18	2017-11-13 13:11:11.80638
015	260	27064350809	20171021	00000001	FA	002500007076	00000072962	00000000200	00000001459	18	2017-11-13 13:11:11.80638
015	261	30670364603	20171021	00000001	FA	002500000725	-0000005364	00000000200	-0000000107	18	2017-11-13 13:11:11.80638
015	262	27305781466	20171021	00000001	FA	002500007215	00000009055	00000000200	00000000181	18	2017-11-13 13:11:11.80638
015	263	27044920102	20171021	00000001	FA	002500007212	00000086278	00000000200	00000001726	18	2017-11-13 13:11:11.80638
015	264	27952625514	20171021	00000001	FA	002500007062	00000260219	00000000200	00000005204	18	2017-11-13 13:11:11.80638
015	265	20054139285	20171021	00000001	FA	002500007068	00000240591	00000000200	00000004812	18	2017-11-13 13:11:11.80638
015	266	20261719887	20171021	00000001	FA	002500007081	00000093531	00000000200	00000001871	18	2017-11-13 13:11:11.80638
015	267	27316539497	20171021	00000001	FA	002500007192	00000021005	00000000200	00000000420	18	2017-11-13 13:11:11.80638
015	268	27225092570	20171021	00000001	FA	002500007069	00000029840	00000000200	00000000597	18	2017-11-13 13:11:11.80638
015	269	27952625514	20171021	00000001	FA	002500007064	00000165302	00000000200	00000003306	18	2017-11-13 13:11:11.80638
015	270	20313340792	20171021	00000001	FA	002500007220	00000061978	00000000200	00000001240	18	2017-11-13 13:11:11.80638
015	271	23163173824	20171021	00000001	FA	002500000726	-0000001684	00000000200	-0000000034	18	2017-11-13 13:11:11.80638
015	272	27297722927	20171021	00000001	FA	002500007191	00000099879	00000000200	00000001998	18	2017-11-13 13:11:11.80638
015	273	23289795464	20171021	00000001	FA	002500007070	00000129189	00000000200	00000002584	18	2017-11-13 13:11:11.80638
015	274	27363206641	20171021	00000001	FA	002500007213	00000053040	00000000200	00000001061	18	2017-11-13 13:11:11.80638
015	275	27367571123	20171021	00000001	FA	002500007084	00000078813	00000000200	00000001576	18	2017-11-13 13:11:11.80638
015	276	27952625514	20171021	00000001	FA	002500007066	00000339790	00000000200	00000006796	18	2017-11-13 13:11:11.80638
015	277	30670364603	20171021	00000001	FA	002500007080	00000048177	00000000200	00000000964	18	2017-11-13 13:11:11.80638
015	278	20213549619	20171021	00000001	FA	002500007214	00000081646	00000000200	00000001633	18	2017-11-13 13:11:11.80638
015	279	27173955451	20171021	00000001	FA	002500007083	00000024557	00000000200	00000000491	18	2017-11-13 13:11:11.80638
015	280	20305781720	20171021	00000001	FA	002500007086	00000077888	00000000200	00000001558	18	2017-11-13 13:11:11.80638
015	281	27394430655	20171021	00000001	FA	002500007194	00000086174	00000000200	00000001723	18	2017-11-13 13:11:11.80638
015	282	27950274226	20171021	00000001	FA	002500007216	00000004527	00000000200	00000000091	18	2017-11-13 13:11:11.80638
015	283	27952625514	20171021	00000001	FA	002500000723	-0000043697	00000000200	-0000000874	18	2017-11-13 13:11:11.80638
015	284	27064350809	20171021	00000001	FA	002500000727	-0000003498	00000000200	-0000000070	18	2017-11-13 13:11:11.80638
015	285	27952625514	20171021	00000001	FA	002500007061	00000019195	00000000200	00000000384	18	2017-11-13 13:11:11.80638
015	286	27246645227	20171021	00000001	FA	002500007219	00000026137	00000000200	00000000523	18	2017-11-13 13:11:11.80638
015	287	23289795464	20171021	00000001	FA	002500007067	00000098776	00000000200	00000001976	18	2017-11-13 13:11:11.80638
015	288	20054139285	20171024	00000001	FA	002500007125	00000131925	00000000200	00000002639	18	2017-11-13 13:11:11.80638
015	289	27056603560	20171024	00000001	FA	002500007130	00000041446	00000000200	00000000829	18	2017-11-13 13:11:11.80638
015	290	27316539497	20171024	00000001	FA	002500007280	00000046236	00000000200	00000000925	18	2017-11-13 13:11:11.80638
015	291	20274035170	20171024	00000001	FA	002500000731	-0000005572	00000000200	-0000000111	18	2017-11-13 13:11:11.80638
015	292	27101402156	20171024	00000001	FA	002500007289	00000007649	00000000200	00000000153	18	2017-11-13 13:11:11.80638
015	293	27952625514	20171024	00000001	FA	002500000730	-0000057487	00000000200	-0000001150	18	2017-11-13 13:11:11.80638
015	294	20313340792	20171024	00000001	FA	002500007290	00000012265	00000000200	00000000245	18	2017-11-13 13:11:11.80638
015	295	20054139285	20171024	00000001	FA	002500007127	00000112112	00000000200	00000002242	18	2017-11-13 13:11:11.80638
015	296	27200953946	20171024	00000001	FA	002500007278	00000079240	00000000200	00000001585	18	2017-11-13 13:11:11.80638
015	297	27297722927	20171024	00000001	FA	002500007279	00000090679	00000000200	00000001814	18	2017-11-13 13:11:11.80638
015	298	27363206641	20171024	00000001	FA	002500007282	00000050250	00000000200	00000001005	18	2017-11-13 13:11:11.80638
015	299	27219273431	20171024	00000001	FA	002500007276	00000029840	00000000200	00000000597	18	2017-11-13 13:11:11.80638
003	347	27259753274	20171115	00026530	FA	001500026530	00000025087	00000000200	00000000502	7	2017-12-05 18:44:23.476865
003	348	27367571123	20171115	00008245	FA	001900008245	00000084878	00000000200	00000001698	7	2017-12-05 18:44:23.476865
003	349	27222713140	20171115	00008246	FA	001900008246	00000169833	00000000200	00000003397	7	2017-12-05 18:44:23.476865
003	350	27222713140	20171115	00008247	FA	001900008247	00000109020	00000000200	00000002180	7	2017-12-05 18:44:23.476865
003	351	30604760018	20171115	00026572	FA	001500026572	00000400323	00000000200	00000008006	7	2017-12-05 18:44:23.476865
003	352	30670364778	20171116	00026160	FA	001500026160	00000036511	00000000200	00000000730	7	2017-12-05 18:44:23.476865
003	353	30670364778	20171116	00026161	FA	001500026161	00000115469	00000000200	00000002309	7	2017-12-05 18:44:23.476865
003	354	20940818649	20171116	00016618	FA	001900016618	00000165882	00000000200	00000003318	7	2017-12-05 18:44:23.476865
003	355	20271066547	20171116	00026179	FA	001500026179	00000220634	00000000200	00000004413	7	2017-12-05 18:44:23.476865
003	356	20266077719	20171116	00008269	FA	001900008269	00000047954	00000000200	00000000959	7	2017-12-05 18:44:23.476865
003	357	20266077719	20171116	00008270	FA	001900008270	00000034472	00000000200	00000000689	7	2017-12-05 18:44:23.476865
003	358	20226158074	20171116	00016643	FA	001900016643	00000063840	00000000200	00000001277	7	2017-12-05 18:44:23.476865
003	359	20125944281	20171116	00008287	FA	001900008287	00000074152	00000000200	00000001483	7	2017-12-05 18:44:23.476865
003	360	30707024085	20171116	00008288	FA	001900008288	00000030431	00000000200	00000000609	7	2017-12-05 18:44:23.476865
003	361	27205190134	20171117	00008291	FA	001900008291	00000156668	00000000200	00000003133	7	2017-12-05 18:44:23.476865
003	362	27367571123	20171117	00020646	FA	001700020646	00000039619	00000000200	00000000792	7	2017-12-05 18:44:23.476865
003	363	20261719887	20171117	00020647	FA	001700020647	00000107242	00000000200	00000002145	7	2017-12-05 18:44:23.476865
003	364	20213541421	20171117	00020648	FA	001700020648	00000006060	00000000200	00000000121	7	2017-12-05 18:44:23.476865
003	365	27952625514	20171117	00020649	FA	001700020649	00000443672	00000000200	00000008873	7	2017-12-05 18:44:23.476865
003	366	27219273431	20171117	00020532	FA	001700020532	00000060122	00000000200	00000001202	7	2017-12-05 18:44:23.476865
003	367	27346653502	20171117	00020533	FA	001700020533	00000074640	00000000200	00000001493	7	2017-12-05 18:44:23.476865
003	368	20054139285	20171117	00020651	FA	001700020651	00000316654	00000000200	00000006333	7	2017-12-05 18:44:23.476865
003	369	27367571123	20171117	00020654	FA	001700020654	00000150881	00000000200	00000003018	7	2017-12-05 18:44:23.476865
003	370	27064350809	20171117	00020655	FA	001700020655	00000026134	00000000200	00000000523	7	2017-12-05 18:44:23.476865
003	371	27316072483	20171117	00020656	FA	001700020656	00000213079	00000000200	00000004262	7	2017-12-05 18:44:23.476865
003	372	27173955451	20171117	00020657	FA	001700020657	00000066827	00000000200	00000001337	7	2017-12-05 18:44:23.476865
003	373	27056603560	20171117	00020658	FA	001700020658	00000048477	00000000200	00000000970	7	2017-12-05 18:44:23.476865
003	374	27316072483	20171117	00020659	FA	001700020659	00000046539	00000000200	00000000931	7	2017-12-05 18:44:23.476865
003	375	27064350809	20171117	00020660	FA	001700020660	00000164090	00000000200	00000003282	7	2017-12-05 18:44:23.476865
003	376	27222713140	20171117	00020661	FA	001700020661	00000044111	00000000200	00000000882	7	2017-12-05 18:44:23.476865
003	377	20118857020	20171117	00020535	FA	001700020535	00000119719	00000000200	00000002394	7	2017-12-05 18:44:23.476865
003	378	27942269566	20171117	00020536	FA	001700020536	00000018199	00000000200	00000000364	7	2017-12-05 18:44:23.476865
003	379	27215184760	20171117	00020537	FA	001700020537	00000020424	00000000200	00000000408	7	2017-12-05 18:44:23.476865
003	380	27394430655	20171117	00020538	FA	001700020538	00000017035	00000000200	00000000341	7	2017-12-05 18:44:23.476865
003	381	27313340339	20171117	00020539	FA	001700020539	00000033170	00000000200	00000000663	7	2017-12-05 18:44:23.476865
003	382	23236216764	20171117	00020540	FA	001700020540	00000061940	00000000200	00000001239	7	2017-12-05 18:44:23.476865
003	383	30670388480	20171117	00005610	FA	001800005610	00000029551	00000000200	00000000591	7	2017-12-05 18:44:23.476865
003	384	27056603560	20171117	00026632	FA	001500026632	00000193216	00000000200	00000003864	7	2017-12-05 18:44:23.476865
003	385	27064350809	20171117	00026633	FA	001500026633	00000069536	00000000200	00000001391	7	2017-12-05 18:44:23.476865
003	386	27173955451	20171117	00026634	FA	001500026634	00000101295	00000000200	00000002026	7	2017-12-05 18:44:23.476865
003	387	27367571123	20171117	00026635	FA	001500026635	00000158917	00000000200	00000003178	7	2017-12-05 18:44:23.476865
003	388	20261719887	20171117	00026636	FA	001500026636	00000248199	00000000200	00000004964	7	2017-12-05 18:44:23.476865
003	389	27367571123	20171117	00026637	FA	001500026637	00000010384	00000000200	00000000208	7	2017-12-05 18:44:23.476865
003	390	20253357828	20171117	00026638	FA	001500026638	00000088602	00000000200	00000001772	7	2017-12-05 18:44:23.476865
003	391	20163184592	20171117	00026639	FA	001500026639	00000010515	00000000200	00000000210	7	2017-12-05 18:44:23.476865
003	392	20213541421	20171117	00026640	FA	001500026640	00000115691	00000000200	00000002314	7	2017-12-05 18:44:23.476865
003	393	27222713140	20171117	00026641	FA	001500026641	00000097066	00000000200	00000001941	7	2017-12-05 18:44:23.476865
003	394	23241216349	20171117	00026642	FA	001500026642	00000127046	00000000200	00000002541	7	2017-12-05 18:44:23.476865
003	395	30604760018	20171117	00026643	FA	001500026643	00001077738	00000000200	00000021555	7	2017-12-05 18:44:23.476865
003	396	30670388480	20171117	00026644	FA	001500026644	00000635007	00000000200	00000012700	7	2017-12-05 18:44:23.476865
003	397	20073261806	20171117	00026645	FA	001500026645	00000121414	00000000200	00000002428	7	2017-12-05 18:44:23.476865
003	398	27952625514	20171117	00026646	FA	001500026646	00000747815	00000000200	00000014956	7	2017-12-05 18:44:23.476865
003	399	27222713140	20171117	00026647	FA	001500026647	00000256164	00000000200	00000005123	7	2017-12-05 18:44:23.476865
003	400	27266554740	20171117	00026206	FA	001500026206	00000042716	00000000200	00000000854	7	2017-12-05 18:44:23.476865
003	401	20118857020	20171117	00026207	FA	001500026207	00000054398	00000000200	00000001088	7	2017-12-05 18:44:23.476865
003	402	20225250473	20171117	00026208	FA	001500026208	00000061841	00000000200	00000001237	7	2017-12-05 18:44:23.476865
003	403	27346653502	20171117	00026209	FA	001500026209	00000383916	00000000200	00000007678	7	2017-12-05 18:44:23.476865
003	404	27219273431	20171117	00026210	FA	001500026210	00000066730	00000000200	00000001335	7	2017-12-05 18:44:23.476865
003	405	20173955449	20171117	00026211	FA	001500026211	00000074564	00000000200	00000001491	7	2017-12-05 18:44:23.476865
003	406	20118857020	20171117	00020546	FA	001700020546	00000056784	00000000200	00000001136	7	2017-12-05 18:44:23.476865
003	407	20163184592	20171117	00020663	FA	001700020663	00000110205	00000000200	00000002204	7	2017-12-05 18:44:23.476865
003	408	20054139285	20171117	00026649	FA	001500026649	00000174997	00000000200	00000003500	7	2017-12-05 18:44:23.476865
003	409	27363206641	20171117	00011670	FA	001300011670	00000344617	00000000200	00000006892	7	2017-12-05 18:44:23.476865
003	410	30670364778	20171118	00020567	FA	001700020567	00000603807	00000000200	00000012076	7	2017-12-05 18:44:23.476865
003	411	23289795464	20171118	00020699	FA	001700020699	00000094996	00000000200	00000001900	7	2017-12-05 18:44:23.476865
003	412	20226158074	20171118	00020568	FA	001700020568	00000117412	00000000200	00000002348	7	2017-12-05 18:44:23.476865
003	413	27952625514	20171118	00026710	FA	001500026710	00000033043	00000000200	00000000661	7	2017-12-05 18:44:23.476865
003	414	27309749079	20171118	00026711	FA	001500026711	00000057160	00000000200	00000001143	7	2017-12-05 18:44:23.476865
003	415	27125944707	20171118	00026712	FA	001500026712	00000021868	00000000200	00000000437	7	2017-12-05 18:44:23.476865
003	416	23289795464	20171118	00026713	FA	001500026713	00000120645	00000000200	00000002413	7	2017-12-05 18:44:23.476865
003	417	23163173824	20171118	00026714	FA	001500026714	00000028637	00000000200	00000000573	7	2017-12-05 18:44:23.476865
003	418	23331976199	20171118	00026265	FA	001500026265	00000051775	00000000200	00000001036	7	2017-12-05 18:44:23.476865
003	419	27226803233	20171118	00026266	FA	001500026266	00000049028	00000000200	00000000981	7	2017-12-05 18:44:23.476865
003	420	20118857020	20171118	00026267	FA	001500026267	00000177796	00000000200	00000003556	7	2017-12-05 18:44:23.476865
003	421	20313340792	20171118	00026268	FA	001500026268	00000016254	00000000200	00000000325	7	2017-12-05 18:44:23.476865
003	422	27942269566	20171118	00026269	FA	001500026269	00000063071	00000000200	00000001261	7	2017-12-05 18:44:23.476865
003	423	27044920102	20171118	00026270	FA	001500026270	00000009430	00000000200	00000000189	7	2017-12-05 18:44:23.476865
003	424	27215184760	20171118	00026271	FA	001500026271	00000010902	00000000200	00000000218	7	2017-12-05 18:44:23.476865
003	425	27313340339	20171118	00026272	FA	001500026272	00000051051	00000000200	00000001021	7	2017-12-05 18:44:23.476865
003	426	27297722927	20171118	00026273	FA	001500026273	00000040533	00000000200	00000000811	7	2017-12-05 18:44:23.476865
003	427	20273644912	20171118	00016724	FA	001900016724	00000010614	00000000200	00000000212	7	2017-12-05 18:44:23.476865
003	428	20261719887	20171118	00005612	FA	001800005612	00000045798	00000000200	00000000916	7	2017-12-05 18:44:23.476865
003	429	27367571123	20171118	00005613	FA	001800005613	00000036528	00000000200	00000000731	7	2017-12-05 18:44:23.476865
003	430	23289795464	20171118	00005614	FA	001800005614	00000012621	00000000200	00000000252	7	2017-12-05 18:44:23.476865
003	431	27222713140	20171118	00005615	FA	001800005615	00000017411	00000000200	00000000348	7	2017-12-05 18:44:23.476865
003	432	27361302759	20171118	00006770	FA	001800006770	00000008731	00000000200	00000000175	7	2017-12-05 18:44:23.476865
003	433	27239279908	20171118	00006771	FA	001800006771	00000006843	00000000200	00000000137	7	2017-12-05 18:44:23.476865
003	434	27394430655	20171118	00006772	FA	001800006772	00000032874	00000000200	00000000657	7	2017-12-05 18:44:23.476865
003	435	20266077719	20171118	00008322	FA	001900008322	00000006297	00000000200	00000000126	7	2017-12-05 18:44:23.476865
003	436	27325453465	20171118	00016744	FA	001900016744	00000072828	00000000200	00000001457	7	2017-12-05 18:44:23.476865
003	437	27219273431	20171121	00020607	FA	001700020607	00000007733	00000000200	00000000155	7	2017-12-05 18:44:23.476865
003	438	27222713140	20171121	00020727	FA	001700020727	00000062495	00000000200	00000001250	7	2017-12-05 18:44:23.476865
003	439	20166734178	20171121	00020728	FA	001700020728	00000213079	00000000200	00000004262	7	2017-12-05 18:44:23.476865
015	300	20054139285	20171024	00000001	FA	002500000734	-0000045763	00000000200	-0000000915	18	2017-11-13 13:11:11.80638
015	301	27188206463	20171024	00000001	FA	002500007131	00000070428	00000000200	00000001409	18	2017-11-13 13:11:11.80638
015	302	20213549619	20171024	00000001	FA	002500007283	00000092880	00000000200	00000001858	18	2017-11-13 13:11:11.80638
015	303	27173955451	20171024	00000001	FA	002500007137	00000033726	00000000200	00000000675	18	2017-11-13 13:11:11.80638
015	304	27237091987	20171024	00000001	FA	002500007284	00000019336	00000000200	00000000387	18	2017-11-13 13:11:11.80638
015	305	20305781720	20171024	00000001	FA	002500007140	00000107980	00000000200	00000002160	18	2017-11-13 13:11:11.80638
015	306	27266554740	20171024	00000001	FA	002500007286	00000033089	00000000200	00000000662	18	2017-11-13 13:11:11.80638
015	307	20082698508	20171024	00000001	FA	002500007275	00000029542	00000000200	00000000591	18	2017-11-13 13:11:11.80638
015	308	20213541421	20171024	00000001	FA	002500007133	00000069720	00000000200	00000001394	18	2017-11-13 13:11:11.80638
015	309	27952625514	20171024	00000001	FA	002500007118	00000346787	00000000200	00000006936	18	2017-11-13 13:11:11.80638
015	310	27064350809	20171024	00000001	FA	002500007121	00000045979	00000000200	00000000920	18	2017-11-13 13:11:11.80638
015	311	27125944707	20171024	00000001	FA	002500007124	00000084707	00000000200	00000001694	18	2017-11-13 13:11:11.80638
015	312	27056603560	20171024	00000001	FA	002500007129	00000124719	00000000200	00000002494	18	2017-11-13 13:11:11.80638
015	313	20274035170	20171024	00000001	FA	002500007135	00000099763	00000000200	00000001995	18	2017-11-13 13:11:11.80638
015	314	27313340339	20171024	00000001	FA	002500000463	-0000016671	00000000200	-0000000333	18	2017-11-13 13:11:11.80638
015	315	27952625514	20171024	00000001	FA	002500007120	00000224535	00000000200	00000004491	18	2017-11-13 13:11:11.80638
015	316	23289795464	20171024	00000001	FA	002500007122	00000170373	00000000200	00000003407	18	2017-11-13 13:11:11.80638
015	317	20271066547	20171024	00000001	FA	002500007281	00000016265	00000000200	00000000325	18	2017-11-13 13:11:11.80638
015	318	27236216638	20171024	00000001	FA	002500007287	00000020051	00000000200	00000000401	18	2017-11-13 13:11:11.80638
015	319	20054139285	20171024	00000001	FA	002500007126	00000094745	00000000200	00000001895	18	2017-11-13 13:11:11.80638
015	320	20261719887	20171024	00000001	FA	002500007134	00000097022	00000000200	00000001940	18	2017-11-13 13:11:11.80638
015	321	20266077719	20171024	00000001	FA	002500007139	00000237348	00000000200	00000004747	18	2017-11-13 13:11:11.80638
015	322	27952625514	20171024	00000001	FA	002500000733	-0000025168	00000000200	-0000000503	18	2017-11-13 13:11:11.80638
015	323	27222713140	20171024	00000001	FA	002500007136	00000066221	00000000200	00000001324	18	2017-11-13 13:11:11.80638
015	324	20054139285	20171024	00000001	FA	002500007128	00000161446	00000000200	00000003229	18	2017-11-13 13:11:11.80638
015	325	27222713140	20171024	00000001	FA	002500007142	00000027420	00000000200	00000000548	18	2017-11-13 13:11:11.80638
015	326	27100281819	20171024	00000001	FA	002500007123	00000047906	00000000200	00000000958	18	2017-11-13 13:11:11.80638
015	327	27219273431	20171024	00000001	FA	002500007277	00000041232	00000000200	00000000825	18	2017-11-13 13:11:11.80638
015	328	20054139285	20171024	00000001	FA	002500000735	-0000002831	00000000200	-0000000057	18	2017-11-13 13:11:11.80638
015	329	30670364603	20171024	00000001	FA	002500007132	00000020824	00000000200	00000000416	18	2017-11-13 13:11:11.80638
015	330	27173955451	20171024	00000001	FA	002500007138	00000092881	00000000200	00000001858	18	2017-11-13 13:11:11.80638
015	331	20305781720	20171024	00000001	FA	002500007141	00000035771	00000000200	00000000715	18	2017-11-13 13:11:11.80638
015	332	27313340339	20171024	00000001	FA	002500007285	00000039408	00000000200	00000000788	18	2017-11-13 13:11:11.80638
015	333	27361302759	20171024	00000001	FA	002500007288	00000052766	00000000200	00000001055	18	2017-11-13 13:11:11.80638
015	334	27952625514	20171024	00000001	FA	002500007119	00000110350	00000000200	00000002207	18	2017-11-13 13:11:11.80638
015	335	27064350809	20171024	00000001	FA	002500000736	-0000005154	00000000200	-0000000103	18	2017-11-13 13:11:11.80638
015	336	30999157935	20171026	00000001	FA	002500007362	00000152889	00000000200	00000003058	18	2017-11-13 13:11:11.80638
015	337	20305781720	20171027	00000001	FA	002500007241	00000065987	00000000200	00000001320	18	2017-11-13 13:11:11.80638
015	338	30670362317	20171027	00000001	FA	002500000750	-0000002167	00000000200	-0000000043	18	2017-11-13 13:11:11.80638
015	339	20273644912	20171027	00000001	FA	002500007398	00000004732	00000000200	00000000095	18	2017-11-13 13:11:11.80638
015	340	27313340339	20171027	00000001	FA	002500007407	00000043498	00000000200	00000000870	18	2017-11-13 13:11:11.80638
015	341	20261719887	20171027	00000001	FA	002500007255	00000075426	00000000200	00000001509	18	2017-11-13 13:11:11.80638
015	342	27952625514	20171027	00000001	FA	002500007242	00000238695	00000000200	00000004774	18	2017-11-13 13:11:11.80638
015	343	23289795464	20171027	00000001	FA	002500007239	00000090354	00000000200	00000001807	18	2017-11-13 13:11:11.80638
015	344	27222713140	20171027	00000001	FA	002500007256	00000064737	00000000200	00000001295	18	2017-11-13 13:11:11.80638
015	345	27222713140	20171027	00000001	FA	002500007258	00000013741	00000000200	00000000275	18	2017-11-13 13:11:11.80638
015	346	27346653502	20171027	00000001	FA	002500007408	00000058617	00000000200	00000001172	18	2017-11-13 13:11:11.80638
015	347	27952625514	20171027	00000001	FA	002500007244	00000379236	00000000200	00000007585	18	2017-11-13 13:11:11.80638
015	348	27394430655	20171027	00000001	FA	002500007401	00000241218	00000000200	00000004824	18	2017-11-13 13:11:11.80638
015	349	27950274226	20171027	00000001	FA	002500007399	00000017003	00000000200	00000000340	18	2017-11-13 13:11:11.80638
015	350	27219273431	20171027	00000001	FA	002500007404	00000036885	00000000200	00000000738	18	2017-11-13 13:11:11.80638
015	351	20054139285	20171027	00000001	FA	002500007237	00000149683	00000000200	00000002994	18	2017-11-13 13:11:11.80638
015	352	30670364603	20171027	00000001	FA	002500007253	00000051150	00000000200	00000001023	18	2017-11-13 13:11:11.80638
015	353	20305781720	20171027	00000001	FA	002500007240	00000054889	00000000200	00000001098	18	2017-11-13 13:11:11.80638
015	354	27952625514	20171027	00000001	FA	002500000751	-0000046234	00000000200	-0000000925	18	2017-11-13 13:11:11.80638
015	355	27346653340	20171027	00000001	FA	002500007402	00000194636	00000000200	00000003893	18	2017-11-13 13:11:11.80638
015	356	30670362317	20171027	00000001	FA	002500007236	00000018972	00000000200	00000000379	18	2017-11-13 13:11:11.80638
015	357	27246645227	20171027	00000001	FA	002500007409	00000023691	00000000200	00000000474	18	2017-11-13 13:11:11.80638
015	358	27064350809	20171027	00000001	FA	002500007235	00000064220	00000000200	00000001284	18	2017-11-13 13:11:11.80638
015	359	20305781720	20171027	00000001	FA	002500007257	00000092880	00000000200	00000001858	18	2017-11-13 13:11:11.80638
015	360	20267029114	20171027	00000001	FA	002500007400	00000039923	00000000200	00000000798	18	2017-11-13 13:11:11.80638
015	361	27056603560	20171027	00000001	FA	002500007252	00000083472	00000000200	00000001669	18	2017-11-13 13:11:11.80638
015	362	30714924644	20171027	00000001	FA	002500007405	00000014809	00000000200	00000000296	18	2017-11-13 13:11:11.80638
015	363	20261719887	20171027	00000001	FA	002500000752	-0000004786	00000000200	-0000000096	18	2017-11-13 13:11:11.80638
015	364	27952625514	20171027	00000001	FA	002500007243	00000265263	00000000200	00000005305	18	2017-11-13 13:11:11.80638
015	365	27952625514	20171027	00000001	FA	002500007245	00000183479	00000000200	00000003670	18	2017-11-13 13:11:11.80638
015	366	27394430655	20171027	00000001	FA	002500007410	00000092880	00000000200	00000001858	18	2017-11-13 13:11:11.80638
015	367	20213541421	20171027	00000001	FA	002500007238	00000041832	00000000200	00000000837	18	2017-11-13 13:11:11.80638
015	368	27950274226	20171027	00000001	FA	002500000469	-0000017003	00000000200	-0000000340	18	2017-11-13 13:11:11.80638
015	369	27125944707	20171027	00000001	FA	002500007251	00000104610	00000000200	00000002092	18	2017-11-13 13:11:11.80638
015	370	30670364603	20171027	00000001	FA	002500007254	00000025477	00000000200	00000000510	18	2017-11-13 13:11:11.80638
015	371	27044920102	20171027	00000001	FA	002500007406	00000053917	00000000200	00000001078	18	2017-11-13 13:11:11.80638
015	372	27952625514	20171031	00000001	FA	002500007298	00000256750	00000000200	00000005135	18	2017-11-13 13:11:11.80638
015	373	27346653340	20171031	00000001	FA	002500007460	00000100977	00000000200	00000002020	18	2017-11-13 13:11:11.80638
015	374	27064350809	20171031	00000001	FA	002500007303	00000029188	00000000200	00000000584	18	2017-11-13 13:11:11.80638
015	375	23289795464	20171031	00000001	FA	002500007304	00000117810	00000000200	00000002356	18	2017-11-13 13:11:11.80638
015	376	20054139285	20171031	00000001	FA	002500007307	00000095932	00000000200	00000001919	18	2017-11-13 13:11:11.80638
015	377	20271066547	20171031	00000001	FA	002500007455	00000213126	00000000200	00000004263	18	2017-11-13 13:11:11.80638
015	378	20305781720	20171031	00000001	FA	002500007320	00000041726	00000000200	00000000835	18	2017-11-13 13:11:11.80638
015	379	27952625514	20171031	00000001	FA	002500007300	00000185954	00000000200	00000003719	18	2017-11-13 13:11:11.80638
015	380	20274035170	20171031	00000001	FA	002500007313	00000016420	00000000200	00000000328	18	2017-11-13 13:11:11.80638
015	381	20266077719	20171031	00000001	FA	002500007319	00000102190	00000000200	00000002044	18	2017-11-13 13:11:11.80638
015	382	27367571123	20171031	00000001	FA	002500007318	00000040704	00000000200	00000000814	18	2017-11-13 13:11:11.80638
015	383	27952625514	20171031	00000001	FA	002500007323	00000144108	00000000200	00000002882	18	2017-11-13 13:11:11.80638
015	384	23241214044	20171031	00000001	FA	002500007449	00000025230	00000000200	00000000505	18	2017-11-13 13:11:11.80638
015	385	20261719887	20171031	00000001	FA	002500007310	00000044905	00000000200	00000000898	18	2017-11-13 13:11:11.80638
015	386	23289795464	20171031	00000001	FA	002500007315	00000041050	00000000200	00000000821	18	2017-11-13 13:11:11.80638
015	387	27222713140	20171031	00000001	FA	002500007302	00000059680	00000000200	00000001194	18	2017-11-13 13:11:11.80638
015	388	27173955451	20171031	00000001	FA	002500007317	00000062419	00000000200	00000001248	18	2017-11-13 13:11:11.80638
015	389	20182383016	20171031	00000001	FA	002500000474	-0000007733	00000000200	-0000000155	18	2017-11-13 13:11:11.80638
015	390	27950274226	20171031	00000001	FA	002500007459	00000050407	00000000200	00000001008	18	2017-11-13 13:11:11.80638
015	391	20054139285	20171031	00000001	FA	002500007306	00000101572	00000000200	00000002031	18	2017-11-13 13:11:11.80638
015	392	30670364603	20171031	00000001	FA	002500007309	00000006292	00000000200	00000000126	18	2017-11-13 13:11:11.80638
015	393	27044920102	20171031	00000001	FA	002500007453	00000047715	00000000200	00000000954	18	2017-11-13 13:11:11.80638
015	394	27952625514	20171031	00000001	FA	002500007299	00000084445	00000000200	00000001689	18	2017-11-13 13:11:11.80638
015	395	27316539497	20171031	00000001	FA	002500007454	00000048966	00000000200	00000000979	18	2017-11-13 13:11:11.80638
015	396	20274035170	20171031	00000001	FA	002500007312	00000105040	00000000200	00000002101	18	2017-11-13 13:11:11.80638
015	397	20054139285	20171031	00000001	FA	002500007308	00000150414	00000000200	00000003008	18	2017-11-13 13:11:11.80638
015	398	20305781720	20171031	00000001	FA	002500007321	00000067741	00000000200	00000001355	18	2017-11-13 13:11:11.80638
015	399	27952625514	20171031	00000001	FA	002500007301	00000010259	00000000200	00000000205	18	2017-11-13 13:11:11.80638
015	400	20313340792	20171031	00000001	FA	002500007461	00000021723	00000000200	00000000434	18	2017-11-13 13:11:11.80638
015	401	27297722927	20171031	00000001	FA	002500007452	00000014197	00000000200	00000000284	18	2017-11-13 13:11:11.80638
015	402	23289795464	20171031	00000001	FA	002500007314	00000041261	00000000200	00000000825	18	2017-11-13 13:11:11.80638
015	403	27363206641	20171031	00000001	FA	002500007456	00000066174	00000000200	00000001324	18	2017-11-13 13:11:11.80638
015	404	27222713140	20171031	00000001	FA	002500007316	00000070207	00000000200	00000001404	18	2017-11-13 13:11:11.80638
015	405	27952625514	20171031	00000001	FA	002500000761	-0000144108	00000000200	-0000002882	18	2017-11-13 13:11:11.80638
015	406	20261719887	20171031	00000001	FA	002500007311	00000042136	00000000200	00000000843	18	2017-11-13 13:11:11.80638
015	407	27237091987	20171031	00000001	FA	002500007458	00000028499	00000000200	00000000570	18	2017-11-13 13:11:11.80638
015	408	27222713140	20171031	00000001	FA	002500007322	00000006403	00000000200	00000000128	18	2017-11-13 13:11:11.80638
015	409	20082698508	20171031	00000001	FA	002500007450	00000026061	00000000200	00000000521	18	2017-11-13 13:11:11.80638
015	410	20182383016	20171031	00000001	FA	002500007457	00000013135	00000000200	00000000263	18	2017-11-13 13:11:11.80638
015	411	27219273431	20171031	00000001	FA	002500007451	00000093855	00000000200	00000001877	18	2017-11-13 13:11:11.80638
015	412	20054139285	20171031	00000001	FA	002500007305	00000109654	00000000200	00000002193	18	2017-11-13 13:11:11.80638
001	48	02022525047	20171014	00002127	FA	120000002127	00000051735	00000000200	00000001034	5	2017-11-16 12:54:35.10011
001	49	02704492010	20171025	00002225	FA	120000002225	00000130268	00000000200	00000002606	5	2017-11-16 12:54:35.10011
001	50	02731334075	20171025	00002228	FA	120000002228	00000074144	00000000200	00000001483	5	2017-11-16 12:54:35.10011
001	51	02022525047	20171025	00002229	FA	120000002229	00000072212	00000000200	00000001444	5	2017-11-16 12:54:35.10011
001	52	02736320664	20171025	00002230	FA	120000002230	00000110645	00000000200	00000002213	5	2017-11-16 12:54:35.10011
001	53	02016559574	20171025	00002231	FA	120000002231	00000054565	00000000200	00000001091	5	2017-11-16 12:54:35.10011
001	54	02017395544	20171025	00002232	FA	120000002232	00000090354	00000000200	00000001807	5	2017-11-16 12:54:35.10011
001	55	02732545346	20171025	00002233	FA	120000002233	00000079462	00000000200	00000001589	5	2017-11-16 12:54:35.10011
001	56	02021518189	20171027	00002237	FA	120000002237	00000030387	00000000200	00000000608	5	2017-11-16 12:54:35.10011
001	57	02005413928	20171018	00020992	FA	110000020992	00000094630	00000000200	00000001892	5	2017-11-16 12:54:35.10011
001	58	02005413928	20171003	00020225	FA	110000020225	00000420283	00000000200	00000008406	5	2017-11-16 12:54:35.10011
001	59	02005413928	20171003	00020226	FA	110000020226	00000137393	00000000200	00000002748	5	2017-11-16 12:54:35.10011
001	60	02324121634	20171009	00020492	FA	110000020492	00000246890	00000000200	00000004939	5	2017-11-16 12:54:35.10011
001	61	02324121634	20171009	00020493	FA	110000020493	00000115305	00000000200	00000002306	5	2017-11-16 12:54:35.10011
001	62	02712594470	20171004	00020243	FA	110000020243	00000126577	00000000200	00000002532	5	2017-11-16 12:54:35.10011
001	63	02706435080	20171004	00020244	FA	110000020244	00000140678	00000000200	00000002812	5	2017-11-16 12:54:35.10011
001	64	02716318473	20171004	00020245	FA	110000020245	00000330201	00000000200	00000006604	5	2017-11-16 12:54:35.10011
001	65	02007326180	20171004	00020248	FA	110000020248	00000045920	00000000200	00000000918	5	2017-11-16 12:54:35.10011
001	66	02027403517	20171004	00020249	FA	110000020249	00000584854	00000000200	00000011697	5	2017-11-16 12:54:35.10011
001	67	02005413928	20171004	00020258	FA	110000020258	00000941841	00000000200	00000018835	5	2017-11-16 12:54:35.10011
001	68	02005413928	20171004	00020259	FA	110000020259	00000235848	00000000200	00000004718	5	2017-11-16 12:54:35.10011
001	69	03070904243	20171004	00020261	FA	110000020261	00000163078	00000000200	00000003262	5	2017-11-16 12:54:35.10011
001	70	03070904243	20171004	00020262	FA	110000020262	00000109075	00000000200	00000002182	5	2017-11-16 12:54:35.10011
001	71	02005413928	20171030	00021544	FA	110000021544	00000143803	00000000200	00000002876	5	2017-11-16 12:54:35.10011
001	72	03070904243	20171025	00021289	FA	110000021289	00000051775	00000000200	00000001035	5	2017-11-16 12:54:35.10011
001	73	02730974907	20171004	00020267	FA	110000020267	00000252501	00000000200	00000005051	5	2017-11-16 12:54:35.10011
001	74	03071527834	20171025	00021291	FA	110000021291	00000162543	00000000200	00000003251	5	2017-11-16 12:54:35.10011
001	75	02324121726	20171004	00020268	FA	110000020268	00000170883	00000000200	00000003419	5	2017-11-16 12:54:35.10011
001	76	02710028181	20171004	00020269	FA	110000020269	00000040400	00000000200	00000000808	5	2017-11-16 12:54:35.10011
001	77	02021354142	20171004	00020270	FA	110000020270	00000067747	00000000200	00000001355	5	2017-11-16 12:54:35.10011
001	78	02722509257	20171004	00020271	FA	110000020271	00000104754	00000000200	00000002096	5	2017-11-16 12:54:35.10011
001	79	03070904243	20171013	00020784	FA	110000020784	00000270451	00000000200	00000005409	5	2017-11-16 12:54:35.10011
001	80	02021354142	20171004	00020273	FA	110000020273	00000016630	00000000200	00000000333	5	2017-11-16 12:54:35.10011
001	81	02005413928	20171013	00020785	FA	110000020785	00000194954	00000000200	00000003899	5	2017-11-16 12:54:35.10011
001	82	02021354142	20171004	00020274	FA	110000020274	00000016630	00000000200	00000000333	5	2017-11-16 12:54:35.10011
001	83	02730974907	20171025	00021298	FA	110000021298	00000284596	00000000200	00000005691	5	2017-11-16 12:54:35.10011
001	84	03070904243	20171010	00020531	FA	110000020531	00000088742	00000000200	00000001775	5	2017-11-16 12:54:35.10011
001	85	03070904243	20171013	00020787	FA	110000020787	00000085254	00000000200	00000001705	5	2017-11-16 12:54:35.10011
013	2	27232869564	20171002	00006337	OT	001000006337	-0000015585	00000000200	-0000000312	17	2017-11-15 11:40:32.00095
013	3	27952625514	20171005	00006342	OT	001000006342	-0000085146	00000000200	-0000001703	17	2017-11-15 11:40:32.00095
013	4	27000000006	20171006	00012167	OT	001000012167	-0000078357	00000000200	-0000001567	17	2017-11-15 11:40:32.00095
013	5	20267029114	20171006	00012168	OT	001000012168	-0000043486	00000000200	-0000000870	17	2017-11-15 11:40:32.00095
013	6	27230149416	20171007	00006347	OT	001000006347	-0000051298	00000000200	-0000001026	17	2017-11-15 11:40:32.00095
013	7	30670364603	20171009	00006349	OT	001000006349	-0000023864	00000000200	-0000000477	17	2017-11-15 11:40:32.00095
013	8	27346653502	20171009	00012172	OT	001000012172	-0000112940	00000000200	-0000002259	17	2017-11-15 11:40:32.00095
013	9	27363206641	20171009	00012173	OT	001000012173	-0000275910	00000000200	-0000005518	17	2017-11-15 11:40:32.00095
013	10	20054139285	20171010	00006353	OT	001000006353	-0000169410	00000000200	-0000003388	17	2017-11-15 11:40:32.00095
013	11	20226157833	20171010	00006354	OT	001000006354	-0000080007	00000000200	-0000001600	17	2017-11-15 11:40:32.00095
013	12	27363206641	20171013	00012181	OT	001000012181	-0000036569	00000000200	-0000000731	17	2017-11-15 11:40:32.00095
013	13	27056603560	20171013	00006366	OT	001000006366	-0000018284	00000000200	-0000000366	17	2017-11-15 11:40:32.00095
013	14	23289795464	20171013	00006367	OT	001000006367	-0000036569	00000000200	-0000000731	17	2017-11-15 11:40:32.00095
013	15	23289795464	20171013	00006368	OT	001000006368	-0000036569	00000000200	-0000000731	17	2017-11-15 11:40:32.00095
013	16	20118857020	20171013	00012183	OT	001000012183	-0000009142	00000000200	-0000000183	17	2017-11-15 11:40:32.00095
013	17	30714924644	20171013	00012184	OT	001000012184	-0000009142	00000000200	-0000000183	17	2017-11-15 11:40:32.00095
013	18	20078122316	20171013	00006369	OT	001000006369	-0000066437	00000000200	-0000001329	17	2017-11-15 11:40:32.00095
013	19	27394430655	20171013	00012185	OT	001000012185	-0000009868	00000000200	-0000000197	17	2017-11-15 11:40:32.00095
013	20	20345487035	20171019	00006374	OT	001000006374	-0000015985	00000000600	-0000000959	17	2017-11-15 11:40:32.00095
013	21	20305781720	20171020	00006375	OT	001000006375	-0000519620	00000000200	-0000010392	17	2017-11-15 11:40:32.00095
013	22	27297722927	20171023	00012196	OT	001000012196	-0000023399	00000000200	-0000000468	17	2017-11-15 11:40:32.00095
013	23	27044920102	20171024	00012198	OT	001000012198	-0000009566	00000000200	-0000000191	17	2017-11-15 11:40:32.00095
013	24	27229348774	20171024	00012199	OT	001000012199	-0000025264	00000000200	-0000000505	17	2017-11-15 11:40:32.00095
013	25	27316072483	20171024	00006379	OT	001000006379	-0000024379	00000000200	-0000000488	17	2017-11-15 11:40:32.00095
013	26	20226157833	20171024	00006380	OT	001000006380	-0000057233	00000000200	-0000001145	17	2017-11-15 11:40:32.00095
013	27	27292122239	20171026	00012203	OT	001000012203	-0000034570	00000000200	-0000000691	17	2017-11-15 11:40:32.00095
013	28	27394430655	20171002	00226251	FA	001000226251	00000047196	00000000200	00000000944	17	2017-11-15 11:40:32.00095
013	29	30609719954	20171002	00098222	FA	001000098222	00000255173	00000000200	00000005103	17	2017-11-15 11:40:32.00095
013	30	30714735817	20171002	00098230	FA	001000098230	00000270275	00000000200	00000005406	17	2017-11-15 11:40:32.00095
013	31	30712398562	20171002	00098231	FA	001000098231	00000142000	00000000200	00000002840	17	2017-11-15 11:40:32.00095
013	32	20226157833	20171002	00098235	FA	001000098235	00000075179	00000000200	00000001504	17	2017-11-15 11:40:32.00095
013	33	27952625514	20171002	00098236	FA	001000098236	00000668948	00000000200	00000013379	17	2017-11-15 11:40:32.00095
013	34	27952625514	20171002	00098237	FA	001000098237	00000055006	00000000200	00000001100	17	2017-11-15 11:40:32.00095
013	35	23289795464	20171002	00098238	FA	001000098238	00000247320	00000000200	00000004946	17	2017-11-15 11:40:32.00095
013	36	27173955451	20171002	00098239	FA	001000098239	00000140762	00000000200	00000002815	17	2017-11-15 11:40:32.00095
013	37	20305781720	20171002	00098240	FA	001000098240	00000485293	00000000200	00000009706	17	2017-11-15 11:40:32.00095
013	38	27316072483	20171002	00098241	FA	001000098241	00000038335	00000000200	00000000767	17	2017-11-15 11:40:32.00095
013	39	27056603560	20171002	00098242	FA	001000098242	00000389512	00000000200	00000007790	17	2017-11-15 11:40:32.00095
013	40	20274035170	20171002	00098243	FA	001000098243	00000178829	00000000200	00000003577	17	2017-11-15 11:40:32.00095
013	41	20073261806	20171002	00098244	FA	001000098244	00000641730	00000000200	00000012835	17	2017-11-15 11:40:32.00095
013	42	27232869564	20171002	00098245	FA	001000098245	00000219488	00000000200	00000004390	17	2017-11-15 11:40:32.00095
013	43	23289795464	20171002	00098246	FA	001000098246	00000085796	00000000200	00000001716	17	2017-11-15 11:40:32.00095
013	44	27163184732	20171002	00098248	FA	001000098248	00000103191	00000000200	00000002064	17	2017-11-15 11:40:32.00095
013	45	20054139285	20171002	00098249	FA	001000098249	00000225380	00000000200	00000004508	17	2017-11-15 11:40:32.00095
013	46	27313340339	20171002	00226239	FA	001000226239	00000171781	00000000200	00000003436	17	2017-11-15 11:40:32.00095
013	47	27346653502	20171002	00226240	FA	001000226240	00000128875	00000000200	00000002578	17	2017-11-15 11:40:32.00095
013	48	27000000006	20171002	00226241	FA	001000226241	00000112337	00000000200	00000002247	17	2017-11-15 11:40:32.00095
013	49	27367571123	20171002	00226242	FA	001000226242	00000117940	00000000200	00000002359	17	2017-11-15 11:40:32.00095
013	50	27297722927	20171002	00226243	FA	001000226243	00000360605	00000000200	00000007212	17	2017-11-15 11:40:32.00095
013	51	30714924644	20171002	00226244	FA	001000226244	00000070156	00000000200	00000001403	17	2017-11-15 11:40:32.00095
013	52	27363206641	20171002	00226245	FA	001000226245	00000304268	00000000200	00000006085	17	2017-11-15 11:40:32.00095
013	53	27305781466	20171002	00226246	FA	001000226246	00000064173	00000000200	00000001283	17	2017-11-15 11:40:32.00095
013	54	27248176607	20171002	00226247	FA	001000226247	00000140231	00000000200	00000002805	17	2017-11-15 11:40:32.00095
013	55	23331976199	20171002	00226248	FA	001000226248	00000196673	00000000200	00000003933	17	2017-11-15 11:40:32.00095
013	56	27044920102	20171002	00226250	FA	001000226250	00000068557	00000000200	00000001371	17	2017-11-15 11:40:32.00095
013	57	27950274226	20171002	00226252	FA	001000226252	00000033219	00000000200	00000000664	17	2017-11-15 11:40:32.00095
013	58	24203397271	20171002	00023278	FA	001100023278	00000101563	00000000600	00000006094	17	2017-11-15 11:40:32.00095
013	59	20290347158	20171002	00023281	FA	001100023281	00000033219	00000000600	00000001993	17	2017-11-15 11:40:32.00095
013	60	27274033784	20171003	00226282	FA	001000226282	00000134111	00000000600	00000008047	17	2017-11-15 11:40:32.00095
013	61	27240625070	20171003	00226283	FA	001000226283	00000035722	00000000600	00000002143	17	2017-11-15 11:40:32.00095
013	62	30714049069	20171003	00009238	FA	001100009238	00000117304	00000000200	00000002346	17	2017-11-15 11:40:32.00095
001	86	02795262551	20171010	00020532	FA	110000020532	00000047985	00000000200	00000000959	5	2017-11-16 12:54:35.10011
001	87	03070904243	20171013	00020788	FA	110000020788	00000175937	00000000200	00000003519	5	2017-11-16 12:54:35.10011
001	88	02005413928	20171004	00020277	FA	110000020277	00000046579	00000000200	00000000931	5	2017-11-16 12:54:35.10011
001	89	02705660356	20171004	00020278	FA	110000020278	00000070677	00000000200	00000001414	5	2017-11-16 12:54:35.10011
001	90	03070904243	20171013	00020790	FA	110000020790	00000014157	00000000200	00000000283	5	2017-11-16 12:54:35.10011
001	91	02705660356	20171004	00020279	FA	110000020279	00000198862	00000000200	00000003976	5	2017-11-16 12:54:35.10011
001	92	03070904243	20171025	00021303	FA	110000021303	00000012013	00000000200	00000000240	5	2017-11-16 12:54:35.10011
001	93	03071527834	20171004	00020280	FA	110000020280	00000213957	00000000200	00000004279	5	2017-11-16 12:54:35.10011
001	94	02027403517	20171025	00021304	FA	110000021304	00000293129	00000000200	00000005864	5	2017-11-16 12:54:35.10011
001	95	02005413928	20171004	00020281	FA	110000020281	00000945503	00000000200	00000018909	5	2017-11-16 12:54:35.10011
001	96	02027403517	20171025	00021305	FA	110000021305	00000135467	00000000200	00000002709	5	2017-11-16 12:54:35.10011
001	97	03071527834	20171031	00021561	FA	110000021561	00000195200	00000000200	00000003904	5	2017-11-16 12:54:35.10011
001	98	02716318473	20171031	00021562	FA	110000021562	00000205248	00000000200	00000004104	5	2017-11-16 12:54:35.10011
001	99	03070904243	20171031	00021563	FA	110000021563	00000113397	00000000200	00000002268	5	2017-11-16 12:54:35.10011
001	100	02005413928	20171025	00021309	FA	110000021309	00001211650	00000000200	00000024231	5	2017-11-16 12:54:35.10011
001	101	02005413928	20171025	00021310	FA	110000021310	00000605766	00000000200	00000012116	5	2017-11-16 12:54:35.10011
001	102	02005413928	20171025	00021311	FA	110000021311	00000133781	00000000200	00000002676	5	2017-11-16 12:54:35.10011
001	103	02706435080	20171025	00021312	FA	110000021312	00000059004	00000000200	00000001181	5	2017-11-16 12:54:35.10011
001	104	02722271314	20171014	00020801	FA	110000020801	00000137299	00000000200	00000002746	5	2017-11-16 12:54:35.10011
001	105	02795262551	20171025	00021313	FA	110000021313	00000231268	00000000200	00000004628	5	2017-11-16 12:54:35.10011
001	106	03071527834	20171025	00021314	FA	110000021314	00000078318	00000000200	00000001566	5	2017-11-16 12:54:35.10011
001	107	02005413928	20171025	00021315	FA	110000021315	00000044360	00000000200	00000000888	5	2017-11-16 12:54:35.10011
001	108	02027403517	20171025	00021316	FA	110000021316	00000476439	00000000200	00000009529	5	2017-11-16 12:54:35.10011
001	109	02027403517	20171025	00021317	FA	110000021317	00000037195	00000000200	00000000743	5	2017-11-16 12:54:35.10011
001	110	02027403517	20171025	00021322	FA	110000021322	00000443276	00000000200	00000008865	5	2017-11-16 12:54:35.10011
001	111	02027403517	20171025	00021323	FA	110000021323	00000051631	00000000200	00000001032	5	2017-11-16 12:54:35.10011
001	112	02027403517	20171014	00020814	FA	110000020814	00000547848	00000000200	00000010957	5	2017-11-16 12:54:35.10011
001	113	02016673417	20171014	00020815	FA	110000020815	00000250444	00000000200	00000005008	5	2017-11-16 12:54:35.10011
001	114	02016673417	20171020	00021073	FA	110000021073	00000306410	00000000200	00000006127	5	2017-11-16 12:54:35.10011
001	115	02021354142	20171014	00020818	FA	110000020818	00000080135	00000000200	00000001603	5	2017-11-16 12:54:35.10011
001	116	02706435080	20171020	00021074	FA	110000021074	00000043744	00000000200	00000000875	5	2017-11-16 12:54:35.10011
001	117	02016318459	20171020	00021075	FA	110000021075	00000085110	00000000200	00000001702	5	2017-11-16 12:54:35.10011
001	118	02027403517	20171020	00021076	FA	110000021076	00000325190	00000000200	00000006504	5	2017-11-16 12:54:35.10011
001	119	02328979546	20171020	00021077	FA	110000021077	00001709952	00000000200	00000034199	5	2017-11-16 12:54:35.10011
001	120	02324121634	20171020	00021078	FA	110000021078	00000164844	00000000200	00000003296	5	2017-11-16 12:54:35.10011
001	121	03070904243	20171020	00021079	FA	110000021079	00000028434	00000000200	00000000569	5	2017-11-16 12:54:35.10011
001	122	02716318473	20171020	00021082	FA	110000021082	00000285833	00000000200	00000005718	5	2017-11-16 12:54:35.10011
001	123	02795262551	20171020	00021083	FA	110000021083	00001236702	00000000200	00000024734	5	2017-11-16 12:54:35.10011
001	124	02722271314	20171031	00021595	FA	110000021595	00000054823	00000000200	00000001097	5	2017-11-16 12:54:35.10011
001	125	02795262551	20171020	00021084	FA	110000021084	00000114520	00000000200	00000002290	5	2017-11-16 12:54:35.10011
001	126	03071527834	20171020	00021093	FA	110000021093	00000253904	00000000200	00000005078	5	2017-11-16 12:54:35.10011
001	127	02005413928	20171010	00020583	FA	110000020583	00000252867	00000000200	00000005058	5	2017-11-16 12:54:35.10011
001	128	02731607248	20171020	00021095	FA	110000021095	00000033978	00000000200	00000000680	5	2017-11-16 12:54:35.10011
001	129	02795262551	20171020	00021096	FA	110000021096	00000046966	00000000200	00000000940	5	2017-11-16 12:54:35.10011
001	130	02795262551	20171026	00021352	FA	110000021352	00000362000	00000000200	00000007240	5	2017-11-16 12:54:35.10011
001	131	02022201457	20171026	00021367	FA	110000021367	00000169263	00000000200	00000003386	5	2017-11-16 12:54:35.10011
001	132	02722271314	20171011	00020600	FA	110000020600	00000176302	00000000200	00000003526	5	2017-11-16 12:54:35.10011
001	133	02795262551	20171011	00020603	FA	110000020603	00000485280	00000000200	00000009706	5	2017-11-16 12:54:35.10011
001	134	02795262551	20171011	00020604	FA	110000020604	00000087527	00000000200	00000001751	5	2017-11-16 12:54:35.10011
001	135	02730974907	20171011	00020605	FA	110000020605	00000245348	00000000200	00000004907	5	2017-11-16 12:54:35.10011
001	136	02705660356	20171011	00020606	FA	110000020606	00000256083	00000000200	00000005123	5	2017-11-16 12:54:35.10011
001	137	02728019123	20171011	00020608	FA	110000020608	00000231904	00000000200	00000004638	5	2017-11-16 12:54:35.10011
001	138	02027403517	20171011	00020614	FA	110000020614	00000710705	00000000200	00000014213	5	2017-11-16 12:54:35.10011
001	139	02021354142	20171006	00020360	FA	110000020360	00000053636	00000000200	00000001073	5	2017-11-16 12:54:35.10011
001	140	03067036231	20171011	00020617	FA	110000020617	00000071680	00000000200	00000001434	5	2017-11-16 12:54:35.10011
001	141	02005413928	20171011	00020618	FA	110000020618	00001071371	00000000200	00000021429	5	2017-11-16 12:54:35.10011
001	142	02005413928	20171011	00020619	FA	110000020619	00000392671	00000000200	00000007853	5	2017-11-16 12:54:35.10011
001	143	02005413928	20171011	00020620	FA	110000020620	00000125596	00000000200	00000002512	5	2017-11-16 12:54:35.10011
001	144	02324121634	20171017	00020876	FA	110000020876	00000075315	00000000200	00000001506	5	2017-11-16 12:54:35.10011
001	145	03070904243	20171011	00020621	FA	110000020621	00000049383	00000000200	00000000988	5	2017-11-16 12:54:35.10011
001	146	02722271314	20171017	00020877	FA	110000020877	00000012210	00000000200	00000000244	5	2017-11-16 12:54:35.10011
001	147	03070904243	20171017	00020879	FA	110000020879	00000030387	00000000200	00000000608	5	2017-11-16 12:54:35.10011
001	148	02027403517	20171011	00020624	FA	110000020624	00000008082	00000000200	00000000162	5	2017-11-16 12:54:35.10011
001	149	02795262551	20171017	00020880	FA	110000020880	00000176432	00000000200	00000003529	5	2017-11-16 12:54:35.10011
001	150	02728019123	20171011	00020625	FA	110000020625	00000090049	00000000200	00000001801	5	2017-11-16 12:54:35.10011
001	151	02795262551	20171017	00020881	FA	110000020881	00000543000	00000000200	00000010860	5	2017-11-16 12:54:35.10011
001	152	02706435080	20171021	00021137	FA	110000021137	00000102988	00000000200	00000002059	5	2017-11-16 12:54:35.10011
001	153	02706435080	20171011	00020626	FA	110000020626	00000069276	00000000200	00000001385	5	2017-11-16 12:54:35.10011
001	154	02016673417	20171021	00021138	FA	110000021138	00000070419	00000000200	00000001409	5	2017-11-16 12:54:35.10011
001	155	02716318473	20171021	00021139	FA	110000021139	00000033800	00000000200	00000000676	5	2017-11-16 12:54:35.10011
001	156	02027403517	20171021	00021140	FA	110000021140	00000315682	00000000200	00000006313	5	2017-11-16 12:54:35.10011
001	157	03071527834	20171027	00021397	FA	110000021397	00000141162	00000000200	00000002823	5	2017-11-16 12:54:35.10011
001	158	03070904243	20171006	00020374	FA	110000020374	00000219204	00000000200	00000004384	5	2017-11-16 12:54:35.10011
001	159	03071527834	20171021	00021142	FA	110000021142	00001134938	00000000200	00000022698	5	2017-11-16 12:54:35.10011
001	160	02016673417	20171006	00020375	FA	110000020375	00000033436	00000000200	00000000668	5	2017-11-16 12:54:35.10011
001	161	03070904243	20171021	00021143	FA	110000021143	00000418667	00000000200	00000008373	5	2017-11-16 12:54:35.10011
001	162	02328979546	20171027	00021399	FA	110000021399	00002517971	00000000200	00000050359	5	2017-11-16 12:54:35.10011
001	163	03071527834	20171006	00020376	FA	110000020376	00000281982	00000000200	00000005640	5	2017-11-16 12:54:35.10011
001	164	02017395574	20171027	00021400	FA	110000021400	00000029823	00000000200	00000000597	5	2017-11-16 12:54:35.10011
001	165	03070904243	20171027	00021401	FA	110000021401	00000084105	00000000200	00000001682	5	2017-11-16 12:54:35.10011
001	166	02795262551	20171006	00020378	FA	110000020378	00000253250	00000000200	00000005065	5	2017-11-16 12:54:35.10011
001	167	02795262551	20171017	00020890	FA	110000020890	00000009360	00000000200	00000000187	5	2017-11-16 12:54:35.10011
001	168	03071527834	20171027	00021402	FA	110000021402	00000017300	00000000200	00000000346	5	2017-11-16 12:54:35.10011
001	169	02324121634	20171027	00021403	FA	110000021403	00000230278	00000000200	00000004605	5	2017-11-16 12:54:35.10011
001	170	02328979546	20171006	00020381	FA	110000020381	00000080340	00000000200	00000001607	5	2017-11-16 12:54:35.10011
001	171	02731607248	20171027	00021410	FA	110000021410	00000096104	00000000200	00000001922	5	2017-11-16 12:54:35.10011
001	172	02730974907	20171017	00020901	FA	110000020901	00000046707	00000000200	00000000934	5	2017-11-16 12:54:35.10011
001	173	02021354142	20171021	00021163	FA	110000021163	00000038612	00000000200	00000000772	5	2017-11-16 12:54:35.10011
001	174	03070904243	20171012	00020676	FA	110000020676	00000132082	00000000200	00000002642	5	2017-11-16 12:54:35.10011
001	175	03070904243	20171012	00020677	FA	110000020677	00000045892	00000000200	00000000918	5	2017-11-16 12:54:35.10011
001	176	02016673417	20171007	00020423	FA	110000020423	00000179016	00000000200	00000003581	5	2017-11-16 12:54:35.10011
001	177	02725975327	20171023	00021191	FA	110000021191	00000067015	00000000200	00000001340	5	2017-11-16 12:54:35.10011
001	178	02021354142	20171017	00020937	FA	110000020937	00000035880	00000000200	00000000718	5	2017-11-16 12:54:35.10011
001	179	03070904243	20171007	00020428	FA	110000020428	00000091241	00000000200	00000001825	5	2017-11-16 12:54:35.10011
001	180	02712594470	20171007	00020429	FA	110000020429	00000072799	00000000200	00000001456	5	2017-11-16 12:54:35.10011
001	181	02027403517	20171028	00021455	FA	110000021455	00000402905	00000000200	00000008058	5	2017-11-16 12:54:35.10011
001	182	02795262551	20171018	00020944	FA	110000020944	00000312048	00000000200	00000006241	5	2017-11-16 12:54:35.10011
001	183	02027403517	20171028	00021456	FA	110000021456	00000189691	00000000200	00000003794	5	2017-11-16 12:54:35.10011
001	184	03067036460	20171028	00021457	FA	110000021457	00000285576	00000000200	00000005712	5	2017-11-16 12:54:35.10011
001	185	02795262551	20171007	00020434	FA	110000020434	00000141252	00000000200	00000002825	5	2017-11-16 12:54:35.10011
001	186	02795262551	20171028	00021458	FA	110000021458	00000928563	00000000200	00000018571	5	2017-11-16 12:54:35.10011
001	187	02725975327	20171012	00020691	FA	110000020691	00000063500	00000000200	00000001270	5	2017-11-16 12:54:35.10011
001	188	02016673417	20171028	00021460	FA	110000021460	00000046272	00000000200	00000000925	5	2017-11-16 12:54:35.10011
001	189	02731607248	20171028	00021461	FA	110000021461	00000104206	00000000200	00000002084	5	2017-11-16 12:54:35.10011
001	190	03070904243	20171003	00020183	FA	110000020183	00000260094	00000000200	00000005202	5	2017-11-16 12:54:35.10011
001	191	02722271314	20171018	00020951	FA	110000020951	00000453614	00000000200	00000009072	5	2017-11-16 12:54:35.10011
001	192	02016673417	20171028	00021463	FA	110000021463	00000272903	00000000200	00000005458	5	2017-11-16 12:54:35.10011
001	193	03070904243	20171003	00020184	FA	110000020184	00000029731	00000000200	00000000595	5	2017-11-16 12:54:35.10011
001	194	02795262551	20171007	00020440	FA	110000020440	00000305454	00000000200	00000006110	5	2017-11-16 12:54:35.10011
001	195	03070904243	20171028	00021464	FA	110000021464	00000093956	00000000200	00000001879	5	2017-11-16 12:54:35.10011
001	196	02706435080	20171003	00020185	FA	110000020185	00000040017	00000000200	00000000800	5	2017-11-16 12:54:35.10011
001	197	02027403517	20171007	00020441	FA	110000020441	00000428990	00000000200	00000008579	5	2017-11-16 12:54:35.10011
001	198	02712594470	20171018	00020954	FA	110000020954	00000150480	00000000200	00000003009	5	2017-11-16 12:54:35.10011
001	199	02795262551	20171018	00020955	FA	110000020955	00000082328	00000000200	00000001647	5	2017-11-16 12:54:35.10011
001	200	02712594470	20171003	00020191	FA	110000020191	00000069966	00000000200	00000001399	5	2017-11-16 12:54:35.10011
001	201	03070904243	20171018	00020959	FA	110000020959	00000236856	00000000200	00000004737	5	2017-11-16 12:54:35.10011
001	202	02722271314	20171018	00020961	FA	110000020961	00000453614	00000000200	00000009072	5	2017-11-16 12:54:35.10011
001	203	02731607248	20171012	00020706	FA	110000020706	00000504321	00000000200	00000010087	5	2017-11-16 12:54:35.10011
001	204	02728019123	20171018	00020962	FA	110000020962	00000149808	00000000200	00000002995	5	2017-11-16 12:54:35.10011
001	205	02731607248	20171012	00020707	FA	110000020707	00000152914	00000000200	00000003059	5	2017-11-16 12:54:35.10011
001	206	02722271314	20171018	00020963	FA	110000020963	00000137506	00000000200	00000002750	5	2017-11-16 12:54:35.10011
001	207	02324121726	20171018	00020964	FA	110000020964	00000125968	00000000200	00000002519	5	2017-11-16 12:54:35.10011
001	208	02005413928	20171023	00021220	FA	110000021220	00000071053	00000000200	00000001421	5	2017-11-16 12:54:35.10011
001	209	02706435080	20171007	00020453	FA	110000020453	00000235961	00000000200	00000004718	5	2017-11-16 12:54:35.10011
001	210	02705660356	20171018	00020965	FA	110000020965	00000387437	00000000200	00000007750	5	2017-11-16 12:54:35.10011
001	211	02731607248	20171012	00020711	FA	110000020711	00000420177	00000000200	00000008404	5	2017-11-16 12:54:35.10011
001	212	02717395545	20171018	00020967	FA	110000020967	00000042541	00000000200	00000000850	5	2017-11-16 12:54:35.10011
001	213	02722271314	20171018	00020968	FA	110000020968	00000059653	00000000200	00000001193	5	2017-11-16 12:54:35.10011
001	214	02021354142	20171007	00020459	FA	110000020459	00000189348	00000000200	00000003787	5	2017-11-16 12:54:35.10011
001	215	02316317382	20171018	00020971	FA	110000020971	00000286847	00000000200	00000005736	5	2017-11-16 12:54:35.10011
001	216	03071527834	20171018	00020973	FA	110000020973	00000051900	00000000200	00000001038	5	2017-11-16 12:54:35.10011
001	217	02728019123	20171024	00021229	FA	110000021229	00000498321	00000000200	00000009966	5	2017-11-16 12:54:35.10011
001	218	02728019123	20171018	00020974	FA	110000020974	00000232818	00000000200	00000004657	5	2017-11-16 12:54:35.10011
001	219	03070904243	20171024	00021233	FA	110000021233	00000020672	00000000200	00000000413	5	2017-11-16 12:54:35.10011
001	220	02021354142	20171018	00020980	FA	110000020980	00000148264	00000000200	00000002965	5	2017-11-16 12:54:35.10011
001	221	02730974907	20171018	00020981	FA	110000020981	00000336754	00000000200	00000006735	5	2017-11-16 12:54:35.10011
001	222	02027403517	20171018	00020982	FA	110000020982	00000128806	00000000200	00000002576	5	2017-11-16 12:54:35.10011
001	223	02027403517	20171018	00020983	FA	110000020983	00000351761	00000000200	00000007034	5	2017-11-16 12:54:35.10011
001	224	02795262551	20171009	00020472	FA	110000020472	00000222306	00000000200	00000004447	5	2017-11-16 12:54:35.10011
001	225	02005413928	20171018	00020985	FA	110000020985	00001191531	00000000200	00000023834	5	2017-11-16 12:54:35.10011
001	226	02722271314	20171013	00020730	FA	110000020730	00000468605	00000000200	00000009372	5	2017-11-16 12:54:35.10011
001	227	02005413928	20171018	00020986	FA	110000020986	00000457428	00000000200	00000009148	5	2017-11-16 12:54:35.10011
001	228	02016673417	20171013	00020731	FA	110000020731	00000158400	00000000200	00000003168	5	2017-11-16 12:54:35.10011
001	229	02005413928	20171018	00020987	FA	110000020987	00000130812	00000000200	00000002616	5	2017-11-16 12:54:35.10011
001	230	02005413928	20171018	00020988	FA	110000020988	00000480103	00000000200	00000009601	5	2017-11-16 12:54:35.10011
001	231	02725975327	20171024	00021244	FA	110000021244	00000050781	00000000200	00000001016	5	2017-11-16 12:54:35.10011
001	232	20011994378	20171018	00019971	FA	110000019971	00000341918	00000000200	00000006838	5	2017-11-16 12:54:35.10011
001	233	02716318446	20171018	00019972	FA	110000019972	00000065407	00000000200	00000001308	5	2017-11-16 12:54:35.10011
001	234	02716318446	20171018	00019973	FA	110000019973	00000125696	00000000200	00000002515	5	2017-11-16 12:54:35.10011
001	235	02704492010	20171018	00019974	FA	110000019974	00000171033	00000000200	00000003420	5	2017-11-16 12:54:35.10011
001	236	02314162209	20171018	00019975	FA	110000019975	00000511697	00000000200	00000010233	5	2017-11-16 12:54:35.10011
001	237	02716318446	20171018	00019976	FA	110000019976	00000003661	00000000200	00000000073	5	2017-11-16 12:54:35.10011
001	238	02094314994	20171025	00020232	FA	110000020232	00000524869	00000000200	00000010497	5	2017-11-16 12:54:35.10011
001	239	02011885702	20171006	00019466	FA	110000019466	00000065523	00000000200	00000001309	5	2017-11-16 12:54:35.10011
001	240	02723927990	20171025	00020234	FA	110000020234	00000134001	00000000200	00000002681	5	2017-11-16 12:54:35.10011
001	241	02011885702	20171006	00019467	FA	110000019467	00000006888	00000000200	00000000138	5	2017-11-16 12:54:35.10011
001	242	02716446176	20171030	00020493	FA	110000020493	00000035808	00000000200	00000000716	5	2017-11-16 12:54:35.10011
001	243	02794226956	20171025	00020240	FA	110000020240	00000129001	00000000200	00000002580	5	2017-11-16 12:54:35.10011
001	244	02729398531	20171025	00020241	FA	110000020241	00000049287	00000000200	00000000985	5	2017-11-16 12:54:35.10011
001	245	02716318446	20171025	00020242	FA	110000020242	00000423804	00000000200	00000008476	5	2017-11-16 12:54:35.10011
001	246	02716318446	20171025	00020243	FA	110000020243	00000072978	00000000200	00000001460	5	2017-11-16 12:54:35.10011
001	247	02022525047	20171007	00019476	FA	110000019476	00000063444	00000000200	00000001269	5	2017-11-16 12:54:35.10011
001	248	02716318446	20171025	00020244	FA	110000020244	00000070789	00000000200	00000001416	5	2017-11-16 12:54:35.10011
001	249	02716318446	20171025	00020245	FA	110000020245	00000081270	00000000200	00000001625	5	2017-11-16 12:54:35.10011
001	250	02011885702	20171007	00019478	FA	110000019478	00000145771	00000000200	00000002916	5	2017-11-16 12:54:35.10011
001	251	02704161991	20171025	00020246	FA	110000020246	00000156687	00000000200	00000003135	5	2017-11-16 12:54:35.10011
001	252	27216609528	20171031	00020502	FA	110000020502	00000230800	00000000200	00000004616	5	2017-11-16 12:54:35.10011
001	253	02723927990	20171007	00019479	FA	110000019479	00000110729	00000000200	00000002215	5	2017-11-16 12:54:35.10011
001	254	02022525047	20171025	00020251	FA	110000020251	00000042252	00000000200	00000000845	5	2017-11-16 12:54:35.10011
001	255	02007331687	20171012	00019740	FA	110000019740	00000012690	00000000200	00000000254	5	2017-11-16 12:54:35.10011
013	63	30712127895	20171004	00009239	FA	001100009239	00000208359	00000000200	00000004167	17	2017-11-15 11:40:32.00095
013	64	20147388323	20171004	00009250	FA	001100009250	00000094120	00000000200	00000001882	17	2017-11-15 11:40:32.00095
013	65	20110567570	20171004	00009252	FA	001100009252	00000641988	00000000200	00000012840	17	2017-11-15 11:40:32.00095
013	66	20227583232	20171004	00009253	FA	001100009253	00000119032	00000000200	00000002381	17	2017-11-15 11:40:32.00095
013	67	27224236773	20171004	00009269	FA	001100009269	00000047457	00000000200	00000000949	17	2017-11-15 11:40:32.00095
013	68	30670336073	20171004	00009281	FA	001100009281	00000063812	00000000200	00000001276	17	2017-11-15 11:40:32.00095
013	69	27165291269	20171004	00023322	FA	001100023322	00000064127	00000000600	00000003848	17	2017-11-15 11:40:32.00095
013	70	27300258544	20171004	00023330	FA	001100023330	00000069813	00000000600	00000004189	17	2017-11-15 11:40:32.00095
013	71	27063634234	20171004	00023353	FA	001100023353	00000044582	00000000600	00000002675	17	2017-11-15 11:40:32.00095
013	72	30711644578	20171005	00098314	FA	001000098314	00000241638	00000000200	00000004833	17	2017-11-15 11:40:32.00095
013	73	30645715116	20171005	00098284	FA	001000098284	00000910836	00000000200	00000018217	17	2017-11-15 11:40:32.00095
013	74	20226157833	20171005	00098285	FA	001000098285	00000114633	00000000200	00000002293	17	2017-11-15 11:40:32.00095
013	75	27952625514	20171005	00098286	FA	001000098286	00001553283	00000000200	00000031066	17	2017-11-15 11:40:32.00095
013	76	27952625514	20171005	00098287	FA	001000098287	00000019736	00000000200	00000000395	17	2017-11-15 11:40:32.00095
013	77	20054139285	20171005	00098288	FA	001000098288	00000016941	00000000200	00000000339	17	2017-11-15 11:40:32.00095
013	78	20054139285	20171005	00098289	FA	001000098289	00000143577	00000000200	00000002872	17	2017-11-15 11:40:32.00095
013	79	23289795464	20171005	00098290	FA	001000098290	00000249935	00000000200	00000004999	17	2017-11-15 11:40:32.00095
013	80	23289795464	20171005	00098291	FA	001000098291	00000338508	00000000200	00000006770	17	2017-11-15 11:40:32.00095
013	81	27232869564	20171005	00098292	FA	001000098292	00000381862	00000000200	00000007637	17	2017-11-15 11:40:32.00095
013	82	27232869564	20171005	00098293	FA	001000098293	00000016941	00000000200	00000000339	17	2017-11-15 11:40:32.00095
013	83	20073261806	20171005	00098294	FA	001000098294	00000317284	00000000200	00000006346	17	2017-11-15 11:40:32.00095
013	84	27056603560	20171005	00098295	FA	001000098295	00000737248	00000000200	00000014745	17	2017-11-15 11:40:32.00095
013	85	27056603560	20171005	00098296	FA	001000098296	00000016941	00000000200	00000000339	17	2017-11-15 11:40:32.00095
013	86	20274035170	20171005	00098297	FA	001000098297	00000153589	00000000200	00000003072	17	2017-11-15 11:40:32.00095
013	87	30670364603	20171005	00098298	FA	001000098298	00000059875	00000000200	00000001198	17	2017-11-15 11:40:32.00095
013	88	27316072483	20171005	00098301	FA	001000098301	00000066006	00000000200	00000001320	17	2017-11-15 11:40:32.00095
013	89	20305781720	20171005	00098302	FA	001000098302	00000309545	00000000200	00000006191	17	2017-11-15 11:40:32.00095
013	90	23289795464	20171005	00098303	FA	001000098303	00000016941	00000000200	00000000339	17	2017-11-15 11:40:32.00095
013	91	23289795464	20171005	00098304	FA	001000098304	00000016941	00000000200	00000000339	17	2017-11-15 11:40:32.00095
013	92	27367571123	20171005	00098305	FA	001000098305	00000232490	00000000200	00000004650	17	2017-11-15 11:40:32.00095
013	93	27163184732	20171005	00098306	FA	001000098306	00000125144	00000000200	00000002503	17	2017-11-15 11:40:32.00095
013	94	23289795464	20171005	00098307	FA	001000098307	00000013324	00000000200	00000000266	17	2017-11-15 11:40:32.00095
013	95	30715278347	20171005	00098308	FA	001000098308	00000252934	00000000200	00000005059	17	2017-11-15 11:40:32.00095
013	96	20166734178	20171005	00098310	FA	001000098310	00000066323	00000000200	00000001326	17	2017-11-15 11:40:32.00095
013	97	27222713140	20171005	00098311	FA	001000098311	00000018546	00000000200	00000000371	17	2017-11-15 11:40:32.00095
013	98	27064350809	20171005	00098312	FA	001000098312	00000116335	00000000200	00000002327	17	2017-11-15 11:40:32.00095
013	99	27188206463	20171005	00098313	FA	001000098313	00000111883	00000000200	00000002238	17	2017-11-15 11:40:32.00095
013	100	27346653502	20171005	00226307	FA	001000226307	00000081645	00000000200	00000001633	17	2017-11-15 11:40:32.00095
013	101	27000000006	20171005	00226308	FA	001000226308	00000078357	00000000200	00000001567	17	2017-11-15 11:40:32.00095
013	102	20267029114	20171005	00226309	FA	001000226309	00000043486	00000000200	00000000870	17	2017-11-15 11:40:32.00095
013	103	27313340339	20171005	00226310	FA	001000226310	00000063325	00000000200	00000001267	17	2017-11-15 11:40:32.00095
013	104	27248176607	20171005	00226311	FA	001000226311	00000117727	00000000200	00000002355	17	2017-11-15 11:40:32.00095
013	105	27305781466	20171005	00226312	FA	001000226312	00000038693	00000000200	00000000774	17	2017-11-15 11:40:32.00095
013	106	27950274226	20171005	00226313	FA	001000226313	00000025318	00000000200	00000000506	17	2017-11-15 11:40:32.00095
013	107	23331976199	20171005	00226314	FA	001000226314	00000222981	00000000200	00000004460	17	2017-11-15 11:40:32.00095
013	108	27044920102	20171005	00226315	FA	001000226315	00000023802	00000000200	00000000476	17	2017-11-15 11:40:32.00095
013	109	27394430655	20171005	00226316	FA	001000226316	00000071912	00000000200	00000001438	17	2017-11-15 11:40:32.00095
013	110	27297722927	20171005	00226317	FA	001000226317	00000218941	00000000200	00000004379	17	2017-11-15 11:40:32.00095
013	111	27363206641	20171005	00226318	FA	001000226318	00000257340	00000000200	00000005147	17	2017-11-15 11:40:32.00095
013	112	27313340754	20171005	00226319	FA	001000226319	00000096593	00000000200	00000001932	17	2017-11-15 11:40:32.00095
013	113	20313340792	20171005	00226320	FA	001000226320	00000070840	00000000600	00000004250	17	2017-11-15 11:40:32.00095
013	114	20388075806	20171005	00226321	FA	001000226321	00000171835	00000000200	00000003437	17	2017-11-15 11:40:32.00095
013	115	23237095979	20171005	00226323	FA	001000226323	00000041273	00000000200	00000000825	17	2017-11-15 11:40:32.00095
013	116	27237091987	20171005	00226324	FA	001000226324	00000071912	00000000200	00000001438	17	2017-11-15 11:40:32.00095
013	117	27101402156	20171005	00226325	FA	001000226325	00000037145	00000000200	00000000743	17	2017-11-15 11:40:32.00095
013	118	20284642369	20171005	00226326	FA	001000226326	00000026410	00000000200	00000000528	17	2017-11-15 11:40:32.00095
013	119	20271066547	20171005	00226327	FA	001000226327	00000033219	00000000200	00000000664	17	2017-11-15 11:40:32.00095
013	120	27121002650	20171005	00226328	FA	001000226328	00000084971	00000000200	00000001699	17	2017-11-15 11:40:32.00095
013	121	20118857020	20171005	00226329	FA	001000226329	00000061214	00000000200	00000001224	17	2017-11-15 11:40:32.00095
013	122	20273644912	20171005	00226330	FA	001000226330	00000054726	00000000200	00000001095	17	2017-11-15 11:40:32.00095
013	123	20082698508	20171005	00226331	FA	001000226331	00000097391	00000000200	00000001948	17	2017-11-15 11:40:32.00095
013	124	20173955449	20171005	00226333	FA	001000226333	00000138349	00000000200	00000002767	17	2017-11-15 11:40:32.00095
013	125	30670359170	20171005	00098317	FA	001000098317	00000369845	00000000200	00000007397	17	2017-11-15 11:40:32.00095
013	126	30609719954	20171005	00098319	FA	001000098319	00000077309	00000000200	00000001546	17	2017-11-15 11:40:32.00095
013	127	30609719954	20171005	00098320	FA	001000098320	00000144248	00000000200	00000002885	17	2017-11-15 11:40:32.00095
013	128	30714735817	20171005	00098328	FA	001000098328	00000319581	00000000200	00000006392	17	2017-11-15 11:40:32.00095
013	129	30712398562	20171005	00098329	FA	001000098329	00000096921	00000000200	00000001938	17	2017-11-15 11:40:32.00095
013	130	20345487035	20171005	00098332	FA	001000098332	00000022431	00000000600	00000001346	17	2017-11-15 11:40:32.00095
013	131	30715459228	20171005	00098334	FA	001000098334	00000026129	00000000600	00000001568	17	2017-11-15 11:40:32.00095
013	132	20300363130	20171005	00098337	FA	001000098337	00000588670	00000000200	00000011773	17	2017-11-15 11:40:32.00095
013	133	20084272648	20171005	00226337	FA	001000226337	00000058804	00000000200	00000001176	17	2017-11-15 11:40:32.00095
013	134	20179005353	20171005	00226354	FA	001000226354	00000038110	00000000600	00000002287	17	2017-11-15 11:40:32.00095
013	135	27224183203	20171005	00226355	FA	001000226355	00000053855	00000000200	00000001077	17	2017-11-15 11:40:32.00095
013	136	27293830199	20171005	00026806	FA	001300026806	00000201145	00000000600	00000012069	17	2017-11-15 11:40:32.00095
013	137	27160615899	20171005	00023358	FA	001100023358	00000071444	00000000600	00000004287	17	2017-11-15 11:40:32.00095
013	138	30542722688	20171005	00009295	FA	001100009295	00000058056	00000000200	00000001161	17	2017-11-15 11:40:32.00095
013	139	27230149416	20171006	00098367	FA	001000098367	00000233536	00000000200	00000004671	17	2017-11-15 11:40:32.00095
013	140	27000000006	20171006	00098366	FA	001000098366	00000093786	00000000200	00000001876	17	2017-11-15 11:40:32.00095
013	141	20073987610	20171006	00226378	FA	001000226378	00000467691	00000000600	00000028061	17	2017-11-15 11:40:32.00095
013	142	20215184596	20171006	00226398	FA	001000226398	00000033219	00000000200	00000000664	17	2017-11-15 11:40:32.00095
013	143	20082121235	20171006	00226408	FA	001000226408	00000107710	00000000200	00000002154	17	2017-11-15 11:40:32.00095
013	144	20940818649	20171006	00023359	FA	001100023359	00000066437	00000000200	00000001329	17	2017-11-15 11:40:32.00095
013	145	20173981342	20171006	00023361	FA	001100023361	00000118324	00000000200	00000002366	17	2017-11-15 11:40:32.00095
013	146	20147959282	20171006	00023365	FA	001100023365	00000084517	00000000200	00000001690	17	2017-11-15 11:40:32.00095
013	147	20269447649	20171006	00023369	FA	001100023369	00000128414	00000000600	00000007705	17	2017-11-15 11:40:32.00095
013	148	20215184596	20171006	00226411	FA	001000226411	00000005159	00000000200	00000000103	17	2017-11-15 11:40:32.00095
013	149	30712127895	20171007	00098370	FA	001000098370	00000346548	00000000200	00000006931	17	2017-11-15 11:40:32.00095
013	150	20147388323	20171007	00098382	FA	001000098382	00000111354	00000000200	00000002227	17	2017-11-15 11:40:32.00095
013	151	27224236773	20171007	00098398	FA	001000098398	00000057102	00000000200	00000001142	17	2017-11-15 11:40:32.00095
013	152	23073219329	20171007	00226420	FA	001000226420	00000041273	00000000600	00000002476	17	2017-11-15 11:40:32.00095
013	153	27300258544	20171007	00226432	FA	001000226432	00000053855	00000000600	00000003231	17	2017-11-15 11:40:32.00095
013	154	27370674057	20171007	00023371	FA	001100023371	00000030951	00000000600	00000001857	17	2017-11-15 11:40:32.00095
013	155	20243042802	20171007	00023372	FA	001100023372	00000288609	00000000200	00000005772	17	2017-11-15 11:40:32.00095
013	156	20173981342	20171007	00023373	FA	001100023373	00000099656	00000000200	00000001993	17	2017-11-15 11:40:32.00095
013	157	20173981342	20171007	00023377	FA	001100023377	00000084607	00000000200	00000001692	17	2017-11-15 11:40:32.00095
013	158	27363206641	20171007	00023381	FA	001100023381	00000092863	00000000200	00000001857	17	2017-11-15 11:40:32.00095
013	159	27042671466	20171007	00009309	FA	001100009309	00000107272	00000000200	00000002145	17	2017-11-15 11:40:32.00095
013	160	27042671466	20171007	00009310	FA	001100009310	00000015985	00000000200	00000000320	17	2017-11-15 11:40:32.00095
013	161	27224183203	20171007	00023383	FA	001100023383	00000033219	00000000200	00000000664	17	2017-11-15 11:40:32.00095
013	162	30670359170	20171009	00098406	FA	001000098406	00000103899	00000000200	00000002078	17	2017-11-15 11:40:32.00095
013	163	30609719954	20171009	00098407	FA	001000098407	00000156919	00000000200	00000003138	17	2017-11-15 11:40:32.00095
013	164	30714735817	20171009	00098415	FA	001000098415	00000215953	00000000200	00000004319	17	2017-11-15 11:40:32.00095
013	165	27952625514	20171009	00098420	FA	001000098420	00000771823	00000000200	00000015436	17	2017-11-15 11:40:32.00095
013	166	27952625514	20171009	00098421	FA	001000098421	00000018595	00000000200	00000000372	17	2017-11-15 11:40:32.00095
013	167	20226157833	20171009	00098422	FA	001000098422	00000080007	00000000200	00000001600	17	2017-11-15 11:40:32.00095
013	168	23289795464	20171009	00098423	FA	001000098423	00000287854	00000000200	00000005757	17	2017-11-15 11:40:32.00095
013	169	27173955451	20171009	00098424	FA	001000098424	00000115439	00000000200	00000002309	17	2017-11-15 11:40:32.00095
013	170	20305781720	20171009	00098425	FA	001000098425	00000427273	00000000200	00000008545	17	2017-11-15 11:40:32.00095
013	171	27316072483	20171009	00098426	FA	001000098426	00000053674	00000000200	00000001073	17	2017-11-15 11:40:32.00095
013	172	27056603560	20171009	00098427	FA	001000098427	00000627918	00000000200	00000012558	17	2017-11-15 11:40:32.00095
013	173	30670364603	20171009	00098428	FA	001000098428	00000212166	00000000200	00000004243	17	2017-11-15 11:40:32.00095
013	174	30670364603	20171009	00098429	FA	001000098429	00000048444	00000000200	00000000969	17	2017-11-15 11:40:32.00095
013	175	20274035170	20171009	00098430	FA	001000098430	00000037145	00000000200	00000000743	17	2017-11-15 11:40:32.00095
013	176	20073261806	20171009	00098431	FA	001000098431	00000309545	00000000200	00000006191	17	2017-11-15 11:40:32.00095
013	177	27232869564	20171009	00098432	FA	001000098432	00000787750	00000000200	00000015755	17	2017-11-15 11:40:32.00095
013	178	23289795464	20171009	00098433	FA	001000098433	00000282539	00000000200	00000005651	17	2017-11-15 11:40:32.00095
013	179	27367571123	20171009	00098434	FA	001000098434	00000161564	00000000200	00000003231	17	2017-11-15 11:40:32.00095
013	180	27163184732	20171009	00098435	FA	001000098435	00000123480	00000000200	00000002470	17	2017-11-15 11:40:32.00095
013	181	20054139285	20171009	00098436	FA	001000098436	00000337852	00000000200	00000006757	17	2017-11-15 11:40:32.00095
013	182	20104656944	20171009	00098437	FA	001000098437	00000171567	00000000200	00000003431	17	2017-11-15 11:40:32.00095
013	183	27346653502	20171009	00226460	FA	001000226460	00000254577	00000000200	00000005092	17	2017-11-15 11:40:32.00095
013	184	27346653502	20171009	00226461	FA	001000226461	00000112940	00000000200	00000002259	17	2017-11-15 11:40:32.00095
013	185	27000000006	20171009	00226462	FA	001000226462	00000141355	00000000200	00000002827	17	2017-11-15 11:40:32.00095
013	186	27313340339	20171009	00226463	FA	001000226463	00000123003	00000000200	00000002460	17	2017-11-15 11:40:32.00095
013	187	27305781466	20171009	00226464	FA	001000226464	00000064173	00000000200	00000001283	17	2017-11-15 11:40:32.00095
013	188	27363206641	20171009	00226465	FA	001000226465	00000275910	00000000200	00000005518	17	2017-11-15 11:40:32.00095
013	189	27363206641	20171009	00226466	FA	001000226466	00000305595	00000000200	00000006112	17	2017-11-15 11:40:32.00095
013	190	30714924644	20171009	00226467	FA	001000226467	00000064173	00000000200	00000001283	17	2017-11-15 11:40:32.00095
013	191	27248176607	20171009	00226468	FA	001000226468	00000135097	00000000200	00000002702	17	2017-11-15 11:40:32.00095
013	192	27297722927	20171009	00226469	FA	001000226469	00000360133	00000000200	00000007203	17	2017-11-15 11:40:32.00095
013	193	27950274226	20171009	00226470	FA	001000226470	00000025271	00000000200	00000000505	17	2017-11-15 11:40:32.00095
013	194	27044920102	20171009	00226471	FA	001000226471	00000047153	00000000200	00000000943	17	2017-11-15 11:40:32.00095
013	195	27394430655	20171009	00226472	FA	001000226472	00000005058	00000000200	00000000101	17	2017-11-15 11:40:32.00095
013	196	23331976199	20171009	00226473	FA	001000226473	00000109285	00000000200	00000002186	17	2017-11-15 11:40:32.00095
013	197	20176333074	20171009	00226474	FA	001000226474	00000348493	00000000200	00000006970	17	2017-11-15 11:40:32.00095
013	198	27229348774	20171009	00226475	FA	001000226475	00000201569	00000000200	00000004031	17	2017-11-15 11:40:32.00095
013	199	33542719449	20171009	00009316	FA	001100009316	00000066688	00000000200	00000001334	17	2017-11-15 11:40:32.00095
013	200	20313340792	20171009	00023389	FA	001100023389	00000033219	00000000600	00000001993	17	2017-11-15 11:40:32.00095
013	201	27240625070	20171010	00226478	FA	001000226478	00000071611	00000000600	00000004297	17	2017-11-15 11:40:32.00095
013	202	27257772123	20171010	00226493	FA	001000226493	00000090431	00000000600	00000005426	17	2017-11-15 11:40:32.00095
013	203	33542719449	20171010	00009319	FA	001100009319	00000100221	00000000200	00000002004	17	2017-11-15 11:40:32.00095
013	204	30712127895	20171011	00098476	FA	001000098476	00000331730	00000000200	00000006635	17	2017-11-15 11:40:32.00095
013	205	20147388323	20171011	00098484	FA	001000098484	00000127656	00000000200	00000002553	17	2017-11-15 11:40:32.00095
013	206	20110567570	20171011	00098485	FA	001000098485	00000744584	00000000200	00000014892	17	2017-11-15 11:40:32.00095
013	207	20227583232	20171011	00098486	FA	001000098486	00000068005	00000000200	00000001360	17	2017-11-15 11:40:32.00095
013	208	27224236773	20171011	00098502	FA	001000098502	00000068358	00000000200	00000001367	17	2017-11-15 11:40:32.00095
013	209	30670336073	20171011	00098513	FA	001000098513	00000075785	00000000200	00000001516	17	2017-11-15 11:40:32.00095
013	210	23073219329	20171011	00226526	FA	001000226526	00000041273	00000000600	00000002476	17	2017-11-15 11:40:32.00095
013	211	27165291269	20171011	00226552	FA	001000226552	00000160523	00000000600	00000009631	17	2017-11-15 11:40:32.00095
013	212	20147959282	20171011	00226554	FA	001000226554	00000100502	00000000200	00000002010	17	2017-11-15 11:40:32.00095
013	213	27300258544	20171011	00226559	FA	001000226559	00000199629	00000000600	00000011978	17	2017-11-15 11:40:32.00095
013	214	27363206641	20171011	00023401	FA	001100023401	00000056470	00000000200	00000001129	17	2017-11-15 11:40:32.00095
013	215	27346653502	20171011	00023402	FA	001100023402	00000028235	00000000200	00000000565	17	2017-11-15 11:40:32.00095
013	216	20054139285	20171011	00009326	FA	001100009326	00000056470	00000000200	00000001129	17	2017-11-15 11:40:32.00095
013	217	30707057633	20171011	00009327	FA	001100009327	00000179390	00000000200	00000003588	17	2017-11-15 11:40:32.00095
013	218	20226157833	20171012	00098525	FA	001000098525	00000225962	00000000200	00000004519	17	2017-11-15 11:40:32.00095
013	219	27952625514	20171012	00098526	FA	001000098526	00000759556	00000000200	00000015191	17	2017-11-15 11:40:32.00095
013	220	27952625514	20171012	00098527	FA	001000098527	00000128453	00000000200	00000002569	17	2017-11-15 11:40:32.00095
013	221	20073261806	20171012	00098528	FA	001000098528	00000619089	00000000200	00000012382	17	2017-11-15 11:40:32.00095
013	222	20054139285	20171012	00098529	FA	001000098529	00000275180	00000000200	00000005504	17	2017-11-15 11:40:32.00095
013	223	27316072483	20171012	00098530	FA	001000098530	00000066006	00000000200	00000001320	17	2017-11-15 11:40:32.00095
013	224	27056603560	20171012	00098531	FA	001000098531	00000859788	00000000200	00000017196	17	2017-11-15 11:40:32.00095
013	225	27163184732	20171012	00098532	FA	001000098532	00000176743	00000000200	00000003535	17	2017-11-15 11:40:32.00095
013	226	23289795464	20171012	00098533	FA	001000098533	00000833759	00000000200	00000016675	17	2017-11-15 11:40:32.00095
013	227	23289795464	20171012	00098534	FA	001000098534	00000028866	00000000200	00000000577	17	2017-11-15 11:40:32.00095
013	228	27367571123	20171012	00098535	FA	001000098535	00000278236	00000000200	00000005565	17	2017-11-15 11:40:32.00095
013	229	20305781720	20171012	00098536	FA	001000098536	00000518802	00000000200	00000010376	17	2017-11-15 11:40:32.00095
013	230	27173955451	20171012	00098537	FA	001000098537	00000209274	00000000200	00000004185	17	2017-11-15 11:40:32.00095
013	231	23289795464	20171012	00098538	FA	001000098538	00000487687	00000000200	00000009754	17	2017-11-15 11:40:32.00095
013	232	23289795464	20171012	00098539	FA	001000098539	00000028625	00000000200	00000000573	17	2017-11-15 11:40:32.00095
013	233	30670364603	20171012	00098540	FA	001000098540	00000262303	00000000200	00000005246	17	2017-11-15 11:40:32.00095
013	234	27232869564	20171012	00098541	FA	001000098541	00000561831	00000000200	00000011237	17	2017-11-15 11:40:32.00095
013	235	30715278347	20171012	00098542	FA	001000098542	00000113949	00000000200	00000002279	17	2017-11-15 11:40:32.00095
013	236	27222713140	20171012	00098544	FA	001000098544	00000056265	00000000200	00000001125	17	2017-11-15 11:40:32.00095
013	237	23163173824	20171012	00098545	FA	001000098545	00000082702	00000000200	00000001654	17	2017-11-15 11:40:32.00095
013	238	20163184592	20171012	00098546	FA	001000098546	00000215982	00000000200	00000004320	17	2017-11-15 11:40:32.00095
013	239	20078122316	20171012	00098547	FA	001000098547	00000066437	00000000200	00000001329	17	2017-11-15 11:40:32.00095
013	240	27125944707	20171012	00098548	FA	001000098548	00000217210	00000000200	00000004344	17	2017-11-15 11:40:32.00095
013	241	27064350809	20171012	00098549	FA	001000098549	00000224407	00000000200	00000004488	17	2017-11-15 11:40:32.00095
013	242	27064350809	20171012	00098550	FA	001000098550	00000181908	00000000200	00000003638	17	2017-11-15 11:40:32.00095
013	243	27188206463	20171012	00098551	FA	001000098551	00000088225	00000000200	00000001765	17	2017-11-15 11:40:32.00095
013	244	20166734178	20171012	00098553	FA	001000098553	00000101636	00000000200	00000002033	17	2017-11-15 11:40:32.00095
013	245	27222713140	20171012	00098554	FA	001000098554	00000392777	00000000200	00000007856	17	2017-11-15 11:40:32.00095
013	246	30711644578	20171012	00098556	FA	001000098556	00000014519	00000000200	00000000290	17	2017-11-15 11:40:32.00095
013	247	30670359170	20171012	00098558	FA	001000098558	00000173050	00000000200	00000003461	17	2017-11-15 11:40:32.00095
013	248	30609719954	20171012	00098560	FA	001000098560	00000179835	00000000200	00000003597	17	2017-11-15 11:40:32.00095
013	249	30609719954	20171012	00098561	FA	001000098561	00000162757	00000000200	00000003255	17	2017-11-15 11:40:32.00095
013	250	30714735817	20171012	00098571	FA	001000098571	00000381191	00000000200	00000007624	17	2017-11-15 11:40:32.00095
013	251	30712398562	20171012	00098572	FA	001000098572	00000198309	00000000200	00000003966	17	2017-11-15 11:40:32.00095
013	252	20345487035	20171012	00098574	FA	001000098574	00000039095	00000000600	00000002346	17	2017-11-15 11:40:32.00095
014	732	20125940995	20171128	80765328	FA	029100167895	00000385795	00000000200	00000007716	3	2017-12-05 15:57:19.680123
014	733	27117797819	20171128	80765335	FA	029100167917	00000163725	00000000200	00000003274	3	2017-12-05 15:57:19.680123
014	734	11111111111	20171128	09892883	FA	029100167921	00000282983	00000000600	00000016980	3	2017-12-05 15:57:19.680123
014	735	27144481122	20171128	09892884	FA	029100167922	00000249124	00000000200	00000004983	3	2017-12-05 15:57:19.680123
014	736	27166891936	20171128	80765339	FA	028800260187	00000208209	00000000200	00000004164	3	2017-12-05 15:57:19.680123
014	737	11111111111	20171128	80765341	FA	029100167929	00000093840	00000000600	00000005630	3	2017-12-05 15:57:19.680123
014	738	11111111111	20171128	80765343	FA	028800260195	00000290979	00000000600	00000017459	3	2017-12-05 15:57:19.680123
014	739	11111111111	20171128	80765345	FA	029100167930	00000233627	00000000600	00000014018	3	2017-12-05 15:57:19.680123
014	740	11111111111	20171128	09892886	FA	029100167931	00000057353	00000000600	00000003441	3	2017-12-05 15:57:19.680123
014	741	27011995093	20171128	80765346	FA	029100167932	00000035925	00000000200	00000000719	3	2017-12-05 15:57:19.680123
014	742	23078116749	20171128	09892887	FA	029100167933	00000049816	00000000200	00000000996	3	2017-12-05 15:57:19.680123
014	743	23078116749	20171128	09892888	FA	029100167934	00000058154	00000000200	00000001163	3	2017-12-05 15:57:19.680123
014	744	11111111111	20171128	80765347	FA	029100167936	00000267968	00000000600	00000016078	3	2017-12-05 15:57:19.680123
014	745	23078116749	20171128	80765348	FA	029100167937	00000033976	00000000200	00000000680	3	2017-12-05 15:57:19.680123
014	746	11111111111	20171128	09892889	FA	029100167938	00000094763	00000000600	00000005686	3	2017-12-05 15:57:19.680123
014	747	23344036209	20171128	80304258	FA	029100066852	00000127302	00000000200	00000002546	3	2017-12-05 15:57:19.680123
014	748	11111111111	20171128	08064430	NC	028800012106	-0000290979	00000000600	-0000017459	3	2017-12-05 15:57:19.680123
014	749	27296732589	20171128	09892894	FA	028800260202	00000130932	00000000200	00000002619	3	2017-12-05 15:57:19.680123
014	750	27200130079	20171128	09892907	FA	029100167978	00000412207	00000000200	00000008244	3	2017-12-05 15:57:19.680123
014	751	27056603560	20171128	80304283	FA	028800095019	00000924168	00000000200	00000018484	3	2017-12-05 15:57:19.680123
014	752	30715184040	20171128	80304292	FA	029100066878	00000166190	00000000200	00000003324	3	2017-12-05 15:57:19.680123
014	753	11111111111	20171128	80765399	FA	029100167993	00000049944	00000000600	00000002997	3	2017-12-05 15:57:19.680123
014	754	30715184040	20171128	80304295	FA	029100066879	00000004220	00000000200	00000000084	3	2017-12-05 15:57:19.680123
014	755	30715184040	20171128	08035447	NC	029100006417	-0000010805	00000000200	-0000000216	3	2017-12-05 15:57:19.680123
014	756	11111111111	20171128	80765400	FA	029100167994	00000149795	00000000600	00000008988	3	2017-12-05 15:57:19.680123
014	757	11111111111	20171128	80765401	FA	029100167996	00000387974	00000000600	00000023279	3	2017-12-05 15:57:19.680123
014	758	11111111111	20171128	80765402	FA	029100167998	00000422787	00000000600	00000025367	3	2017-12-05 15:57:19.680123
014	759	20108342456	20171128	80765403	FA	029100167999	00000083565	00000000200	00000001671	3	2017-12-05 15:57:19.680123
014	760	20113547198	20171128	80765406	FA	028800260247	00000054961	00000000200	00000001099	3	2017-12-05 15:57:19.680123
014	761	20118586000	20171129	80765413	FA	029100168002	00000081086	00000000200	00000001622	3	2017-12-05 15:57:19.680123
014	762	23073183979	20171129	80765431	FA	029100168007	00000328929	00000000200	00000006579	3	2017-12-05 15:57:19.680123
014	763	20922996297	20171129	80304311	FA	028800095038	00000465039	00000000200	00000009301	3	2017-12-05 15:57:19.680123
014	764	20073246904	20171129	80304313	FA	028800095039	00000028974	00000000200	00000000580	3	2017-12-05 15:57:19.680123
014	765	23141622099	20171129	80765455	FA	028800260301	00000102110	00000000200	00000002042	3	2017-12-05 15:57:19.680123
014	766	23215564894	20171129	09824085	FA	029100066931	00000344992	00000000200	00000006900	3	2017-12-05 15:57:19.680123
014	767	11111111111	20171129	09892966	FA	029100168089	00000104513	00000000600	00000006271	3	2017-12-05 15:57:19.680123
014	768	11111111111	20171129	09892967	FA	029100168090	00000118330	00000000600	00000007100	3	2017-12-05 15:57:19.680123
014	769	27100281819	20171129	80304333	FA	029100066932	00000576496	00000000200	00000011530	3	2017-12-05 15:57:19.680123
014	770	23215564894	20171129	80304334	FA	028800095045	00000011595	00000000200	00000000232	3	2017-12-05 15:57:19.680123
014	771	23215564894	20171129	09824086	FA	029100066933	00000014405	00000000200	00000000288	3	2017-12-05 15:57:19.680123
014	772	23215564894	20171129	00981630	NC	029100006421	-0000028103	00000000200	-0000000562	3	2017-12-05 15:57:19.680123
014	773	23215564894	20171129	08035453	NC	028800006283	-0000011595	00000000200	-0000000232	3	2017-12-05 15:57:19.680123
014	774	11111111111	20171129	80765470	FA	029100168097	00000467344	00000000600	00000028040	3	2017-12-05 15:57:19.680123
014	775	11111111111	20171129	80765472	FA	029100168098	00000030648	00000000600	00000001839	3	2017-12-05 15:57:19.680123
014	776	30999093120	20171129	80765496	FA	028800260340	00000164983	00000000200	00000003300	3	2017-12-05 15:57:19.680123
014	777	30999093120	20171129	80765497	FA	028800260341	00000285739	00000000200	00000005715	3	2017-12-05 15:57:19.680123
014	778	30999093120	20171129	80765498	FA	028800260342	00000013757	00000000200	00000000275	3	2017-12-05 15:57:19.680123
014	779	27290859560	20171129	80304350	FA	028800095066	00000137588	00000000200	00000002752	3	2017-12-05 15:57:19.680123
014	780	11111111111	20171130	80765516	FA	029100168141	00000154469	00000000600	00000009268	3	2017-12-05 15:57:19.680123
014	781	30670371359	20171130	09824100	FA	028800095086	00000622384	00000000200	00000012448	3	2017-12-05 15:57:19.680123
014	782	11111111111	20171130	80765535	FA	029100168154	00000050904	00000000600	00000003054	3	2017-12-05 15:57:19.680123
014	783	11111111111	20171130	09892993	FA	029100168155	00000040692	00000000600	00000002442	3	2017-12-05 15:57:19.680123
014	784	11111111111	20171130	80765536	FA	028800260384	00000041451	00000000600	00000002487	3	2017-12-05 15:57:19.680123
014	785	30670371359	20171130	00981631	NC	029100006424	-0000001610	00000000200	-0000000032	3	2017-12-05 15:57:19.680123
014	786	20226157833	20171130	80304367	FA	029100066958	00000390052	00000000200	00000007801	3	2017-12-05 15:57:19.680123
014	787	20178791428	20171130	09892996	FA	029100168163	00000403186	00000000200	00000008064	3	2017-12-05 15:57:19.680123
014	788	11111111111	20171130	09892997	FA	029100168166	00000518442	00000000600	00000031107	3	2017-12-05 15:57:19.680123
014	789	20226157833	20171130	80304368	FA	029100066959	00000090010	00000000200	00000001800	3	2017-12-05 15:57:19.680123
014	790	20073299498	20171130	09824105	FA	029100066967	00000376623	00000000200	00000007533	3	2017-12-05 15:57:19.680123
014	791	11111111111	20171130	80765566	FA	029100168172	00000026256	00000000600	00000001575	3	2017-12-05 15:57:19.680123
014	792	27259753274	20171130	80304372	FA	029100066974	00000196043	00000000200	00000003921	3	2017-12-05 15:57:19.680123
014	793	11111111111	20171130	09893003	FA	029100168186	00000897336	00000000600	00000053840	3	2017-12-05 15:57:19.680123
014	794	27185951184	20171130	80765580	FA	029100168209	00000104524	00000000200	00000002091	3	2017-12-05 15:57:19.680123
014	795	30670357577	20171130	80304383	FA	029100066991	00000468190	00000000200	00000009364	3	2017-12-05 15:57:19.680123
014	796	23215564894	20171130	09824111	FA	029100066994	00000374704	00000000200	00000007494	3	2017-12-05 15:57:19.680123
014	797	24248899286	20171130	09824112	FA	029100066995	00000399959	00000000200	00000007999	3	2017-12-05 15:57:19.680123
014	798	24248899286	20171130	09824113	FA	029100066996	00000081202	00000000200	00000001624	3	2017-12-05 15:57:19.680123
014	799	20125944281	20171130	09824114	FA	028800095092	00000198999	00000000200	00000003980	3	2017-12-05 15:57:19.680123
014	800	11111111111	20171130	00986372	NC	028800012122	-0000897336	00000000600	-0000053840	3	2017-12-05 15:57:19.680123
014	801	20242148127	20171130	09824119	FA	028800095115	00000146131	00000000200	00000002923	3	2017-12-05 15:57:19.680123
014	802	20242148127	20171130	09824120	FA	029100067020	00000121327	00000000200	00000002427	3	2017-12-05 15:57:19.680123
014	803	11111111111	20171130	09893030	FA	029100168265	00000202771	00000000600	00000012166	3	2017-12-05 15:57:19.680123
014	804	20253240653	20171130	80765640	FA	028800260450	00000120303	00000000200	00000002406	3	2017-12-05 15:57:19.680123
014	805	20331132269	20171130	80304411	FA	029100067021	00000011499	00000000200	00000000230	3	2017-12-05 15:57:19.680123
014	806	20073299498	20171130	09824127	FA	028800095133	00000218484	00000000200	00000004370	3	2017-12-05 15:57:19.680123
003	440	20173955740	20171121	00020735	FA	001700020735	00000036018	00000000200	00000000720	7	2017-12-05 18:44:23.476865
003	441	27367571123	20171121	00020732	FA	001700020732	00000035997	00000000200	00000000720	7	2017-12-05 18:44:23.476865
003	442	20261719887	20171121	00020733	FA	001700020733	00000020252	00000000200	00000000405	7	2017-12-05 18:44:23.476865
003	443	27222713140	20171121	00020734	FA	001700020734	00000012388	00000000200	00000000248	7	2017-12-05 18:44:23.476865
003	444	20367604302	20171121	00026321	FA	001500026321	00000040506	00000000200	00000000810	7	2017-12-05 18:44:23.476865
003	445	27346653502	20171121	00020608	FA	001700020608	00000080382	00000000200	00000001608	7	2017-12-05 18:44:23.476865
003	446	20113547198	20171121	00020611	FA	001700020611	00000070789	00000000200	00000001416	7	2017-12-05 18:44:23.476865
003	447	27952625514	20171121	00020736	FA	001700020736	00000810273	00000000200	00000016205	7	2017-12-05 18:44:23.476865
003	448	20226158074	20171121	00016771	FA	001900016771	00000047749	00000000200	00000000955	7	2017-12-05 18:44:23.476865
003	449	23241216349	20171121	00009698	FA	001300009698	00000255763	00000000200	00000005115	7	2017-12-05 18:44:23.476865
003	450	27064350809	20171121	00026765	FA	001500026765	00000068180	00000000200	00000001364	7	2017-12-05 18:44:23.476865
003	451	27367571123	20171121	00026766	FA	001500026766	00000137115	00000000200	00000002742	7	2017-12-05 18:44:23.476865
003	452	27056603560	20171121	00026767	FA	001500026767	00000214385	00000000200	00000004288	7	2017-12-05 18:44:23.476865
003	453	20305781720	20171121	00026768	FA	001500026768	00000587562	00000000200	00000011751	7	2017-12-05 18:44:23.476865
003	454	27222713140	20171121	00026769	FA	001500026769	00000064244	00000000200	00000001285	7	2017-12-05 18:44:23.476865
003	455	20261719887	20171121	00026770	FA	001500026770	00000248483	00000000200	00000004970	7	2017-12-05 18:44:23.476865
003	456	30670364603	20171121	00026771	FA	001500026771	00000105747	00000000200	00000002115	7	2017-12-05 18:44:23.476865
003	457	20213541421	20171121	00026772	FA	001500026772	00000272448	00000000200	00000005449	7	2017-12-05 18:44:23.476865
003	458	27222713140	20171121	00026773	FA	001500026773	00000194753	00000000200	00000003895	7	2017-12-05 18:44:23.476865
003	459	27100281819	20171121	00026774	FA	001500026774	00000043993	00000000200	00000000880	7	2017-12-05 18:44:23.476865
003	460	27952625514	20171121	00026775	FA	001500026775	00000503496	00000000200	00000010070	7	2017-12-05 18:44:23.476865
003	461	27952625514	20171121	00026776	FA	001500026776	00000595796	00000000200	00000011916	7	2017-12-05 18:44:23.476865
003	462	27222713140	20171121	00026777	FA	001500026777	00000081075	00000000200	00000001622	7	2017-12-05 18:44:23.476865
003	463	27351546862	20171121	00026332	FA	001500026332	00000068750	00000000200	00000001375	7	2017-12-05 18:44:23.476865
003	464	23141622099	20171121	00026333	FA	001500026333	00000100341	00000000200	00000002007	7	2017-12-05 18:44:23.476865
003	465	27219273431	20171121	00026334	FA	001500026334	00000161000	00000000200	00000003220	7	2017-12-05 18:44:23.476865
003	466	27346653502	20171121	00026335	FA	001500026335	00000063226	00000000200	00000001265	7	2017-12-05 18:44:23.476865
003	467	27248176607	20171121	00026337	FA	001500026337	00000161714	00000000200	00000003234	7	2017-12-05 18:44:23.476865
003	468	30708978562	20171121	00009696	FA	001300009696	00000233479	00000000200	00000004670	7	2017-12-05 18:44:23.476865
003	469	20274035170	20171122	00026837	FA	001500026837	00000027905	00000000200	00000000558	7	2017-12-05 18:44:23.476865
003	470	23289795464	20171122	00026838	FA	001500026838	00000229336	00000000200	00000004587	7	2017-12-05 18:44:23.476865
003	471	27125942461	20171122	00011714	FA	001300011714	00000076426	00000000200	00000001529	7	2017-12-05 18:44:23.476865
003	472	20054139285	20171122	00026841	FA	001500026841	00000140086	00000000200	00000002802	7	2017-12-05 18:44:23.476865
003	473	27367571123	20171122	00005629	FA	001800005629	00000024830	00000000200	00000000497	7	2017-12-05 18:44:23.476865
003	474	20261719887	20171122	00005630	FA	001800005630	00000030246	00000000200	00000000605	7	2017-12-05 18:44:23.476865
003	475	30670364603	20171122	00005631	FA	001800005631	00000048208	00000000200	00000000964	7	2017-12-05 18:44:23.476865
003	476	27222713140	20171122	00005632	FA	001800005632	00000039861	00000000200	00000000797	7	2017-12-05 18:44:23.476865
003	477	20274035170	20171122	00005633	FA	001800005633	00000008600	00000000200	00000000172	7	2017-12-05 18:44:23.476865
003	478	27952625514	20171122	00005634	FA	001800005634	00000085441	00000000200	00000001709	7	2017-12-05 18:44:23.476865
003	479	23289795464	20171122	00005635	FA	001800005635	00000179334	00000000200	00000003587	7	2017-12-05 18:44:23.476865
003	480	20273644912	20171122	00006795	FA	001800006795	00000016690	00000000200	00000000334	7	2017-12-05 18:44:23.476865
003	481	20274035170	20171122	00020790	FA	001700020790	00000056784	00000000200	00000001136	7	2017-12-05 18:44:23.476865
010	59	27222713140	20170804	00005699	FA	030000005699	00000078926	00000000200	00000001579	14	2017-09-27 09:37:53.511455
010	60	27064350809	20170804	00005700	FA	030000005700	00000092912	00000000200	00000001857	14	2017-09-27 09:37:53.511455
010	61	27163184732	20170804	00005701	FA	030000005701	00000058523	00000000200	00000001170	14	2017-09-27 09:37:53.511455
010	62	20305781720	20170804	00005702	FA	030000005702	00000089256	00000000200	00000001785	14	2017-09-27 09:37:53.511455
010	63	30670364603	20170825	00005969	FA	030000005969	00000045154	00000000200	00000000904	14	2017-09-27 09:37:53.511455
010	64	27056603560	20170825	00005970	FA	030000005970	00000052232	00000000200	00000001045	14	2017-09-27 09:37:53.511455
010	65	20261719887	20170825	00005971	FA	030000005971	00000110332	00000000200	00000002207	14	2017-09-27 09:37:53.511455
010	66	23289795464	20170825	00005972	FA	030000005972	00000202232	00000000200	00000004046	14	2017-09-27 09:37:53.511455
010	67	23289795464	20170825	00005973	FA	030000005973	00000088431	00000000200	00000001769	14	2017-09-27 09:37:53.511455
010	68	20274035170	20170825	00005974	FA	030000005974	00000134670	00000000200	00000002694	14	2017-09-27 09:37:53.511455
010	69	23163173824	20170825	00005975	FA	030000005975	00000057852	00000000200	00000001157	14	2017-09-27 09:37:53.511455
010	70	27173955451	20170825	00005976	FA	030000005976	00000127404	00000000200	00000002548	14	2017-09-27 09:37:53.511455
010	71	27188206463	20170825	00005977	FA	030000005977	00000173140	00000000200	00000003463	14	2017-09-27 09:37:53.511455
010	72	27222713140	20170825	00005978	FA	030000005978	00000061984	00000000200	00000001239	14	2017-09-27 09:37:53.511455
010	73	27064350809	20170825	00005979	FA	030000005979	00000080165	00000000200	00000001603	14	2017-09-27 09:37:53.511455
010	74	27163184732	20170825	00005980	FA	030000005980	00000057852	00000000200	00000001157	14	2017-09-27 09:37:53.511455
010	75	20305781720	20170825	00005981	FA	030000005981	00000079917	00000000200	00000001598	14	2017-09-27 09:37:53.511455
010	76	27056603560	20170808	00005736	FA	030000005736	00000079174	00000000200	00000001583	14	2017-09-27 09:37:53.511455
010	77	20261719887	20170808	00005737	FA	030000005737	00000188679	00000000200	00000003774	14	2017-09-27 09:37:53.511455
010	78	23241216349	20170808	00005738	FA	030000005738	00000036739	00000000200	00000000735	14	2017-09-27 09:37:53.511455
010	79	20274035170	20170808	00005739	FA	030000005739	00000098141	00000000200	00000001962	14	2017-09-27 09:37:53.511455
010	80	27173955451	20170808	00005740	FA	030000005740	00000056199	00000000200	00000001124	14	2017-09-27 09:37:53.511455
010	81	20073261806	20170808	00005741	FA	030000005741	00000193388	00000000200	00000003867	14	2017-09-27 09:37:53.511455
010	82	27222713140	20170808	00005742	FA	030000005742	00000174958	00000000200	00000003500	14	2017-09-27 09:37:53.511455
010	83	20305781720	20170808	00005743	FA	030000005743	00000170083	00000000200	00000003401	14	2017-09-27 09:37:53.511455
010	84	30715278347	20170829	00006023	FA	030000006023	00000036364	00000000200	00000000727	14	2017-09-27 09:37:53.511455
010	85	27056603560	20170829	00006024	FA	030000006024	00000042975	00000000200	00000000860	14	2017-09-27 09:37:53.511455
010	86	20261719887	20170829	00006025	FA	030000006025	00000143307	00000000200	00000002867	14	2017-09-27 09:37:53.511455
010	87	23289795464	20170829	00006026	FA	030000006026	00000061157	00000000200	00000001223	14	2017-09-27 09:37:53.511455
010	88	23289795464	20170829	00006027	FA	030000006027	00000121652	00000000200	00000002434	14	2017-09-27 09:37:53.511455
010	89	20274035170	20170829	00006028	FA	030000006028	00000057852	00000000200	00000001157	14	2017-09-27 09:37:53.511455
010	90	27125944707	20170829	00006029	FA	030000006029	00000066943	00000000200	00000001339	14	2017-09-27 09:37:53.511455
010	91	27100281819	20170829	00006030	FA	030000006030	00000054711	00000000200	00000001095	14	2017-09-27 09:37:53.511455
010	92	27173955451	20170829	00006031	FA	030000006031	00000105868	00000000200	00000002117	14	2017-09-27 09:37:53.511455
010	93	20073261806	20170829	00006032	FA	030000006032	00000258678	00000000200	00000005174	14	2017-09-27 09:37:53.511455
010	94	27222713140	20170829	00006033	FA	030000006033	00000078099	00000000200	00000001562	14	2017-09-27 09:37:53.511455
010	95	27163184732	20170829	00006034	FA	030000006034	00000121692	00000000200	00000002434	14	2017-09-27 09:37:53.511455
010	96	20305781720	20170829	00006035	FA	030000006035	00000114137	00000000200	00000002283	14	2017-09-27 09:37:53.511455
010	97	30670364603	20170811	00005784	FA	030000005784	00000088018	00000000200	00000001762	14	2017-09-27 09:37:53.511455
010	98	27056603560	20170811	00005785	FA	030000005785	00000100826	00000000200	00000002016	14	2017-09-27 09:37:53.511455
010	99	20261719887	20170811	00005786	FA	030000005786	00000152315	00000000200	00000003047	14	2017-09-27 09:37:53.511455
010	100	23289795464	20170811	00005787	FA	030000005787	00000077851	00000000200	00000001558	14	2017-09-27 09:37:53.511455
010	101	20274035170	20170811	00005788	FA	030000005788	00000019835	00000000200	00000000397	14	2017-09-27 09:37:53.511455
010	102	27125944707	20170811	00005789	FA	030000005789	00000029969	00000000200	00000000599	14	2017-09-27 09:37:53.511455
010	103	23163173824	20170811	00005790	FA	030000005790	00000019835	00000000200	00000000397	14	2017-09-27 09:37:53.511455
010	104	27188206463	20170811	00005791	FA	030000005791	00000110744	00000000200	00000002216	14	2017-09-27 09:37:53.511455
010	105	27173955451	20170811	00005792	FA	030000005792	00000172277	00000000200	00000003446	14	2017-09-27 09:37:53.511455
010	106	27222713140	20170811	00005793	FA	030000005793	00000131984	00000000200	00000002640	14	2017-09-27 09:37:53.511455
010	107	27064350809	20170811	00005794	FA	030000005794	00000072727	00000000200	00000001455	14	2017-09-27 09:37:53.511455
010	108	20305781720	20170811	00005795	FA	030000005795	00000224784	00000000200	00000004496	14	2017-09-27 09:37:53.511455
010	109	20261719887	20170815	00005830	FA	030000005830	00000238347	00000000200	00000004768	14	2017-09-27 09:37:53.511455
010	110	20274035170	20170815	00005831	FA	030000005831	00000102397	00000000200	00000002048	14	2017-09-27 09:37:53.511455
010	111	27125944707	20170815	00005832	FA	030000005832	00000081258	00000000200	00000001626	14	2017-09-27 09:37:53.511455
010	112	23163173824	20170815	00005833	FA	030000005833	00000021488	00000000200	00000000430	14	2017-09-27 09:37:53.511455
010	113	27100281819	20170815	00005834	FA	030000005834	00000086364	00000000200	00000001727	14	2017-09-27 09:37:53.511455
010	114	27173955451	20170815	00005835	FA	030000005835	00000057852	00000000200	00000001157	14	2017-09-27 09:37:53.511455
010	115	20073261806	20170815	00005836	FA	030000005836	00000237355	00000000200	00000004747	14	2017-09-27 09:37:53.511455
010	116	27064350809	20170815	00005837	FA	030000005837	00000074380	00000000200	00000001488	14	2017-09-27 09:37:53.511455
010	117	27222713140	20170815	00005838	FA	030000005838	00000052067	00000000200	00000001042	14	2017-09-27 09:37:53.511455
010	118	27163184732	20170815	00005839	FA	030000005839	00000038728	00000000200	00000000774	14	2017-09-27 09:37:53.511455
010	119	20305781720	20170815	00005840	FA	030000005840	00000077273	00000000200	00000001545	14	2017-09-27 09:37:53.511455
010	120	33670386029	20170808	00015104	FA	030000015104	00000023140	00000000200	00000000463	14	2017-09-27 09:37:53.511455
010	121	20290121753	20170822	00015616	FA	030000015616	00000072727	00000000200	00000001455	14	2017-09-27 09:37:53.511455
010	122	20273644912	20170829	00015872	FA	030000015872	00000012125	00000000200	00000000243	14	2017-09-27 09:37:53.511455
010	123	27044920102	20170808	00015105	FA	030000015105	00000036777	00000000200	00000000736	14	2017-09-27 09:37:53.511455
010	124	27346653782	20170822	00015617	FA	030000015617	00000030579	00000000200	00000000612	14	2017-09-27 09:37:53.511455
010	125	23141622099	20170829	00015873	FA	030000015873	00000072727	00000000200	00000001455	14	2017-09-27 09:37:53.511455
010	126	27942269566	20170808	00015106	FA	030000015106	00000097521	00000000200	00000001950	14	2017-09-27 09:37:53.511455
010	127	27219273431	20170822	00015618	FA	030000015618	00000085289	00000000200	00000001706	14	2017-09-27 09:37:53.511455
010	128	27229348774	20170829	00015874	FA	030000015874	00000052562	00000000200	00000001051	14	2017-09-27 09:37:53.511455
010	129	20295456478	20170808	00015107	FA	030000015107	00000040248	00000000200	00000000805	14	2017-09-27 09:37:53.511455
010	130	27351546862	20170822	00015619	FA	030000015619	00000016942	00000000200	00000000339	14	2017-09-27 09:37:53.511455
010	131	27041619916	20170829	00015875	FA	030000015875	00000104463	00000000200	00000002090	14	2017-09-27 09:37:53.511455
010	132	27245845494	20170808	00015108	FA	030000015108	00000123864	00000000200	00000002479	14	2017-09-27 09:37:53.511455
010	133	23237095979	20170822	00015620	FA	030000015620	00000021488	00000000200	00000000430	14	2017-09-27 09:37:53.511455
010	134	27346653782	20170829	00015876	FA	030000015876	00000030579	00000000200	00000000612	14	2017-09-27 09:37:53.511455
010	135	27313340754	20170808	00015109	FA	030000015109	00000073141	00000000200	00000001463	14	2017-09-27 09:37:53.511455
010	136	23190178064	20170822	00015621	FA	030000015621	00000030744	00000000200	00000000615	14	2017-09-27 09:37:53.511455
010	137	27313340754	20170829	00015877	FA	030000015877	00000021488	00000000200	00000000430	14	2017-09-27 09:37:53.511455
010	138	27121002650	20170808	00015110	FA	030000015110	00000059753	00000000200	00000001195	14	2017-09-27 09:37:53.511455
010	139	27297722927	20170822	00015622	FA	030000015622	00000066268	00000000200	00000001325	14	2017-09-27 09:37:53.511455
010	140	27248176607	20170829	00015878	FA	030000015878	00000052417	00000000200	00000001048	14	2017-09-27 09:37:53.511455
010	141	27367571123	20170808	00015111	FA	030000015111	00000122562	00000000200	00000002451	14	2017-09-27 09:37:53.511455
010	142	20273644912	20170822	00015623	FA	030000015623	00000021488	00000000200	00000000430	14	2017-09-27 09:37:53.511455
010	143	27101402156	20170808	00015112	FA	030000015112	00000036364	00000000200	00000000727	14	2017-09-27 09:37:53.511455
010	144	23141622099	20170822	00015624	FA	030000015624	00000088431	00000000200	00000001769	14	2017-09-27 09:37:53.511455
010	145	27044920102	20170801	00014857	FA	030000014857	00000052075	00000000200	00000001042	14	2017-09-27 09:37:53.511455
010	146	27925411626	20170808	00015113	FA	030000015113	00000029174	00000000200	00000000583	14	2017-09-27 09:37:53.511455
010	147	27044920102	20170815	00015369	FA	030000015369	00000021488	00000000200	00000000430	14	2017-09-27 09:37:53.511455
010	148	27041619916	20170822	00015625	FA	030000015625	00000125951	00000000200	00000002519	14	2017-09-27 09:37:53.511455
010	149	20082698508	20170801	00014858	FA	030000014858	00000095703	00000000200	00000001913	14	2017-09-27 09:37:53.511455
010	150	20226158074	20170808	00015114	FA	030000015114	00000036364	00000000200	00000000727	14	2017-09-27 09:37:53.511455
010	151	20271066547	20170815	00015370	FA	030000015370	00000021488	00000000200	00000000430	14	2017-09-27 09:37:53.511455
010	152	20243042802	20170822	00015626	FA	030000015626	00000104463	00000000200	00000002090	14	2017-09-27 09:37:53.511455
010	153	27305781466	20170801	00014859	FA	030000014859	00000008550	00000000200	00000000171	14	2017-09-27 09:37:53.511455
010	154	27346653502	20170808	00015115	FA	030000015115	00000098348	00000000200	00000001967	14	2017-09-27 09:37:53.511455
010	155	27313340754	20170815	00015371	FA	030000015371	00000021488	00000000200	00000000430	14	2017-09-27 09:37:53.511455
010	156	27313340754	20170822	00015627	FA	030000015627	00000057852	00000000200	00000001157	14	2017-09-27 09:37:53.511455
010	157	20271066547	20170801	00014860	FA	030000014860	00000051402	00000000200	00000001028	14	2017-09-27 09:37:53.511455
010	158	27219273431	20170808	00015116	FA	030000015116	00000066943	00000000200	00000001339	14	2017-09-27 09:37:53.511455
010	159	27121002650	20170815	00015372	FA	030000015372	00000091901	00000000200	00000001838	14	2017-09-27 09:37:53.511455
010	160	27361302759	20170822	00015628	FA	030000015628	00000083471	00000000200	00000001671	14	2017-09-27 09:37:53.511455
010	161	27121002650	20170801	00014861	FA	030000014861	00000079588	00000000200	00000001592	14	2017-09-27 09:37:53.511455
010	162	27351546862	20170808	00015117	FA	030000015117	00000036364	00000000200	00000000727	14	2017-09-27 09:37:53.511455
010	163	27367571123	20170815	00015373	FA	030000015373	00000147766	00000000200	00000002955	14	2017-09-27 09:37:53.511455
010	164	27248176607	20170822	00015629	FA	030000015629	00000083864	00000000200	00000001677	14	2017-09-27 09:37:53.511455
010	165	27367571123	20170801	00014862	FA	030000014862	00000158926	00000000200	00000003178	14	2017-09-27 09:37:53.511455
010	166	27402096204	20170808	00015118	FA	030000015118	00000036364	00000000200	00000000727	14	2017-09-27 09:37:53.511455
010	167	27313340339	20170815	00015374	FA	030000015374	00000057194	00000000200	00000001144	14	2017-09-27 09:37:53.511455
010	168	20290121753	20170801	00014863	FA	030000014863	00000092562	00000000200	00000001852	14	2017-09-27 09:37:53.511455
010	169	27297722927	20170808	00015119	FA	030000015119	00000074947	00000000200	00000001498	14	2017-09-27 09:37:53.511455
010	170	27346653782	20170815	00015375	FA	030000015375	00000030579	00000000200	00000000612	14	2017-09-27 09:37:53.511455
010	171	27346653502	20170801	00014864	FA	030000014864	00000097521	00000000200	00000001950	14	2017-09-27 09:37:53.511455
010	172	20273644912	20170808	00015120	FA	030000015120	00000079794	00000000200	00000001597	14	2017-09-27 09:37:53.511455
010	173	20082698508	20170815	00015376	FA	030000015376	00000060496	00000000200	00000001210	14	2017-09-27 09:37:53.511455
010	174	27219273431	20170801	00014865	FA	030000014865	00000061157	00000000200	00000001223	14	2017-09-27 09:37:53.511455
010	175	27041619916	20170808	00015121	FA	030000015121	00000156446	00000000200	00000003128	14	2017-09-27 09:37:53.511455
010	176	23141622099	20170815	00015377	FA	030000015377	00000067108	00000000200	00000001342	14	2017-09-27 09:37:53.511455
010	177	27351546862	20170801	00014866	FA	030000014866	00000014711	00000000200	00000000294	14	2017-09-27 09:37:53.511455
010	178	20243042802	20170808	00015122	FA	030000015122	00000131074	00000000200	00000002622	14	2017-09-27 09:37:53.511455
010	179	20243042802	20170815	00015378	FA	030000015378	00000167273	00000000200	00000003346	14	2017-09-27 09:37:53.511455
010	180	27216609528	20170801	00014867	FA	030000014867	00000022524	00000000200	00000000450	14	2017-09-27 09:37:53.511455
010	181	27237091987	20170808	00015123	FA	030000015123	00000021752	00000000200	00000000436	14	2017-09-27 09:37:53.511455
010	182	20290121753	20170815	00015379	FA	030000015379	00000094595	00000000200	00000001893	14	2017-09-27 09:37:53.511455
010	183	27297722927	20170801	00014868	FA	030000014868	00000059339	00000000200	00000001186	14	2017-09-27 09:37:53.511455
013	253	20300363130	20171012	00098579	FA	001000098579	00000392945	00000000200	00000007859	17	2017-11-15 11:40:32.00095
013	254	27313340339	20171012	00226581	FA	001000226581	00000170381	00000000200	00000003408	17	2017-11-15 11:40:32.00095
013	255	27346653502	20171012	00226582	FA	001000226582	00000287646	00000000200	00000005753	17	2017-11-15 11:40:32.00095
013	256	27000000006	20171012	00226583	FA	001000226583	00000079829	00000000200	00000001597	17	2017-11-15 11:40:32.00095
013	257	27950274226	20171012	00226584	FA	001000226584	00000018573	00000000200	00000000371	17	2017-11-15 11:40:32.00095
013	258	27044920102	20171012	00226585	FA	001000226585	00000063354	00000000200	00000001267	17	2017-11-15 11:40:32.00095
013	259	27305781466	20171012	00226586	FA	001000226586	00000092863	00000000200	00000001857	17	2017-11-15 11:40:32.00095
013	260	27363206641	20171012	00226587	FA	001000226587	00000380033	00000000200	00000007601	17	2017-11-15 11:40:32.00095
013	261	30714924644	20171012	00226588	FA	001000226588	00000112708	00000000200	00000002254	17	2017-11-15 11:40:32.00095
013	262	27297722927	20171012	00226589	FA	001000226589	00000277595	00000000200	00000005552	17	2017-11-15 11:40:32.00095
013	263	27394430655	20171012	00226590	FA	001000226590	00000069786	00000000200	00000001396	17	2017-11-15 11:40:32.00095
013	264	23331976199	20171012	00226591	FA	001000226591	00000214015	00000000200	00000004280	17	2017-11-15 11:40:32.00095
013	265	27248176607	20171012	00226592	FA	001000226592	00000066980	00000000200	00000001340	17	2017-11-15 11:40:32.00095
013	266	27313340754	20171012	00226594	FA	001000226594	00000110353	00000000200	00000002207	17	2017-11-15 11:40:32.00095
013	267	27266554740	20171012	00226596	FA	001000226596	00000030551	00000000200	00000000611	17	2017-11-15 11:40:32.00095
013	268	20313340792	20171012	00226597	FA	001000226597	00000093941	00000000600	00000005636	17	2017-11-15 11:40:32.00095
013	269	27921235521	20171012	00226598	FA	001000226598	00000117735	00000000200	00000002355	17	2017-11-15 11:40:32.00095
013	270	23237095979	20171012	00226600	FA	001000226600	00000027868	00000000200	00000000557	17	2017-11-15 11:40:32.00095
013	271	27237091987	20171012	00226601	FA	001000226601	00000050690	00000000200	00000001014	17	2017-11-15 11:40:32.00095
003	482	23344036209	20171122	00009706	FA	001300009706	00000014240	00000000200	00000000285	7	2017-12-05 18:44:23.476865
003	483	20273644912	20171122	00011727	FA	001300011727	00000003597	00000000200	00000000072	7	2017-12-05 18:44:23.476865
003	484	23241216349	20171122	00009712	FA	001300009712	00000229116	00000000200	00000004582	7	2017-12-05 18:44:23.476865
003	485	27363206641	20171123	00011761	FA	001300011761	00000407539	00000000200	00000008151	7	2017-12-05 18:44:23.476865
003	486	27342957760	20171123	00011765	FA	001300011765	00000206007	00000000200	00000004120	7	2017-12-05 18:44:23.476865
003	487	27342957760	20171123	00011766	FA	001300011766	00000032427	00000000200	00000000649	7	2017-12-05 18:44:23.476865
003	488	27064350809	20171123	00009726	FA	001300009726	00000022469	00000000200	00000000449	7	2017-12-05 18:44:23.476865
003	489	27952625514	20171123	00020819	FA	001700020819	00001503786	00000000200	00000030076	7	2017-12-05 18:44:23.476865
003	490	27952625514	20171123	00026897	FA	001500026897	00001363064	00000000200	00000027261	7	2017-12-05 18:44:23.476865
003	491	27952625514	20171123	00026898	FA	001500026898	00000926731	00000000200	00000018535	7	2017-12-05 18:44:23.476865
003	492	30707024085	20171123	00009729	FA	001300009729	00000076993	00000000200	00000001540	7	2017-12-05 18:44:23.476865
003	493	30708978562	20171123	00020825	FA	001700020825	00010179958	00000000200	00000203599	7	2017-12-05 18:44:23.476865
003	494	20163184592	20171123	00009731	FA	001300009731	00000030491	00000000200	00000000610	7	2017-12-05 18:44:23.476865
003	495	20368602923	20171123	00011787	FA	001300011787	00000102490	00000000200	00000002050	7	2017-12-05 18:44:23.476865
003	496	27188206463	20171124	00020841	FA	001700020841	00000022295	00000000200	00000000446	7	2017-12-05 18:44:23.476865
003	497	20261719887	20171124	00020842	FA	001700020842	00000052265	00000000200	00000001045	7	2017-12-05 18:44:23.476865
003	498	20305781720	20171124	00020843	FA	001700020843	00000047005	00000000200	00000000940	7	2017-12-05 18:44:23.476865
003	499	20073261806	20171124	00020844	FA	001700020844	00000026297	00000000200	00000000526	7	2017-12-05 18:44:23.476865
003	500	23163173824	20171124	00020845	FA	001700020845	00000082940	00000000200	00000001659	7	2017-12-05 18:44:23.476865
003	501	27346653502	20171124	00020702	FA	001700020702	00000239670	00000000200	00000004793	7	2017-12-05 18:44:23.476865
003	502	20163184592	20171124	00020846	FA	001700020846	00000081813	00000000200	00000001636	7	2017-12-05 18:44:23.476865
003	503	27952625514	20171124	00020847	FA	001700020847	00000279460	00000000200	00000005589	7	2017-12-05 18:44:23.476865
003	504	27219273431	20171124	00020703	FA	001700020703	00000122610	00000000200	00000002452	7	2017-12-05 18:44:23.476865
003	505	27112378257	20171124	00020704	FA	001700020704	00000012388	00000000200	00000000248	7	2017-12-05 18:44:23.476865
003	506	20101553419	20171124	00009745	FA	001300009745	00000061050	00000000200	00000001221	7	2017-12-05 18:44:23.476865
003	507	20118857020	20171124	00020709	FA	001700020709	00000056784	00000000200	00000001136	7	2017-12-05 18:44:23.476865
003	508	20305781720	20171124	00026930	FA	001500026930	00000612267	00000000200	00000012245	7	2017-12-05 18:44:23.476865
003	509	27056603560	20171124	00026931	FA	001500026931	00000181917	00000000200	00000003638	7	2017-12-05 18:44:23.476865
003	510	20261719887	20171124	00026932	FA	001500026932	00000202055	00000000200	00000004041	7	2017-12-05 18:44:23.476865
003	511	20305781720	20171124	00026933	FA	001500026933	00000065565	00000000200	00000001311	7	2017-12-05 18:44:23.476865
003	512	27064350809	20171124	00026934	FA	001500026934	00000048810	00000000200	00000000976	7	2017-12-05 18:44:23.476865
003	513	20261719887	20171124	00026935	FA	001500026935	00000009479	00000000200	00000000190	7	2017-12-05 18:44:23.476865
003	514	27173955451	20171124	00026936	FA	001500026936	00000092689	00000000200	00000001854	7	2017-12-05 18:44:23.476865
003	515	27188206463	20171124	00026937	FA	001500026937	00000225960	00000000200	00000004519	7	2017-12-05 18:44:23.476865
003	516	20073261806	20171124	00026938	FA	001500026938	00000044132	00000000200	00000000883	7	2017-12-05 18:44:23.476865
003	517	20087086608	20171124	00026939	FA	001500026939	00000200455	00000000200	00000004009	7	2017-12-05 18:44:23.476865
003	518	20213541421	20171124	00026940	FA	001500026940	00000061567	00000000200	00000001231	7	2017-12-05 18:44:23.476865
003	519	20078206331	20171124	00026941	FA	001500026941	00000437777	00000000200	00000008756	7	2017-12-05 18:44:23.476865
003	520	30604760018	20171124	00026942	FA	001500026942	00000164781	00000000200	00000003296	7	2017-12-05 18:44:23.476865
003	521	20073261806	20171124	00026943	FA	001500026943	00000015430	00000000200	00000000309	7	2017-12-05 18:44:23.476865
003	522	27222713140	20171124	00026944	FA	001500026944	00000073321	00000000200	00000001466	7	2017-12-05 18:44:23.476865
003	523	20266077719	20171124	00026945	FA	001500026945	00000047450	00000000200	00000000949	7	2017-12-05 18:44:23.476865
003	524	20173955740	20171124	00026946	FA	001500026946	00000111573	00000000200	00000002231	7	2017-12-05 18:44:23.476865
003	525	27952625514	20171124	00026947	FA	001500026947	00000829330	00000000200	00000016587	7	2017-12-05 18:44:23.476865
003	526	23163173824	20171124	00026948	FA	001500026948	00000065928	00000000200	00000001319	7	2017-12-05 18:44:23.476865
003	527	27222713140	20171124	00026949	FA	001500026949	00000173299	00000000200	00000003466	7	2017-12-05 18:44:23.476865
003	528	23163173824	20171124	00026950	FA	001500026950	00000012590	00000000200	00000000252	7	2017-12-05 18:44:23.476865
003	529	27266554740	20171124	00026477	FA	001500026477	00000184662	00000000200	00000003693	7	2017-12-05 18:44:23.476865
003	530	27219273431	20171124	00026478	FA	001500026478	00000101704	00000000200	00000002034	7	2017-12-05 18:44:23.476865
003	531	27346653502	20171124	00026479	FA	001500026479	00000239554	00000000200	00000004791	7	2017-12-05 18:44:23.476865
003	532	27112378257	20171124	00026480	FA	001500026480	00000064878	00000000200	00000001298	7	2017-12-05 18:44:23.476865
003	533	27346653502	20171124	00026481	FA	001500026481	00000033372	00000000200	00000000667	7	2017-12-05 18:44:23.476865
003	534	20173955449	20171124	00026482	FA	001500026482	00000103956	00000000200	00000002079	7	2017-12-05 18:44:23.476865
003	535	20213541421	20171124	00026951	FA	001500026951	00000083492	00000000200	00000001670	7	2017-12-05 18:44:23.476865
003	536	27367571123	20171124	00009747	FA	001300009747	00000281148	00000000200	00000005623	7	2017-12-05 18:44:23.476865
003	537	27367571123	20171124	00009748	FA	001300009748	00000188416	00000000200	00000003768	7	2017-12-05 18:44:23.476865
003	538	20118857020	20171124	00026483	FA	001500026483	00000020615	00000000200	00000000412	7	2017-12-05 18:44:23.476865
003	539	20225687073	20171124	00011827	FA	001300011827	00000028404	00000000200	00000000568	7	2017-12-05 18:44:23.476865
010	184	27112378257	20170808	00015124	FA	030000015124	00000059297	00000000200	00000001187	14	2017-09-27 09:37:53.511455
010	185	27219273431	20170815	00015380	FA	030000015380	00000036364	00000000200	00000000727	14	2017-09-27 09:37:53.511455
010	186	20273644912	20170801	00014869	FA	030000014869	00000019835	00000000200	00000000397	14	2017-09-27 09:37:53.511455
010	187	20225250473	20170808	00015125	FA	030000015125	00000036364	00000000200	00000000727	14	2017-09-27 09:37:53.511455
010	188	23237095979	20170815	00015381	FA	030000015381	00000036364	00000000200	00000000727	14	2017-09-27 09:37:53.511455
010	189	23141622099	20170801	00014870	FA	030000014870	00000056199	00000000200	00000001124	14	2017-09-27 09:37:53.511455
010	190	27363206641	20170808	00015126	FA	030000015126	00000036364	00000000200	00000000727	14	2017-09-27 09:37:53.511455
010	191	27041619916	20170801	00014871	FA	030000014871	00000147273	00000000200	00000002946	14	2017-09-27 09:37:53.511455
010	192	27248176607	20170808	00015127	FA	030000015127	00000011983	00000000200	00000000240	14	2017-09-27 09:37:53.511455
010	193	20388075806	20170815	00015383	FA	030000015383	00000038016	00000000200	00000000760	14	2017-09-27 09:37:53.511455
010	194	20243042802	20170801	00014872	FA	030000014872	00000105207	00000000200	00000002103	14	2017-09-27 09:37:53.511455
010	195	20226158074	20170815	00015384	FA	030000015384	00000044793	00000000200	00000000896	14	2017-09-27 09:37:53.511455
010	196	27200952796	20170801	00014873	FA	030000014873	00000089091	00000000200	00000001782	14	2017-09-27 09:37:53.511455
010	197	27163184465	20170815	00015385	FA	030000015385	00000259504	00000000200	00000005190	14	2017-09-27 09:37:53.511455
010	198	27313340754	20170801	00014874	FA	030000014874	00000070910	00000000200	00000001418	14	2017-09-27 09:37:53.511455
010	199	27363206641	20170801	00014875	FA	030000014875	00000040414	00000000200	00000000808	14	2017-09-27 09:37:53.511455
010	200	27313340339	20170801	00014876	FA	030000014876	00000048678	00000000200	00000000973	14	2017-09-27 09:37:53.511455
010	201	27237091987	20170801	00014877	FA	030000014877	00000039261	00000000200	00000000786	14	2017-09-27 09:37:53.511455
010	202	27112378257	20170801	00014878	FA	030000014878	00000038367	00000000200	00000000767	14	2017-09-27 09:37:53.511455
010	203	27361302759	20170801	00014879	FA	030000014879	00000044050	00000000200	00000000881	14	2017-09-27 09:37:53.511455
010	204	27248176607	20170801	00014880	FA	030000014880	00000041631	00000000200	00000000832	14	2017-09-27 09:37:53.511455
010	205	20388075806	20170801	00014881	FA	030000014881	00000116823	00000000200	00000002336	14	2017-09-27 09:37:53.511455
010	206	27215220112	20170823	00015664	FA	030000015664	00000308247	00000000200	00000006168	14	2017-09-27 09:37:53.511455
010	207	20295456478	20170825	00015732	FA	030000015732	00000020265	00000000200	00000000405	14	2017-09-27 09:37:53.511455
010	208	27121002650	20170825	00015733	FA	030000015733	00000066943	00000000200	00000001339	14	2017-09-27 09:37:53.511455
010	209	27367571123	20170825	00015734	FA	030000015734	00000193388	00000000200	00000003868	14	2017-09-27 09:37:53.511455
010	210	27925411626	20170825	00015735	FA	030000015735	00000070137	00000000200	00000001403	14	2017-09-27 09:37:53.511455
010	211	27313340339	20170825	00015736	FA	030000015736	00000081551	00000000200	00000001632	14	2017-09-27 09:37:53.511455
010	212	27346653502	20170825	00015737	FA	030000015737	00000133884	00000000200	00000002678	14	2017-09-27 09:37:53.511455
010	213	27219273431	20170825	00015738	FA	030000015738	00000036364	00000000200	00000000727	14	2017-09-27 09:37:53.511455
010	214	27351546862	20170825	00015739	FA	030000015739	00000036364	00000000200	00000000727	14	2017-09-27 09:37:53.511455
010	215	23237095979	20170825	00015740	FA	030000015740	00000030579	00000000200	00000000612	14	2017-09-27 09:37:53.511455
010	216	27237091987	20170825	00015741	FA	030000015741	00000021488	00000000200	00000000430	14	2017-09-27 09:37:53.511455
010	217	27044920102	20170825	00015742	FA	030000015742	00000012185	00000000200	00000000243	14	2017-09-27 09:37:53.511455
010	218	20273644912	20170825	00015743	FA	030000015743	00000064527	00000000200	00000001290	14	2017-09-27 09:37:53.511455
010	219	20267029114	20170825	00015744	FA	030000015744	00000008430	00000000200	00000000169	14	2017-09-27 09:37:53.511455
010	220	20226158074	20170825	00015745	FA	030000015745	00000036364	00000000200	00000000727	14	2017-09-27 09:37:53.511455
010	221	27313340754	20170825	00015746	FA	030000015746	00000055372	00000000200	00000001108	14	2017-09-27 09:37:53.511455
010	222	27101402156	20170825	00015747	FA	030000015747	00000021488	00000000200	00000000430	14	2017-09-27 09:37:53.511455
010	223	27245845494	20170825	00015748	FA	030000015748	00000036364	00000000200	00000000727	14	2017-09-27 09:37:53.511455
010	224	27246645227	20170825	00015749	FA	030000015749	00000024545	00000000200	00000000491	14	2017-09-27 09:37:53.511455
010	225	20388075806	20170825	00015750	FA	030000015750	00000130504	00000000200	00000002610	14	2017-09-27 09:37:53.511455
010	226	20173955449	20170811	00015239	FA	030000015239	00000066943	00000000200	00000001339	14	2017-09-27 09:37:53.511455
010	227	27044920102	20170811	00015240	FA	030000015240	00000032914	00000000200	00000000658	14	2017-09-27 09:37:53.511455
010	228	20271066547	20170811	00015241	FA	030000015241	00000030579	00000000200	00000000612	14	2017-09-27 09:37:53.511455
010	229	20148499161	20170804	00014986	FA	030000014986	00000060967	00000000200	00000001219	14	2017-09-27 09:37:53.511455
010	230	27044920102	20170804	00014987	FA	030000014987	00000044525	00000000200	00000000890	14	2017-09-27 09:37:53.511455
010	231	27245845494	20170811	00015243	FA	030000015243	00000019835	00000000200	00000000397	14	2017-09-27 09:37:53.511455
010	232	20082698508	20170804	00014988	FA	030000014988	00000077438	00000000200	00000001548	14	2017-09-27 09:37:53.511455
010	233	27121002650	20170811	00015244	FA	030000015244	00000030579	00000000200	00000000612	14	2017-09-27 09:37:53.511455
010	234	33670386029	20170818	00015500	FA	030000015500	00000023140	00000000200	00000000463	14	2017-09-27 09:37:53.511455
010	235	27942269566	20170804	00014989	FA	030000014989	00000129091	00000000200	00000002583	14	2017-09-27 09:37:53.511455
010	236	27367571123	20170811	00015245	FA	030000015245	00000190074	00000000200	00000003801	14	2017-09-27 09:37:53.511455
010	237	20295456478	20170818	00015501	FA	030000015501	00000030744	00000000200	00000000615	14	2017-09-27 09:37:53.511455
010	238	20295456478	20170804	00014990	FA	030000014990	00000020265	00000000200	00000000405	14	2017-09-27 09:37:53.511455
010	239	27925411626	20170811	00015246	FA	030000015246	00000110190	00000000200	00000002203	14	2017-09-27 09:37:53.511455
010	240	20148499161	20170818	00015502	FA	030000015502	00000049577	00000000200	00000000992	14	2017-09-27 09:37:53.511455
010	241	27245845494	20170804	00014991	FA	030000014991	00000073141	00000000200	00000001463	14	2017-09-27 09:37:53.511455
010	242	27313340339	20170811	00015247	FA	030000015247	00000029504	00000000200	00000000590	14	2017-09-27 09:37:53.511455
010	243	27313340754	20170818	00015503	FA	030000015503	00000036364	00000000200	00000000727	14	2017-09-27 09:37:53.511455
010	244	27121002650	20170804	00014992	FA	030000014992	00000066943	00000000200	00000001339	14	2017-09-27 09:37:53.511455
010	245	20290121753	20170811	00015248	FA	030000015248	00000034298	00000000200	00000000687	14	2017-09-27 09:37:53.511455
010	246	27121002650	20170818	00015504	FA	030000015504	00000091901	00000000200	00000001838	14	2017-09-27 09:37:53.511455
010	247	27367571123	20170804	00014993	FA	030000014993	00000198105	00000000200	00000003962	14	2017-09-27 09:37:53.511455
010	248	27346653782	20170811	00015249	FA	030000015249	00000030579	00000000200	00000000612	14	2017-09-27 09:37:53.511455
010	249	27367571123	20170818	00015505	FA	030000015505	00000168761	00000000200	00000003376	14	2017-09-27 09:37:53.511455
010	250	27101402156	20170804	00014994	FA	030000014994	00000037951	00000000200	00000000759	14	2017-09-27 09:37:53.511455
010	251	27219273431	20170811	00015250	FA	030000015250	00000060496	00000000200	00000001210	14	2017-09-27 09:37:53.511455
010	252	27101402156	20170818	00015506	FA	030000015506	00000019008	00000000200	00000000380	14	2017-09-27 09:37:53.511455
010	253	20226158074	20170804	00014995	FA	030000014995	00000032682	00000000200	00000000654	14	2017-09-27 09:37:53.511455
010	254	27237091987	20170811	00015251	FA	030000015251	00000034710	00000000200	00000000694	14	2017-09-27 09:37:53.511455
010	255	27313340339	20170818	00015507	FA	030000015507	00000037810	00000000200	00000000756	14	2017-09-27 09:37:53.511455
010	256	27313340339	20170804	00014996	FA	030000014996	00000084463	00000000200	00000001689	14	2017-09-27 09:37:53.511455
010	257	20118857020	20170811	00015252	FA	030000015252	00000102369	00000000200	00000002047	14	2017-09-27 09:37:53.511455
010	258	23190178064	20170818	00015508	FA	030000015508	00000036364	00000000200	00000000727	14	2017-09-27 09:37:53.511455
010	259	27219273431	20170804	00014997	FA	030000014997	00000030579	00000000200	00000000612	14	2017-09-27 09:37:53.511455
010	260	20267029114	20170811	00015253	FA	030000015253	00000030579	00000000200	00000000612	14	2017-09-27 09:37:53.511455
010	261	20082698508	20170818	00015509	FA	030000015509	00000054876	00000000200	00000001098	14	2017-09-27 09:37:53.511455
010	262	23237095979	20170804	00014998	FA	030000014998	00000019256	00000000200	00000000385	14	2017-09-27 09:37:53.511455
010	263	20388075806	20170811	00015254	FA	030000015254	00000036364	00000000200	00000000727	14	2017-09-27 09:37:53.511455
010	264	20273644912	20170818	00015510	FA	030000015510	00000023822	00000000200	00000000476	14	2017-09-27 09:37:53.511455
010	265	27351546862	20170804	00014999	FA	030000014999	00000066943	00000000200	00000001339	14	2017-09-27 09:37:53.511455
010	266	27216609528	20170811	00015255	FA	030000015255	00000016942	00000000200	00000000339	14	2017-09-27 09:37:53.511455
010	267	20118857020	20170818	00015511	FA	030000015511	00000016942	00000000200	00000000339	14	2017-09-27 09:37:53.511455
010	268	27216609528	20170804	00015000	FA	030000015000	00000008430	00000000200	00000000169	14	2017-09-27 09:37:53.511455
010	269	27246645227	20170811	00015256	FA	030000015256	00000038893	00000000200	00000000778	14	2017-09-27 09:37:53.511455
010	270	20267029114	20170818	00015512	FA	030000015512	00000036364	00000000200	00000000727	14	2017-09-27 09:37:53.511455
010	271	27237091987	20170804	00015001	FA	030000015001	00000036364	00000000200	00000000727	14	2017-09-27 09:37:53.511455
010	272	27361302759	20170811	00015257	FA	030000015257	00000066943	00000000200	00000001339	14	2017-09-27 09:37:53.511455
010	273	27361302759	20170804	00015002	FA	030000015002	00000032186	00000000200	00000000644	14	2017-09-27 09:37:53.511455
010	274	27313340754	20170811	00015258	FA	030000015258	00000073141	00000000200	00000001463	14	2017-09-27 09:37:53.511455
010	275	27346653782	20170818	00015514	FA	030000015514	00000066943	00000000200	00000001339	14	2017-09-27 09:37:53.511455
010	276	23190178064	20170804	00015003	FA	030000015003	00000029174	00000000200	00000000583	14	2017-09-27 09:37:53.511455
010	277	27219273431	20170818	00015515	FA	030000015515	00000052067	00000000200	00000001042	14	2017-09-27 09:37:53.511455
010	278	20273644912	20170804	00015004	FA	030000015004	00000033892	00000000200	00000000678	14	2017-09-27 09:37:53.511455
010	279	27237091987	20170818	00015516	FA	030000015516	00000066943	00000000200	00000001339	14	2017-09-27 09:37:53.511455
010	280	27297722927	20170804	00015005	FA	030000015005	00000030165	00000000200	00000000603	14	2017-09-27 09:37:53.511455
010	281	27216609528	20170818	00015517	FA	030000015517	00000052809	00000000200	00000001057	14	2017-09-27 09:37:53.511455
010	282	20267029114	20170804	00015006	FA	030000015006	00000008430	00000000200	00000000169	14	2017-09-27 09:37:53.511455
010	283	27363206641	20170818	00015518	FA	030000015518	00000036364	00000000200	00000000727	14	2017-09-27 09:37:53.511455
010	284	27248176607	20170804	00015007	FA	030000015007	00000065660	00000000200	00000001312	14	2017-09-27 09:37:53.511455
010	285	27248176607	20170818	00015519	FA	030000015519	00000025847	00000000200	00000000517	14	2017-09-27 09:37:53.511455
010	286	27313340754	20170804	00015008	FA	030000015008	00000014931	00000000200	00000000299	14	2017-09-27 09:37:53.511455
010	287	27351546862	20170818	00015520	FA	030000015520	00000088431	00000000200	00000001769	14	2017-09-27 09:37:53.511455
010	288	27402096204	20170804	00015009	FA	030000015009	00000036364	00000000200	00000000727	14	2017-09-27 09:37:53.511455
010	289	27266554740	20170818	00015521	FA	030000015521	00000066943	00000000200	00000001339	14	2017-09-27 09:37:53.511455
010	290	20149833510	20170818	00015522	FA	030000015522	00000048602	00000000200	00000000973	14	2017-09-27 09:37:53.511455
010	291	27044920102	20170829	00015859	FA	030000015859	00000021488	00000000200	00000000430	14	2017-09-27 09:37:53.511455
010	292	27305781466	20170829	00015860	FA	030000015860	00000016942	00000000200	00000000339	14	2017-09-27 09:37:53.511455
010	293	27942269566	20170829	00015861	FA	030000015861	00000055690	00000000200	00000001114	14	2017-09-27 09:37:53.511455
010	294	20295456478	20170829	00015862	FA	030000015862	00000021488	00000000200	00000000430	14	2017-09-27 09:37:53.511455
010	295	27044920102	20170822	00015607	FA	030000015607	00000046528	00000000200	00000000930	14	2017-09-27 09:37:53.511455
010	296	27245845494	20170829	00015863	FA	030000015863	00000057852	00000000200	00000001157	14	2017-09-27 09:37:53.511455
010	297	20082698508	20170822	00015608	FA	030000015608	00000054876	00000000200	00000001098	14	2017-09-27 09:37:53.511455
010	298	20271066547	20170822	00015609	FA	030000015609	00000053555	00000000200	00000001071	14	2017-09-27 09:37:53.511455
010	299	27367571123	20170829	00015865	FA	030000015865	00000218793	00000000200	00000004376	14	2017-09-27 09:37:53.511455
010	300	27942269566	20170822	00015610	FA	030000015610	00000079339	00000000200	00000001587	14	2017-09-27 09:37:53.511455
010	301	27313340339	20170829	00015866	FA	030000015866	00000040916	00000000200	00000000819	14	2017-09-27 09:37:53.511455
010	302	20295456478	20170822	00015611	FA	030000015611	00000066943	00000000200	00000001339	14	2017-09-27 09:37:53.511455
010	303	20290121753	20170829	00015867	FA	030000015867	00000057852	00000000200	00000001157	14	2017-09-27 09:37:53.511455
010	304	27245845494	20170822	00015612	FA	030000015612	00000059765	00000000200	00000001196	14	2017-09-27 09:37:53.511455
010	305	27219273431	20170829	00015868	FA	030000015868	00000036364	00000000200	00000000727	14	2017-09-27 09:37:53.511455
010	306	27121002650	20170822	00015613	FA	030000015613	00000061323	00000000200	00000001227	14	2017-09-27 09:37:53.511455
010	307	27351546862	20170829	00015869	FA	030000015869	00000066943	00000000200	00000001339	14	2017-09-27 09:37:53.511455
003	540	20273644912	20171125	00011861	FA	001300011861	00000021372	00000000200	00000000427	7	2017-12-05 18:44:23.476865
003	541	27210701279	20171125	00011865	FA	001300011865	00000095367	00000000200	00000001907	7	2017-12-05 18:44:23.476865
003	542	20261719887	20171125	00005650	FA	001800005650	00000094561	00000000200	00000001891	7	2017-12-05 18:44:23.476865
003	543	27952625514	20171125	00005651	FA	001800005651	00000074456	00000000200	00000001489	7	2017-12-05 18:44:23.476865
003	544	27297722927	20171125	00006813	FA	001800006813	00000011386	00000000200	00000000228	7	2017-12-05 18:44:23.476865
003	545	20273644912	20171125	00006814	FA	001800006814	00000020833	00000000200	00000000417	7	2017-12-05 18:44:23.476865
003	546	20305781720	20171125	00020887	FA	001700020887	00000401757	00000000200	00000008035	7	2017-12-05 18:44:23.476865
003	547	27173955451	20171125	00020888	FA	001700020888	00000074483	00000000200	00000001490	7	2017-12-05 18:44:23.476865
003	548	27056603560	20171125	00020889	FA	001700020889	00000060508	00000000200	00000001210	7	2017-12-05 18:44:23.476865
003	549	20261719887	20171125	00020890	FA	001700020890	00000129260	00000000200	00000002585	7	2017-12-05 18:44:23.476865
003	550	27188206463	20171125	00020891	FA	001700020891	00000035829	00000000200	00000000717	7	2017-12-05 18:44:23.476865
003	551	27222713140	20171125	00020892	FA	001700020892	00000169132	00000000200	00000003383	7	2017-12-05 18:44:23.476865
003	552	23163173824	20171125	00020893	FA	001700020893	00000083780	00000000200	00000001676	7	2017-12-05 18:44:23.476865
003	553	27305781342	20171125	00020735	FA	001700020735	00000100548	00000000200	00000002011	7	2017-12-05 18:44:23.476865
003	554	27942269566	20171125	00020736	FA	001700020736	00000032318	00000000200	00000000646	7	2017-12-05 18:44:23.476865
003	555	27237091987	20171125	00020737	FA	001700020737	00000068221	00000000200	00000001364	7	2017-12-05 18:44:23.476865
003	556	27215184760	20171125	00020738	FA	001700020738	00000017003	00000000200	00000000340	7	2017-12-05 18:44:23.476865
003	557	27044920102	20171125	00020739	FA	001700020739	00000027100	00000000200	00000000542	7	2017-12-05 18:44:23.476865
003	558	27313340339	20171125	00020740	FA	001700020740	00000027368	00000000200	00000000547	7	2017-12-05 18:44:23.476865
003	559	27064350809	20171125	00020895	FA	001700020895	00000168913	00000000200	00000003378	7	2017-12-05 18:44:23.476865
003	560	23344036209	20171125	00020896	FA	001700020896	00000196564	00000000200	00000003931	7	2017-12-05 18:44:23.476865
003	561	27351546986	20171125	00020741	FA	001700020741	00000097680	00000000200	00000001954	7	2017-12-05 18:44:23.476865
003	562	23289795464	20171125	00020897	FA	001700020897	00000297223	00000000200	00000005944	7	2017-12-05 18:44:23.476865
003	563	27297722927	20171125	00020742	FA	001700020742	00000061666	00000000200	00000001233	7	2017-12-05 18:44:23.476865
003	564	20226158074	20171125	00020743	FA	001700020743	00000072889	00000000200	00000001458	7	2017-12-05 18:44:23.476865
003	565	20274035170	20171125	00027002	FA	001500027002	00000026458	00000000200	00000000529	7	2017-12-05 18:44:23.476865
003	566	23289795464	20171125	00027003	FA	001500027003	00000049957	00000000200	00000000999	7	2017-12-05 18:44:23.476865
003	567	27309749079	20171125	00027004	FA	001500027004	00000015894	00000000200	00000000318	7	2017-12-05 18:44:23.476865
003	568	27125944707	20171125	00027005	FA	001500027005	00000037704	00000000200	00000000754	7	2017-12-05 18:44:23.476865
003	569	23331976199	20171125	00026529	FA	001500026529	00000011293	00000000200	00000000226	7	2017-12-05 18:44:23.476865
003	570	27226803233	20171125	00026530	FA	001500026530	00000026409	00000000200	00000000528	7	2017-12-05 18:44:23.476865
003	571	20313340792	20171125	00026531	FA	001500026531	00000015692	00000000200	00000000314	7	2017-12-05 18:44:23.476865
003	572	27942269566	20171125	00026532	FA	001500026532	00000093927	00000000200	00000001879	7	2017-12-05 18:44:23.476865
003	573	27215184760	20171125	00026533	FA	001500026533	00000047653	00000000200	00000000953	7	2017-12-05 18:44:23.476865
003	574	20273644912	20171125	00026534	FA	001500026534	00000017706	00000000200	00000000354	7	2017-12-05 18:44:23.476865
003	575	27313340339	20171125	00026535	FA	001500026535	00000008693	00000000200	00000000174	7	2017-12-05 18:44:23.476865
003	576	27351546862	20171125	00011863	FA	001300011863	00000026458	00000000200	00000000529	7	2017-12-05 18:44:23.476865
003	577	20226158074	20171127	00011917	FA	001300011917	00000097739	00000000200	00000001955	7	2017-12-05 18:44:23.476865
003	578	20054139285	20171127	00005659	FA	001800005659	00001566622	00000000200	00000031332	7	2017-12-05 18:44:23.476865
003	579	20078192144	20171127	00011902	FA	001300011902	00000045106	00000000200	00000000902	7	2017-12-05 18:44:23.476865
003	580	20305781720	20171127	00027045	FA	001500027045	00000388171	00000000200	00000007763	7	2017-12-05 18:44:23.476865
003	581	30999093120	20171127	00011914	FA	001300011914	00000281852	00000000200	00000005637	7	2017-12-05 18:44:23.476865
003	582	20213546113	20171127	00009784	FA	001300009784	00000033966	00000000200	00000000679	7	2017-12-05 18:44:23.476865
003	583	20940818649	20171128	00011954	FA	001300011954	00000090186	00000000200	00000001804	7	2017-12-05 18:44:23.476865
003	584	27219273431	20171128	00020813	FA	001700020813	00000108056	00000000200	00000002161	7	2017-12-05 18:44:23.476865
003	585	27125944707	20171128	00009792	FA	001300009792	00000035141	00000000200	00000000703	7	2017-12-05 18:44:23.476865
003	586	20201331790	20171128	00011993	FA	001300011993	00000025944	00000000200	00000000519	7	2017-12-05 18:44:23.476865
003	587	27325453465	20171128	00011963	FA	001300011963	00000031395	00000000200	00000000628	7	2017-12-05 18:44:23.476865
003	588	27367571123	20171128	00020967	FA	001700020967	00000016743	00000000200	00000000335	7	2017-12-05 18:44:23.476865
003	589	20305781720	20171128	00020968	FA	001700020968	00000271604	00000000200	00000005432	7	2017-12-05 18:44:23.476865
003	590	30670364603	20171128	00020969	FA	001700020969	00000040622	00000000200	00000000812	7	2017-12-05 18:44:23.476865
003	591	27952625514	20171128	00020970	FA	001700020970	00000162766	00000000200	00000003255	7	2017-12-05 18:44:23.476865
003	592	27952625514	20171128	00020971	FA	001700020971	00000625405	00000000200	00000012508	7	2017-12-05 18:44:23.476865
003	593	27346653502	20171128	00020811	FA	001700020811	00000063887	00000000200	00000001278	7	2017-12-05 18:44:23.476865
003	594	20305781720	20171128	00005670	FA	001800005670	00000145269	00000000200	00000002905	7	2017-12-05 18:44:23.476865
003	595	27346653502	20171128	00006831	FA	001800006831	00000053914	00000000200	00000001078	7	2017-12-05 18:44:23.476865
003	596	20305781720	20171128	00027095	FA	001500027095	00000073667	00000000200	00000001473	7	2017-12-05 18:44:23.476865
003	597	20305781720	20171128	00027096	FA	001500027096	00000557546	00000000200	00000011151	7	2017-12-05 18:44:23.476865
003	598	20305781720	20171128	00027097	FA	001500027097	00000382863	00000000200	00000007657	7	2017-12-05 18:44:23.476865
003	599	20261719887	20171128	00027098	FA	001500027098	00000194448	00000000200	00000003889	7	2017-12-05 18:44:23.476865
003	600	27367571123	20171128	00027099	FA	001500027099	00000265837	00000000200	00000005317	7	2017-12-05 18:44:23.476865
010	308	27367571123	20170822	00015614	FA	030000015614	00000212812	00000000200	00000004256	14	2017-09-27 09:37:53.511455
010	309	23190178064	20170829	00015870	FA	030000015870	00000054711	00000000200	00000001094	14	2017-09-27 09:37:53.511455
010	310	20213549619	20170808	00015103	FA	030000015103	00000929504	00000000200	00000018592	14	2017-09-27 09:37:53.511455
010	311	27313340339	20170822	00015615	FA	030000015615	00000075166	00000000200	00000001503	14	2017-09-27 09:37:53.511455
010	312	27297722927	20170829	00015871	FA	030000015871	00000087679	00000000200	00000001754	14	2017-09-27 09:37:53.511455
013	272	20225250473	20171012	00226602	FA	001000226602	00000061087	00000000200	00000001222	17	2017-11-15 11:40:32.00095
013	273	20173955449	20171012	00226603	FA	001000226603	00000064681	00000000200	00000001294	17	2017-11-15 11:40:32.00095
013	274	27101402156	20171012	00226604	FA	001000226604	00000074291	00000000200	00000001486	17	2017-11-15 11:40:32.00095
013	275	20271066547	20171012	00226605	FA	001000226605	00000044134	00000000200	00000000883	17	2017-11-15 11:40:32.00095
013	276	20284642369	20171012	00226606	FA	001000226606	00000070461	00000000200	00000001409	17	2017-11-15 11:40:32.00095
013	277	20149833510	20171012	00226607	FA	001000226607	00000048397	00000000200	00000000968	17	2017-11-15 11:40:32.00095
013	278	20082698508	20171012	00226608	FA	001000226608	00000130610	00000000200	00000002612	17	2017-11-15 11:40:32.00095
013	279	27121002650	20171012	00226609	FA	001000226609	00000071912	00000000200	00000001438	17	2017-11-15 11:40:32.00095
013	280	20273644912	20171012	00226610	FA	001000226610	00000113774	00000000200	00000002275	17	2017-11-15 11:40:32.00095
013	281	20118857020	20171012	00226611	FA	001000226611	00000118937	00000000200	00000002379	17	2017-11-15 11:40:32.00095
013	282	20182383016	20171012	00226614	FA	001000226614	00000079125	00000000200	00000001583	17	2017-11-15 11:40:32.00095
013	283	20084272648	20171012	00226620	FA	001000226620	00000087660	00000000200	00000001753	17	2017-11-15 11:40:32.00095
013	284	20179005353	20171012	00226639	FA	001000226639	00000010267	00000000600	00000000616	17	2017-11-15 11:40:32.00095
013	285	27224183203	20171012	00226640	FA	001000226640	00000020636	00000000200	00000000413	17	2017-11-15 11:40:32.00095
013	286	27293830199	20171012	00027004	FA	001300027004	00000146033	00000000600	00000008762	17	2017-11-15 11:40:32.00095
013	287	20290347158	20171012	00226649	FA	001000226649	00000033219	00000000600	00000001993	17	2017-11-15 11:40:32.00095
013	288	30711644578	20171012	00009328	FA	001100009328	00000100556	00000000200	00000002011	17	2017-11-15 11:40:32.00095
013	289	30657533587	20171012	00009329	FA	001100009329	00000049251	00000000200	00000000985	17	2017-11-15 11:40:32.00095
013	290	27181000851	20171013	00226666	FA	001000226666	00000378078	00000000600	00000022685	17	2017-11-15 11:40:32.00095
013	291	20333551455	20171013	00226673	FA	001000226673	00000121599	00000000600	00000007296	17	2017-11-15 11:40:32.00095
013	292	27294165660	20171013	00226685	FA	001000226685	00000224206	00000000600	00000013452	17	2017-11-15 11:40:32.00095
013	293	23044920484	20171013	00226686	FA	001000226686	00000025092	00000000600	00000001506	17	2017-11-15 11:40:32.00095
013	294	27000000006	20171013	00098613	FA	001000098613	00000109711	00000000200	00000002194	17	2017-11-15 11:40:32.00095
013	295	27230149416	20171013	00098615	FA	001000098615	00000093826	00000000200	00000001877	17	2017-11-15 11:40:32.00095
013	296	20215184596	20171013	00226713	FA	001000226713	00000061844	00000000200	00000001237	17	2017-11-15 11:40:32.00095
013	297	27228688989	20171013	00023412	FA	001100023412	00000033219	00000000200	00000000664	17	2017-11-15 11:40:32.00095
013	298	20243042802	20171013	00023414	FA	001100023414	00000215419	00000000200	00000004308	17	2017-11-15 11:40:32.00095
013	299	30542722688	20171013	00009334	FA	001100009334	00000072963	00000000200	00000001459	17	2017-11-15 11:40:32.00095
013	300	20082121235	20171013	00023417	FA	001100023417	00000074492	00000000200	00000001490	17	2017-11-15 11:40:32.00095
013	301	30714049069	20171013	00009337	FA	001100009337	00000302598	00000000200	00000006052	17	2017-11-15 11:40:32.00095
013	302	30714049069	20171013	00009338	FA	001100009338	00000033219	00000000200	00000000664	17	2017-11-15 11:40:32.00095
013	303	20269447649	20171013	00023420	FA	001100023420	00000053855	00000000600	00000003231	17	2017-11-15 11:40:32.00095
013	304	27224236773	20171014	00098620	FA	001000098620	00000066063	00000000200	00000001321	17	2017-11-15 11:40:32.00095
013	305	30712127895	20171014	00098636	FA	001000098636	00000209872	00000000200	00000004197	17	2017-11-15 11:40:32.00095
013	306	20147388323	20171014	00098650	FA	001000098650	00000182596	00000000200	00000003652	17	2017-11-15 11:40:32.00095
013	307	27300258544	20171014	00226730	FA	001000226730	00000179605	00000000600	00000010776	17	2017-11-15 11:40:32.00095
013	308	20269447649	20171014	00023422	FA	001100023422	00000066437	00000000600	00000003986	17	2017-11-15 11:40:32.00095
013	309	27160615899	20171014	00023427	FA	001100023427	00000071444	00000000600	00000004287	17	2017-11-15 11:40:32.00095
013	310	27274033784	20171014	00023430	FA	001100023430	00000043150	00000000600	00000002589	17	2017-11-15 11:40:32.00095
013	311	27363206641	20171014	00023431	FA	001100023431	00000205170	00000000200	00000004103	17	2017-11-15 11:40:32.00095
013	312	33542719449	20171014	00009343	FA	001100009343	00000135833	00000000200	00000002717	17	2017-11-15 11:40:32.00095
013	313	33542719449	20171014	00009344	FA	001100009344	00000029038	00000000200	00000000581	17	2017-11-15 11:40:32.00095
013	314	20388075806	20171014	00023435	FA	001100023435	00000141802	00000000200	00000002836	17	2017-11-15 11:40:32.00095
013	315	20110567570	20171014	00009345	FA	001100009345	00000296105	00000000200	00000005922	17	2017-11-15 11:40:32.00095
013	316	27136572348	20171017	00098671	FA	001000098671	00000523129	00000000200	00000010463	17	2017-11-15 11:40:32.00095
013	317	27257772123	20171017	00226763	FA	001000226763	00000101253	00000000600	00000006075	17	2017-11-15 11:40:32.00095
013	318	20280000893	20171017	00027127	FA	001300027127	00000233172	00000000200	00000004663	17	2017-11-15 11:40:32.00095
013	319	20205896857	20171017	00023443	FA	001100023443	00000270511	00000000200	00000005410	17	2017-11-15 11:40:32.00095
013	320	27259753274	20171017	00009352	FA	001100009352	00000042714	00000000200	00000000854	17	2017-11-15 11:40:32.00095
013	321	30542722688	20171017	00009353	FA	001100009353	00000029038	00000000200	00000000581	17	2017-11-15 11:40:32.00095
013	322	27224183203	20171017	00023448	FA	001100023448	00000066437	00000000200	00000001329	17	2017-11-15 11:40:32.00095
013	323	27224236773	20171018	00098687	FA	001000098687	00000056119	00000000200	00000001122	17	2017-11-15 11:40:32.00095
013	324	27133302994	20171018	00098697	FA	001000098697	00000043828	00000000600	00000002630	17	2017-11-15 11:40:32.00095
013	325	20147388323	20171018	00098715	FA	001000098715	00000114593	00000000200	00000002292	17	2017-11-15 11:40:32.00095
013	326	30707057633	20171018	00098716	FA	001000098716	00000113785	00000000200	00000002276	17	2017-11-15 11:40:32.00095
013	327	20110567570	20171018	00098720	FA	001000098720	00000873590	00000000200	00000017472	17	2017-11-15 11:40:32.00095
013	328	27165291269	20171018	00226824	FA	001000226824	00000187719	00000000600	00000011263	17	2017-11-15 11:40:32.00095
013	329	20147959282	20171018	00226828	FA	001000226828	00000134639	00000000200	00000002693	17	2017-11-15 11:40:32.00095
013	330	23073219329	20171018	00226833	FA	001000226833	00000074492	00000000600	00000004470	17	2017-11-15 11:40:32.00095
013	331	20269447649	20171018	00226843	FA	001000226843	00000057598	00000000600	00000003456	17	2017-11-15 11:40:32.00095
013	332	27300258544	20171018	00226852	FA	001000226852	00000153643	00000000600	00000009219	17	2017-11-15 11:40:32.00095
013	333	27228688989	20171018	00226869	FA	001000226869	00000066437	00000000200	00000001329	17	2017-11-15 11:40:32.00095
013	334	20082121235	20171018	00023454	FA	001100023454	00000107710	00000000200	00000002154	17	2017-11-15 11:40:32.00095
013	335	27186187666	20171018	00023457	FA	001100023457	00000192158	00000000200	00000003843	17	2017-11-15 11:40:32.00095
013	336	20290347158	20171019	00023461	FA	001100023461	00000055196	00000000600	00000003312	17	2017-11-15 11:40:32.00095
013	337	27952625514	20171019	00098742	FA	001000098742	00000625262	00000000200	00000012505	17	2017-11-15 11:40:32.00095
013	338	20226157833	20171019	00098743	FA	001000098743	00000064477	00000000200	00000001290	17	2017-11-15 11:40:32.00095
013	339	27952625514	20171019	00098744	FA	001000098744	00000135841	00000000200	00000002717	17	2017-11-15 11:40:32.00095
013	340	27952625514	20171019	00098745	FA	001000098745	00000371061	00000000200	00000007421	17	2017-11-15 11:40:32.00095
013	341	27952625514	20171019	00098746	FA	001000098746	00000039893	00000000200	00000000798	17	2017-11-15 11:40:32.00095
013	342	27952625514	20171019	00098747	FA	001000098747	00000280407	00000000200	00000005608	17	2017-11-15 11:40:32.00095
013	343	27952625514	20171019	00098748	FA	001000098748	00000773861	00000000200	00000015477	17	2017-11-15 11:40:32.00095
013	344	20305781720	20171019	00098749	FA	001000098749	00001009251	00000000200	00000020185	17	2017-11-15 11:40:32.00095
013	345	30670364603	20171019	00098750	FA	001000098750	00000088090	00000000200	00000001762	17	2017-11-15 11:40:32.00095
013	346	20274035170	20171019	00098751	FA	001000098751	00000270462	00000000200	00000005409	17	2017-11-15 11:40:32.00095
013	347	20274035170	20171019	00098752	FA	001000098752	00000019985	00000000200	00000000400	17	2017-11-15 11:40:32.00095
013	348	23289795464	20171019	00098753	FA	001000098753	00000552470	00000000200	00000011049	17	2017-11-15 11:40:32.00095
013	349	23289795464	20171019	00098754	FA	001000098754	00000011014	00000000200	00000000220	17	2017-11-15 11:40:32.00095
013	350	27367571123	20171019	00098755	FA	001000098755	00000323129	00000000200	00000006463	17	2017-11-15 11:40:32.00095
013	351	23289795464	20171019	00098756	FA	001000098756	00000465597	00000000200	00000009312	17	2017-11-15 11:40:32.00095
013	352	23289795464	20171019	00098757	FA	001000098757	00000041039	00000000200	00000000821	17	2017-11-15 11:40:32.00095
013	353	27173955451	20171019	00098758	FA	001000098758	00000112980	00000000200	00000002260	17	2017-11-15 11:40:32.00095
013	354	20073261806	20171019	00098759	FA	001000098759	00000309545	00000000200	00000006191	17	2017-11-15 11:40:32.00095
013	355	27316072483	20171019	00098760	FA	001000098760	00000053674	00000000200	00000001073	17	2017-11-15 11:40:32.00095
013	356	27056603560	20171019	00098761	FA	001000098761	00000673364	00000000200	00000013467	17	2017-11-15 11:40:32.00095
013	357	27056603560	20171019	00098762	FA	001000098762	00000032252	00000000200	00000000645	17	2017-11-15 11:40:32.00095
013	358	20054139285	20171019	00098763	FA	001000098763	00000259040	00000000200	00000005181	17	2017-11-15 11:40:32.00095
013	359	27163184732	20171019	00098764	FA	001000098764	00000124852	00000000200	00000002497	17	2017-11-15 11:40:32.00095
013	360	27232869564	20171019	00098765	FA	001000098765	00000760789	00000000200	00000015216	17	2017-11-15 11:40:32.00095
013	361	30715278347	20171019	00098767	FA	001000098767	00000157237	00000000200	00000003145	17	2017-11-15 11:40:32.00095
013	362	27188206463	20171019	00098768	FA	001000098768	00000073061	00000000200	00000001461	17	2017-11-15 11:40:32.00095
013	363	27064350809	20171019	00098769	FA	001000098769	00000120093	00000000200	00000002402	17	2017-11-15 11:40:32.00095
013	364	23344036209	20171019	00098770	FA	001000098770	00000124488	00000000200	00000002490	17	2017-11-15 11:40:32.00095
013	365	20163184592	20171019	00098771	FA	001000098771	00000200771	00000000200	00000004015	17	2017-11-15 11:40:32.00095
013	366	27125944707	20171019	00098772	FA	001000098772	00000109805	00000000200	00000002196	17	2017-11-15 11:40:32.00095
013	367	20078122316	20171019	00098773	FA	001000098773	00000113894	00000000200	00000002278	17	2017-11-15 11:40:32.00095
013	368	27222713140	20171019	00098774	FA	001000098774	00000417095	00000000200	00000008342	17	2017-11-15 11:40:32.00095
013	369	27064350809	20171019	00098775	FA	001000098775	00000199881	00000000200	00000003998	17	2017-11-15 11:40:32.00095
013	370	20267029114	20171019	00226876	FA	001000226876	00000042237	00000000200	00000000845	17	2017-11-15 11:40:32.00095
013	371	27313340339	20171019	00226877	FA	001000226877	00000126525	00000000200	00000002531	17	2017-11-15 11:40:32.00095
013	372	27346653502	20171019	00226878	FA	001000226878	00000165439	00000000200	00000003309	17	2017-11-15 11:40:32.00095
013	373	27000000006	20171019	00226879	FA	001000226879	00000101892	00000000200	00000002038	17	2017-11-15 11:40:32.00095
013	374	27297722927	20171019	00226880	FA	001000226880	00000462794	00000000200	00000009256	17	2017-11-15 11:40:32.00095
013	375	27248176607	20171019	00226881	FA	001000226881	00000076267	00000000200	00000001525	17	2017-11-15 11:40:32.00095
013	376	27394430655	20171019	00226882	FA	001000226882	00000079975	00000000200	00000001600	17	2017-11-15 11:40:32.00095
013	377	27950274226	20171019	00226883	FA	001000226883	00000032017	00000000200	00000000640	17	2017-11-15 11:40:32.00095
013	378	27363206641	20171019	00226884	FA	001000226884	00000491553	00000000200	00000009831	17	2017-11-15 11:40:32.00095
013	379	27305781466	20171019	00226885	FA	001000226885	00000064173	00000000200	00000001283	17	2017-11-15 11:40:32.00095
013	380	30714924644	20171019	00226886	FA	001000226886	00000097391	00000000200	00000001948	17	2017-11-15 11:40:32.00095
013	381	27044920102	20171019	00226887	FA	001000226887	00000076648	00000000200	00000001533	17	2017-11-15 11:40:32.00095
013	382	23331976199	20171019	00226888	FA	001000226888	00000374826	00000000200	00000007497	17	2017-11-15 11:40:32.00095
013	383	23331976199	20171019	00226889	FA	001000226889	00000124554	00000000200	00000002491	17	2017-11-15 11:40:32.00095
013	384	27313340754	20171019	00226890	FA	001000226890	00000115756	00000000200	00000002315	17	2017-11-15 11:40:32.00095
013	385	20388075806	20171019	00226891	FA	001000226891	00000123210	00000000200	00000002464	17	2017-11-15 11:40:32.00095
013	386	20313340792	20171019	00226892	FA	001000226892	00000010267	00000000600	00000000616	17	2017-11-15 11:40:32.00095
013	387	20273644912	20171019	00226893	FA	001000226893	00000108405	00000000200	00000002168	17	2017-11-15 11:40:32.00095
013	388	20173955449	20171019	00226894	FA	001000226894	00000126135	00000000200	00000002523	17	2017-11-15 11:40:32.00095
013	389	20284642369	20171019	00226895	FA	001000226895	00000053415	00000000200	00000001068	17	2017-11-15 11:40:32.00095
013	390	20271066547	20171019	00226897	FA	001000226897	00000032854	00000000200	00000000657	17	2017-11-15 11:40:32.00095
013	391	27121002650	20171019	00226898	FA	001000226898	00000146379	00000000200	00000002928	17	2017-11-15 11:40:32.00095
013	392	20118857020	20171019	00226899	FA	001000226899	00000153658	00000000200	00000003073	17	2017-11-15 11:40:32.00095
013	393	20225250473	20171019	00226900	FA	001000226900	00000033219	00000000200	00000000664	17	2017-11-15 11:40:32.00095
013	394	20213549619	20171019	00226901	FA	001000226901	00000662615	00000000200	00000013252	17	2017-11-15 11:40:32.00095
013	395	20082698508	20171019	00226902	FA	001000226902	00000105130	00000000200	00000002103	17	2017-11-15 11:40:32.00095
013	396	27237091987	20171019	00226903	FA	001000226903	00000013324	00000000200	00000000266	17	2017-11-15 11:40:32.00095
013	397	30670359170	20171019	00098778	FA	001000098778	00000415331	00000000200	00000008307	17	2017-11-15 11:40:32.00095
013	398	30609719954	20171019	00098781	FA	001000098781	00000155365	00000000200	00000003107	17	2017-11-15 11:40:32.00095
013	399	30714735817	20171019	00098789	FA	001000098789	00000427125	00000000200	00000008543	17	2017-11-15 11:40:32.00095
013	400	30712398562	20171019	00098790	FA	001000098790	00000068434	00000000200	00000001369	17	2017-11-15 11:40:32.00095
013	401	30715459228	20171019	00098793	FA	001000098793	00000063035	00000000600	00000003782	17	2017-11-15 11:40:32.00095
013	402	20345487035	20171019	00098795	FA	001000098795	00000102542	00000000600	00000006153	17	2017-11-15 11:40:32.00095
013	403	20300363130	20171019	00098799	FA	001000098799	00000563430	00000000200	00000011269	17	2017-11-15 11:40:32.00095
013	404	20084272648	20171019	00226909	FA	001000226909	00000093894	00000000200	00000001878	17	2017-11-15 11:40:32.00095
013	405	27224183203	20171019	00226925	FA	001000226925	00000020636	00000000200	00000000413	17	2017-11-15 11:40:32.00095
013	406	20179005353	20171019	00226928	FA	001000226928	00000013934	00000000600	00000000836	17	2017-11-15 11:40:32.00095
013	407	27293830199	20171019	00027203	FA	001300027203	00000118448	00000000600	00000007107	17	2017-11-15 11:40:32.00095
013	408	30712127895	20171020	00009371	FA	001100009371	00000018525	00000000200	00000000371	17	2017-11-15 11:40:32.00095
013	409	27000000006	20171020	00098833	FA	001000098833	00000090269	00000000200	00000001805	17	2017-11-15 11:40:32.00095
013	410	27230149416	20171020	00098835	FA	001000098835	00000188099	00000000200	00000003762	17	2017-11-15 11:40:32.00095
013	411	20073987610	20171020	00226949	FA	001000226949	00000399020	00000000600	00000023941	17	2017-11-15 11:40:32.00095
013	412	20215184596	20171020	00226974	FA	001000226974	00000033219	00000000200	00000000664	17	2017-11-15 11:40:32.00095
013	413	30670359170	20171020	00009369	FA	001100009369	00000104537	00000000200	00000002091	17	2017-11-15 11:40:32.00095
013	414	27370674057	20171020	00023468	FA	001100023468	00000074799	00000000600	00000004488	17	2017-11-15 11:40:32.00095
013	415	30657533587	20171020	00009370	FA	001100009370	00000049251	00000000200	00000000985	17	2017-11-15 11:40:32.00095
013	416	27224236773	20171021	00098836	FA	001000098836	00000091839	00000000200	00000001837	17	2017-11-15 11:40:32.00095
013	417	30712127895	20171021	00098846	FA	001000098846	00000386574	00000000200	00000007731	17	2017-11-15 11:40:32.00095
013	418	20147388323	20171021	00098858	FA	001000098858	00000116852	00000000200	00000002337	17	2017-11-15 11:40:32.00095
013	419	27228688989	20171021	00227010	FA	001000227010	00000109581	00000000200	00000002192	17	2017-11-15 11:40:32.00095
013	420	27300258544	20171021	00227013	FA	001000227013	00000173557	00000000600	00000010413	17	2017-11-15 11:40:32.00095
013	421	30542722688	20171021	00009375	FA	001100009375	00000058255	00000000200	00000001165	17	2017-11-15 11:40:32.00095
013	422	27224183203	20171021	00023492	FA	001100023492	00000033219	00000000200	00000000664	17	2017-11-15 11:40:32.00095
013	423	20226157833	20171023	00098872	FA	001000098872	00000057233	00000000200	00000001145	17	2017-11-15 11:40:32.00095
013	424	27952625514	20171023	00098873	FA	001000098873	00000675785	00000000200	00000013516	17	2017-11-15 11:40:32.00095
013	425	27952625514	20171023	00098871	FA	001000098871	00000773861	00000000200	00000015477	17	2017-11-15 11:40:32.00095
013	426	27952625514	20171023	00098874	FA	001000098874	00000450746	00000000200	00000009015	17	2017-11-15 11:40:32.00095
013	427	27952625514	20171023	00098875	FA	001000098875	00000602094	00000000200	00000012042	17	2017-11-15 11:40:32.00095
013	428	27163184732	20171023	00098876	FA	001000098876	00000106323	00000000200	00000002126	17	2017-11-15 11:40:32.00095
013	429	20305781720	20171023	00098877	FA	001000098877	00000503618	00000000200	00000010072	17	2017-11-15 11:40:32.00095
013	430	23289795464	20171023	00098878	FA	001000098878	00002255299	00000000200	00000045106	17	2017-11-15 11:40:32.00095
013	431	20274035170	20171023	00098880	FA	001000098880	00000102778	00000000200	00000002056	17	2017-11-15 11:40:32.00095
013	432	27316072483	20171023	00098881	FA	001000098881	00000129351	00000000200	00000002587	17	2017-11-15 11:40:32.00095
013	433	27056603560	20171023	00098882	FA	001000098882	00000270660	00000000200	00000005413	17	2017-11-15 11:40:32.00095
013	434	27173955451	20171023	00098879	FA	001000098879	00000089551	00000000200	00000001791	17	2017-11-15 11:40:32.00095
013	435	30670364603	20171023	00098883	FA	001000098883	00000147765	00000000200	00000002955	17	2017-11-15 11:40:32.00095
013	436	27232869564	20171023	00098884	FA	001000098884	00000720969	00000000200	00000014419	17	2017-11-15 11:40:32.00095
013	437	20073261806	20171023	00098885	FA	001000098885	00000649469	00000000200	00000012989	17	2017-11-15 11:40:32.00095
013	438	23289795464	20171023	00098886	FA	001000098886	00000985104	00000000200	00000019702	17	2017-11-15 11:40:32.00095
013	439	27367571123	20171023	00098887	FA	001000098887	00000136012	00000000200	00000002720	17	2017-11-15 11:40:32.00095
013	440	20054139285	20171023	00098888	FA	001000098888	00000186222	00000000200	00000003724	17	2017-11-15 11:40:32.00095
013	441	20104656944	20171023	00098889	FA	001000098889	00000194783	00000000200	00000003896	17	2017-11-15 11:40:32.00095
002	183	20160060183	20171129	00004879	FA	001100049500	00000048483	00000000200	00000000970	6	2017-12-07 11:13:01.502569
002	184	20160060183	20171129	00004880	FA	001100049507	00000028066	00000000200	00000000561	6	2017-12-07 11:13:01.502569
002	185	27101402156	20171103	00004881	FA	001100048234	00000222020	00000000200	00000004440	6	2017-12-07 11:13:01.502569
002	186	27101402156	20171103	00004882	FA	001100048249	00000210920	00000000200	00000004218	6	2017-12-07 11:13:01.502569
002	187	27101402156	20171103	00004883	FA	001100002372	-0000222021	00000000200	-0000004440	6	2017-12-07 11:13:01.502569
002	188	27101402156	20171108	00004884	FA	001100048454	00000076434	00000000200	00000001529	6	2017-12-07 11:13:01.502569
003	601	27188206463	20171128	00027100	FA	001500027100	00000026458	00000000200	00000000529	7	2017-12-05 18:44:23.476865
003	602	27056603560	20171128	00027101	FA	001500027101	00000195599	00000000200	00000003912	7	2017-12-05 18:44:23.476865
003	603	20266077719	20171128	00027102	FA	001500027102	00000128980	00000000200	00000002580	7	2017-12-05 18:44:23.476865
003	604	20173955740	20171128	00027103	FA	001500027103	00000035148	00000000200	00000000703	7	2017-12-05 18:44:23.476865
003	605	27280191235	20171128	00027104	FA	001500027104	00000325816	00000000200	00000006516	7	2017-12-05 18:44:23.476865
003	606	30670364603	20171128	00027105	FA	001500027105	00000105402	00000000200	00000002108	7	2017-12-05 18:44:23.476865
003	607	20274035170	20171128	00027106	FA	001500027106	00000025280	00000000200	00000000506	7	2017-12-05 18:44:23.476865
003	608	27952625514	20171128	00027107	FA	001500027107	00000670422	00000000200	00000013408	7	2017-12-05 18:44:23.476865
003	609	27222713140	20171128	00027108	FA	001500027108	00000084619	00000000200	00000001692	7	2017-12-05 18:44:23.476865
003	610	27351546986	20171128	00026635	FA	001500026635	00000147133	00000000200	00000002943	7	2017-12-05 18:44:23.476865
003	611	27266554740	20171128	00026636	FA	001500026636	00000102897	00000000200	00000002058	7	2017-12-05 18:44:23.476865
003	612	27219273431	20171128	00026638	FA	001500026638	00000103113	00000000200	00000002062	7	2017-12-05 18:44:23.476865
003	613	20225250473	20171128	00026639	FA	001500026639	00000060782	00000000200	00000001216	7	2017-12-05 18:44:23.476865
003	614	27346653502	20171128	00026640	FA	001500026640	00000334287	00000000200	00000006686	7	2017-12-05 18:44:23.476865
003	615	23141622099	20171128	00026641	FA	001500026641	00000192065	00000000200	00000003841	7	2017-12-05 18:44:23.476865
003	616	27394430655	20171128	00026642	FA	001500026642	00000079244	00000000200	00000001585	7	2017-12-05 18:44:23.476865
003	617	20173955449	20171128	00026645	FA	001500026645	00000050027	00000000200	00000001001	7	2017-12-05 18:44:23.476865
003	618	23241216349	20171128	00009796	FA	001300009796	00000337813	00000000200	00000006756	7	2017-12-05 18:44:23.476865
003	619	20274035170	20171129	00021017	FA	001700021017	00000028392	00000000200	00000000568	7	2017-12-05 18:44:23.476865
003	620	23289795464	20171129	00021018	FA	001700021018	00000030702	00000000200	00000000614	7	2017-12-05 18:44:23.476865
003	621	27297722927	20171129	00020856	FA	001700020856	00000050818	00000000200	00000001016	7	2017-12-05 18:44:23.476865
003	622	27163184465	20171129	00020857	FA	001700020857	00000043701	00000000200	00000000874	7	2017-12-05 18:44:23.476865
003	623	27952625514	20171129	00021023	FA	001700021023	00003216620	00000000200	00000064332	7	2017-12-05 18:44:23.476865
003	624	27367571123	20171129	00005676	FA	001800005676	00000030606	00000000200	00000000612	7	2017-12-05 18:44:23.476865
003	625	20261719887	20171129	00005677	FA	001800005677	00000064260	00000000200	00000001285	7	2017-12-05 18:44:23.476865
003	626	30670364603	20171129	00005678	FA	001800005678	00000097509	00000000200	00000001950	7	2017-12-05 18:44:23.476865
003	627	27952625514	20171129	00005679	FA	001800005679	00000133126	00000000200	00000002663	7	2017-12-05 18:44:23.476865
003	628	23289795464	20171129	00005680	FA	001800005680	00000021825	00000000200	00000000437	7	2017-12-05 18:44:23.476865
003	629	27394430655	20171129	00006840	FA	001800006840	00000026562	00000000200	00000000531	7	2017-12-05 18:44:23.476865
003	630	27297722927	20171129	00006841	FA	001800006841	00000074372	00000000200	00000001487	7	2017-12-05 18:44:23.476865
003	631	20274035170	20171129	00027180	FA	001500027180	00000048577	00000000200	00000000972	7	2017-12-05 18:44:23.476865
003	632	23289795464	20171129	00027181	FA	001500027181	00000053546	00000000200	00000001071	7	2017-12-05 18:44:23.476865
003	633	27222713140	20171129	00027182	FA	001500027182	00000032745	00000000200	00000000655	7	2017-12-05 18:44:23.476865
003	634	20118857020	20171129	00026691	FA	001500026691	00000016123	00000000200	00000000322	7	2017-12-05 18:44:23.476865
003	635	27394430655	20171129	00026692	FA	001500026692	00000005361	00000000200	00000000107	7	2017-12-05 18:44:23.476865
003	636	27297722927	20171129	00026693	FA	001500026693	00000068820	00000000200	00000001376	7	2017-12-05 18:44:23.476865
003	637	27163184465	20171129	00026694	FA	001500026694	00000192159	00000000200	00000003843	7	2017-12-05 18:44:23.476865
003	638	20118857020	20171129	00020859	FA	001700020859	00000056784	00000000200	00000001136	7	2017-12-05 18:44:23.476865
003	639	27325453465	20171129	00012015	FA	001300012015	00000039841	00000000200	00000000797	7	2017-12-05 18:44:23.476865
003	640	20225250473	20171129	00012025	FA	001300012025	00000013953	00000000200	00000000279	7	2017-12-05 18:44:23.476865
003	641	20054139285	20171129	00027202	FA	001500027202	00000554783	00000000200	00000011096	7	2017-12-05 18:44:23.476865
003	642	27290859560	20171129	00009811	FA	001300009811	00000154162	00000000200	00000003083	7	2017-12-05 18:44:23.476865
003	643	20259944814	20171129	00012042	FA	001300012042	00000283885	00000000200	00000005678	7	2017-12-05 18:44:23.476865
003	644	20259944814	20171129	00012043	FA	001300012043	00000050086	00000000200	00000001002	7	2017-12-05 18:44:23.476865
003	645	27219273431	20171129	00020875	FA	001700020875	00000007554	00000000200	00000000151	7	2017-12-05 18:44:23.476865
003	646	20101553419	20171130	00021040	FA	001700021040	00000062192	00000000200	00000001244	7	2017-12-05 18:44:23.476865
003	647	27952625514	20171130	00000584	FA	001100000584	00000578361	00000000200	00000011567	7	2017-12-05 18:44:23.476865
003	648	27952625514	20171130	00000585	FA	001100000585	00000585858	00000000200	00000011717	7	2017-12-05 18:44:23.476865
003	649	27215184760	20171130	00012053	FA	001300012053	00000026458	00000000200	00000000529	7	2017-12-05 18:44:23.476865
003	650	30604760018	20171130	00021054	FA	001700021054	00000832619	00000000200	00000016652	7	2017-12-05 18:44:23.476865
003	651	30604760018	20171130	00021055	FA	001700021055	00000258940	00000000200	00000005179	7	2017-12-05 18:44:23.476865
003	652	30708978562	20171130	00009830	FA	001300009830	00000093077	00000000200	00000001862	7	2017-12-05 18:44:23.476865
003	653	27952625514	20171111	00000968	FA	001100000968	00000022865	00000000200	00000000457	7	2017-12-05 18:44:23.476865
002	189	27101402156	20171110	00004885	FA	001100048592	00000105572	00000000200	00000002111	6	2017-12-07 11:13:01.502569
002	190	27101402156	20171115	00004886	FA	001100048770	00000082306	00000000200	00000001646	6	2017-12-07 11:13:01.502569
002	191	27101402156	20171117	00004887	FA	001100048957	00000038814	00000000200	00000000776	6	2017-12-07 11:13:01.502569
002	192	27101402156	20171122	00004888	FA	001100049159	00000174147	00000000200	00000003483	6	2017-12-07 11:13:01.502569
002	193	27101402156	20171124	00004889	FA	001100049314	00000046897	00000000200	00000000938	6	2017-12-07 11:13:01.502569
002	194	27101402156	20171124	00004890	FA	001100002415	-0000040095	00000000200	-0000000802	6	2017-12-07 11:13:01.502569
002	195	27101402156	20171128	00004891	FA	001100049490	00000094355	00000000200	00000001887	6	2017-12-07 11:13:01.502569
002	196	27101402156	20171128	00004892	FA	001100049497	00000022261	00000000200	00000000445	6	2017-12-07 11:13:01.502569
002	197	27101402156	20171129	00004893	FA	001100049505	00000007640	00000000200	00000000153	6	2017-12-07 11:13:01.502569
002	198	27297722927	20171101	00004894	FA	001100048116	00000084666	00000000200	00000001693	6	2017-12-07 11:13:01.502569
002	199	27297722927	20171115	00004895	FA	001100048792	00000033687	00000000200	00000000674	6	2017-12-07 11:13:01.502569
002	200	27297722927	20171115	00004896	FA	001100048809	00000094470	00000000200	00000001889	6	2017-12-07 11:13:01.502569
002	201	27297722927	20171117	00004897	FA	001100048965	00000068222	00000000200	00000001364	6	2017-12-07 11:13:01.502569
002	202	27297722927	20171124	00004898	FA	001100049334	00000088719	00000000200	00000001774	6	2017-12-07 11:13:01.502569
002	203	27297722927	20171124	00004899	FA	001100049336	00000031871	00000000200	00000000637	6	2017-12-07 11:13:01.502569
002	204	27297722927	20171124	00004900	FA	001100049337	00000007759	00000000200	00000000155	6	2017-12-07 11:13:01.502569
002	205	20225250473	20171108	00004901	FA	001100048452	00000039149	00000000200	00000000783	6	2017-12-07 11:13:01.502569
002	206	20225250473	20171115	00004902	FA	001100048818	00000047491	00000000200	00000000950	6	2017-12-07 11:13:01.502569
002	207	20225250473	20171122	00004903	FA	001100049155	00000038681	00000000200	00000000774	6	2017-12-07 11:13:01.502569
002	208	20225250473	20171129	00004904	FA	001100049523	00000050383	00000000200	00000001008	6	2017-12-07 11:13:01.502569
002	209	27921235521	20171101	00004905	FA	001100048118	00000056779	00000000200	00000001136	6	2017-12-07 11:13:01.502569
002	210	27921235521	20171103	00004906	FA	001100048228	00000023921	00000000200	00000000478	6	2017-12-07 11:13:01.502569
002	211	27921235521	20171103	00004907	FA	001100048245	00000076309	00000000200	00000001526	6	2017-12-07 11:13:01.502569
002	212	27921235521	20171110	00004908	FA	001100048598	00000014221	00000000200	00000000284	6	2017-12-07 11:13:01.502569
002	213	27921235521	20171115	00004909	FA	001100048790	00000022346	00000000200	00000000447	6	2017-12-07 11:13:01.502569
002	214	27921235521	20171117	00004910	FA	001100048955	00000157065	00000000200	00000003141	6	2017-12-07 11:13:01.502569
002	215	27921235521	20171122	00004911	FA	001100049151	00000174330	00000000200	00000003487	6	2017-12-07 11:13:01.502569
002	216	27921235521	20171124	00004912	FA	001100049323	00000118504	00000000200	00000002370	6	2017-12-07 11:13:01.502569
002	217	27921235521	20171124	00004913	FA	001100002416	-0000077419	00000000200	-0000001548	6	2017-12-07 11:13:01.502569
002	218	27044920102	20171101	00004914	FA	001100048111	00000053683	00000000200	00000001074	6	2017-12-07 11:13:01.502569
002	219	27044920102	20171101	00004915	FA	001100048112	00000034720	00000000200	00000000694	6	2017-12-07 11:13:01.502569
002	220	27044920102	20171108	00004916	FA	001100048442	00000042735	00000000200	00000000855	6	2017-12-07 11:13:01.502569
002	221	27044920102	20171110	00004917	FA	001100048597	00000071166	00000000200	00000001423	6	2017-12-07 11:13:01.502569
002	222	27044920102	20171115	00004918	FA	001100048769	00000121827	00000000200	00000002437	6	2017-12-07 11:13:01.502569
002	223	27044920102	20171117	00004919	FA	001100048967	00000040142	00000000200	00000000803	6	2017-12-07 11:13:01.502569
002	224	27044920102	20171122	00004920	FA	001100049135	00000133706	00000000200	00000002674	6	2017-12-07 11:13:01.502569
002	225	27044920102	20171124	00004921	FA	001100049338	00000033791	00000000200	00000000676	6	2017-12-07 11:13:01.502569
002	226	27044920102	20171129	00004922	FA	001100049503	00000072998	00000000200	00000001460	6	2017-12-07 11:13:01.502569
002	227	27259753754	20171108	00004923	FA	001100048465	00000335870	00000000200	00000006717	6	2017-12-07 11:13:01.502569
002	228	27259753754	20171108	00004924	FA	001100048467	00000234661	00000000200	00000004693	6	2017-12-07 11:13:01.502569
002	229	23241216349	20171101	00004925	FA	001100023206	00000164340	00000000200	00000003287	6	2017-12-07 11:13:01.502569
002	230	23241216349	20171117	00004926	FA	001100023782	00000089000	00000000200	00000001780	6	2017-12-07 11:13:01.502569
002	231	23241216349	20171124	00004927	FA	001100024002	00000119790	00000000200	00000002396	6	2017-12-07 11:13:01.502569
002	232	23241216349	20171124	00004928	FA	001100001280	-0000119790	00000000200	-0000002396	6	2017-12-07 11:13:01.502569
002	233	23241216349	20171129	00004929	FA	001100024107	00000128957	00000000200	00000002579	6	2017-12-07 11:13:01.502569
002	234	20346653184	20171103	00004930	FA	001100048231	00000084248	00000000200	00000001685	6	2017-12-07 11:13:01.502569
002	235	20346653184	20171115	00004931	FA	001100048794	00000082861	00000000200	00000001657	6	2017-12-07 11:13:01.502569
002	236	20346653184	20171121	00004932	FA	001100049083	00000078571	00000000200	00000001571	6	2017-12-07 11:13:01.502569
002	237	20261719887	20171108	00004933	FA	001100023433	00000158451	00000000200	00000003169	6	2017-12-07 11:13:01.502569
002	238	20261719887	20171115	00004934	FA	001100023642	00000108937	00000000200	00000002179	6	2017-12-07 11:13:01.502569
002	239	20261719887	20171115	00004935	FA	001100023643	00000030240	00000000200	00000000605	6	2017-12-07 11:13:01.502569
002	240	20261719887	20171117	00004936	FA	001100023771	00000177542	00000000200	00000003551	6	2017-12-07 11:13:01.502569
002	241	20261719887	20171117	00004937	FA	001100023772	00000181719	00000000200	00000003634	6	2017-12-07 11:13:01.502569
002	242	20261719887	20171117	00004938	FA	001100023773	00000102680	00000000200	00000002054	6	2017-12-07 11:13:01.502569
002	243	20261719887	20171118	00004939	FA	001100001267	-0000031354	00000000200	-0000000627	6	2017-12-07 11:13:01.502569
002	244	20261719887	20171122	00004940	FA	001100023880	00000142950	00000000200	00000002859	6	2017-12-07 11:13:01.502569
002	245	20261719887	20171122	00004941	FA	001100023881	00000030240	00000000200	00000000605	6	2017-12-07 11:13:01.502569
002	246	20261719887	20171129	00004942	FA	001100024103	00000187148	00000000200	00000003743	6	2017-12-07 11:13:01.502569
002	247	20261719887	20171129	00004943	FA	001100024104	00000142110	00000000200	00000002842	6	2017-12-07 11:13:01.502569
002	248	20305781720	20171101	00004944	FA	001100023221	00000285451	00000000200	00000005709	6	2017-12-07 11:13:01.502569
002	249	20305781720	20171101	00004945	FA	001100023222	00000197448	00000000200	00000003949	6	2017-12-07 11:13:01.502569
002	250	20305781720	20171101	00004946	FA	001100023224	00000170203	00000000200	00000003404	6	2017-12-07 11:13:01.502569
002	251	20305781720	20171108	00004947	FA	001100023447	00000357876	00000000200	00000007158	6	2017-12-07 11:13:01.502569
002	252	20305781720	20171108	00004948	FA	001100023448	00000339805	00000000200	00000006796	6	2017-12-07 11:13:01.502569
002	253	20305781720	20171108	00004949	FA	001100001244	-0000027158	00000000200	-0000000543	6	2017-12-07 11:13:01.502569
002	254	20305781720	20171115	00004950	FA	001100023666	00000411843	00000000200	00000008237	6	2017-12-07 11:13:01.502569
002	255	20305781720	20171115	00004951	FA	001100023667	00000243117	00000000200	00000004862	6	2017-12-07 11:13:01.502569
002	256	20305781720	20171115	00004952	FA	001100023670	00000289721	00000000200	00000005794	6	2017-12-07 11:13:01.502569
002	257	20305781720	20171115	00004953	FA	001100023671	00000025878	00000000200	00000000518	6	2017-12-07 11:13:01.502569
002	258	20305781720	20171115	00004954	FA	001100023672	00000211976	00000000200	00000004240	6	2017-12-07 11:13:01.502569
002	259	20305781720	20171122	00004955	FA	001100023909	00000439559	00000000200	00000008791	6	2017-12-07 11:13:01.502569
002	260	20305781720	20171122	00004956	FA	001100023910	00000238196	00000000200	00000004764	6	2017-12-07 11:13:01.502569
002	261	20305781720	20171122	00004957	FA	001100023911	00000182998	00000000200	00000003660	6	2017-12-07 11:13:01.502569
002	262	20305781720	20171122	00004958	FA	001100023913	00000077748	00000000200	00000001555	6	2017-12-07 11:13:01.502569
002	263	20305781720	20171122	00004959	FA	001100023914	00000160000	00000000200	00000003200	6	2017-12-07 11:13:01.502569
002	264	20305781720	20171122	00004960	FA	001100023927	00000038801	00000000200	00000000776	6	2017-12-07 11:13:01.502569
002	265	20305781720	20171124	00004961	FA	001100024007	00000087600	00000000200	00000001752	6	2017-12-07 11:13:01.502569
002	266	20305781720	20171129	00004962	FA	001100024120	00000474916	00000000200	00000009498	6	2017-12-07 11:13:01.502569
002	267	20305781720	20171129	00004963	FA	001100024121	00000338101	00000000200	00000006762	6	2017-12-07 11:13:01.502569
002	268	20305781720	20171129	00004964	FA	001100024122	00000163466	00000000200	00000003269	6	2017-12-07 11:13:01.502569
002	269	27237091987	20171103	00004965	FA	001100048232	00000047054	00000000200	00000000941	6	2017-12-07 11:13:01.502569
002	270	27237091987	20171107	00004966	FA	001100048403	00000129993	00000000200	00000002600	6	2017-12-07 11:13:01.502569
002	271	27237091987	20171117	00004967	FA	001100048953	00000053646	00000000200	00000001073	6	2017-12-07 11:13:01.502569
102	2	23331976849	20171102	00653430	OT	000000824423	00002513971	00000000200	00000050279	52	2017-12-11 09:09:02.82463
102	3	27274039367	20171102	00653439	OT	000000824432	00002711368	00000000200	00000054227	52	2017-12-11 09:09:02.82463
102	4	20316163360	20171102	00653585	OT	000000824468	00007902466	00000000200	00000158049	52	2017-12-11 09:09:02.82463
102	5	20249160688	20171109	00656889	OT	000000824695	00001077800	00000000200	00000021556	52	2017-12-11 09:09:02.82463
102	6	27246106717	20171109	00656848	OT	000000824654	00001487639	00000000200	00000029753	52	2017-12-11 09:09:02.82463
102	7	20921932651	20171110	00658912	OT	000000824950	00000509566	00000000200	00000010191	52	2017-12-11 09:09:02.82463
102	8	20921932651	20171110	00658913	OT	000000824951	00001231852	00000000200	00000024637	52	2017-12-11 09:09:02.82463
102	9	20254097498	20171110	00658942	OT	000000824980	00000801700	00000000200	00000016034	52	2017-12-11 09:09:02.82463
102	10	20254097498	20171110	00658943	OT	000000824981	00001343814	00000000200	00000026876	52	2017-12-11 09:09:02.82463
102	11	27235674195	20171110	00660264	OT	000000826301	00001032640	00000000200	00000020653	52	2017-12-11 09:09:02.82463
102	12	20063876594	20171110	00658723	OT	000000824762	00003395876	00000000200	00000067918	52	2017-12-11 09:09:02.82463
102	13	20298788692	20171110	00658983	OT	000000825021	00001255363	00000000200	00000025107	52	2017-12-11 09:09:02.82463
102	14	20298788692	20171110	00658984	OT	000000825022	00001978888	00000000200	00000039578	52	2017-12-11 09:09:02.82463
102	15	27247623448	20171110	00658987	OT	000000825025	00000404023	00000000200	00000008080	52	2017-12-11 09:09:02.82463
102	16	27247623448	20171110	00658988	OT	000000825026	00000571430	00000000200	00000011429	52	2017-12-11 09:09:02.82463
102	17	20139085605	20171110	00660379	OT	000000826416	00002397776	00000000200	00000047956	52	2017-12-11 09:09:02.82463
102	18	20139085605	20171110	00660380	OT	000000826417	00000155470	00000000200	00000003109	52	2017-12-11 09:09:02.82463
102	19	27313810343	20171110	00659401	OT	000000825438	00000307555	00000000200	00000006151	52	2017-12-11 09:09:02.82463
102	20	20244366814	20171110	00659002	OT	000000825040	00000188922	00000000200	00000003778	52	2017-12-11 09:09:02.82463
102	21	20244366814	20171110	00659003	OT	000000825041	00001266744	00000000200	00000025335	52	2017-12-11 09:09:02.82463
102	22	20244366814	20171110	00659004	OT	000000825042	00001066096	00000000200	00000021322	52	2017-12-11 09:09:02.82463
102	23	20244366814	20171110	00659005	OT	000000825043	00001997600	00000000200	00000039952	52	2017-12-11 09:09:02.82463
102	24	20226732277	20171110	00660414	OT	000000826451	00001544094	00000000200	00000030882	52	2017-12-11 09:09:02.82463
102	25	20226732277	20171110	00660415	OT	000000826452	00001704040	00000000200	00000034081	52	2017-12-11 09:09:02.82463
102	26	20308764665	20171110	00660419	OT	000000826456	00000402912	00000000200	00000008058	52	2017-12-11 09:09:02.82463
102	27	27216865583	20171110	00660433	OT	000000826470	00000312800	00000000200	00000006256	52	2017-12-11 09:09:02.82463
102	28	23176736224	20171110	00660472	OT	000000826509	00000612720	00000000200	00000012254	52	2017-12-11 09:09:02.82463
102	29	27284002224	20171110	00660541	OT	000000826578	00000962000	00000000200	00000019240	52	2017-12-11 09:09:02.82463
102	30	27231146925	20171110	00659522	OT	000000825559	00000806608	00000000200	00000016132	52	2017-12-11 09:09:02.82463
102	31	23263723104	20171110	00660588	OT	000000826625	00000825360	00000000200	00000016507	52	2017-12-11 09:09:02.82463
102	32	20063876594	20171110	00660613	OT	000000826650	00000513067	00000000200	00000010261	52	2017-12-11 09:09:02.82463
102	33	27169129423	20171110	00660632	OT	000000826669	00000202720	00000000200	00000004054	52	2017-12-11 09:09:02.82463
102	34	27302489039	20171110	00659608	OT	000000825645	00000334705	00000000200	00000006694	52	2017-12-11 09:09:02.82463
102	35	20278318843	20171110	00660700	OT	000000826737	00000678240	00000000200	00000013565	52	2017-12-11 09:09:02.82463
102	36	20278318843	20171110	00660701	OT	000000826738	00002063600	00000000200	00000041272	52	2017-12-11 09:09:02.82463
102	37	27245847675	20171110	00660731	OT	000000826768	00000586440	00000000200	00000011729	52	2017-12-11 09:09:02.82463
102	38	20214681316	20171110	00659173	OT	000000825211	00001196161	00000000200	00000023923	52	2017-12-11 09:09:02.82463
102	39	20214681316	20171110	00659174	OT	000000825212	00001962647	00000000200	00000039253	52	2017-12-11 09:09:02.82463
102	40	20243253552	20171110	00660788	OT	000000826825	00001357040	00000000200	00000027141	52	2017-12-11 09:09:02.82463
102	41	23125832814	20171110	00659682	OT	000000825719	00001302796	00000000200	00000026056	52	2017-12-11 09:09:02.82463
102	42	27249795033	20171110	00660821	OT	000000826858	00001931160	00000000200	00000038623	52	2017-12-11 09:09:02.82463
102	43	27314916714	20171110	00659704	OT	000000825741	00000080104	00000000200	00000001602	52	2017-12-11 09:09:02.82463
102	44	27314916714	20171110	00659705	OT	000000825742	00000544750	00000000200	00000010895	52	2017-12-11 09:09:02.82463
102	45	27323784685	20171110	00660882	OT	000000826919	00001614912	00000000200	00000032298	52	2017-12-11 09:09:02.82463
102	46	20183437489	20171110	00660895	OT	000000826932	00004779200	00000000200	00000095584	52	2017-12-11 09:09:02.82463
102	47	20248868997	20171110	00660936	OT	000000826973	00001198656	00000000200	00000023973	52	2017-12-11 09:09:02.82463
102	48	20248868997	20171110	00660937	OT	000000826974	00000221600	00000000200	00000004432	52	2017-12-11 09:09:02.82463
102	49	23231149279	20171110	00659759	OT	000000825796	00000748420	00000000200	00000014968	52	2017-12-11 09:09:02.82463
102	50	23231149279	20171110	00659760	OT	000000825797	00000209440	00000000200	00000004189	52	2017-12-11 09:09:02.82463
102	51	20245845503	20171110	00660955	OT	000000826992	00001103520	00000000200	00000022070	52	2017-12-11 09:09:02.82463
102	52	20245845503	20171110	00660956	OT	000000826993	00005191120	00000000200	00000103822	52	2017-12-11 09:09:02.82463
102	53	27337740710	20171114	00663347	OT	000000827116	00000969600	00000000200	00000019392	52	2017-12-11 09:09:02.82463
102	54	27337740710	20171114	00663348	OT	000000827117	00000274720	00000000200	00000005494	52	2017-12-11 09:09:02.82463
102	55	27928326565	20171116	00663852	OT	000000827192	00000827500	00000000200	00000016550	52	2017-12-11 09:09:02.82463
102	56	27337740710	20171117	00668943	OT	000000827902	00000727200	00000000200	00000014544	52	2017-12-11 09:09:02.82463
102	57	27337740710	20171117	00668944	OT	000000827903	00000206040	00000000200	00000004121	52	2017-12-11 09:09:02.82463
102	58	20921932651	20171117	00668513	OT	000000827472	00000382174	00000000200	00000007643	52	2017-12-11 09:09:02.82463
102	59	20921932651	20171117	00668514	OT	000000827473	00000923889	00000000200	00000018478	52	2017-12-11 09:09:02.82463
102	60	20254097498	20171117	00668544	OT	000000827503	00000601275	00000000200	00000012025	52	2017-12-11 09:09:02.82463
102	61	20254097498	20171117	00668545	OT	000000827504	00001007860	00000000200	00000020157	52	2017-12-11 09:09:02.82463
102	62	27235674195	20171117	00670001	OT	000000828960	00000774480	00000000200	00000015490	52	2017-12-11 09:09:02.82463
102	63	20063876594	20171117	00670798	OT	000000829705	00002546907	00000000200	00000050938	52	2017-12-11 09:09:02.82463
102	64	20298788692	20171117	00668590	OT	000000827549	00000941523	00000000200	00000018830	52	2017-12-11 09:09:02.82463
102	65	20298788692	20171117	00668591	OT	000000827550	00001484167	00000000200	00000029683	52	2017-12-11 09:09:02.82463
102	66	27247623448	20171117	00668594	OT	000000827553	00000303018	00000000200	00000006060	52	2017-12-11 09:09:02.82463
102	67	27247623448	20171117	00668595	OT	000000827554	00000428572	00000000200	00000008571	52	2017-12-11 09:09:02.82463
102	68	20139085605	20171117	00670116	OT	000000829075	00001798332	00000000200	00000035967	52	2017-12-11 09:09:02.82463
102	69	20139085605	20171117	00670117	OT	000000829076	00000116603	00000000200	00000002332	52	2017-12-11 09:09:02.82463
102	70	27313810343	20171117	00669050	OT	000000828009	00000230666	00000000200	00000004613	52	2017-12-11 09:09:02.82463
102	71	20244366814	20171117	00668611	OT	000000827570	00000141692	00000000200	00000002834	52	2017-12-11 09:09:02.82463
102	72	20244366814	20171117	00668612	OT	000000827571	00000950058	00000000200	00000019001	52	2017-12-11 09:09:02.82463
102	73	20244366814	20171117	00668613	OT	000000827572	00000799572	00000000200	00000015991	52	2017-12-11 09:09:02.82463
102	74	20244366814	20171117	00668614	OT	000000827573	00001498150	00000000200	00000029963	52	2017-12-11 09:09:02.82463
102	75	20226732277	20171117	00670152	OT	000000829111	00001158071	00000000200	00000023161	52	2017-12-11 09:09:02.82463
102	76	20226732277	20171117	00670153	OT	000000829112	00001278030	00000000200	00000025561	52	2017-12-11 09:09:02.82463
102	77	20308764665	20171117	00670157	OT	000000829116	00000302184	00000000200	00000006044	52	2017-12-11 09:09:02.82463
102	78	27216865583	20171117	00670171	OT	000000829130	00000234600	00000000200	00000004692	52	2017-12-11 09:09:02.82463
102	79	23176736224	20171117	00670211	OT	000000829170	00000459540	00000000200	00000009191	52	2017-12-11 09:09:02.82463
102	80	27284002224	20171117	00670277	OT	000000829236	00000721500	00000000200	00000014430	52	2017-12-11 09:09:02.82463
102	81	27231146925	20171117	00669163	OT	000000828122	00000604956	00000000200	00000012099	52	2017-12-11 09:09:02.82463
102	82	23263723104	20171117	00670322	OT	000000829281	00000619020	00000000200	00000012380	52	2017-12-11 09:09:02.82463
102	83	20063876594	20171117	00670346	OT	000000829305	00000384801	00000000200	00000007696	52	2017-12-11 09:09:02.82463
102	84	27302489039	20171117	00669256	OT	000000828215	00000251029	00000000200	00000005021	52	2017-12-11 09:09:02.82463
102	85	20278318843	20171117	00670431	OT	000000829390	00000508680	00000000200	00000010174	52	2017-12-11 09:09:02.82463
102	86	20278318843	20171117	00670432	OT	000000829391	00001547700	00000000200	00000030954	52	2017-12-11 09:09:02.82463
102	87	27245847675	20171117	00670462	OT	000000829421	00000439830	00000000200	00000008797	52	2017-12-11 09:09:02.82463
102	88	20214681316	20171117	00668792	OT	000000827751	00000897121	00000000200	00000017942	52	2017-12-11 09:09:02.82463
102	89	20214681316	20171117	00668793	OT	000000827752	00001471985	00000000200	00000029440	52	2017-12-11 09:09:02.82463
102	90	20243253552	20171117	00670519	OT	000000829478	00001017780	00000000200	00000020356	52	2017-12-11 09:09:02.82463
102	91	23125832814	20171117	00669334	OT	000000828293	00000977097	00000000200	00000019542	52	2017-12-11 09:09:02.82463
102	92	27249795033	20171117	00670551	OT	000000829510	00001448370	00000000200	00000028967	52	2017-12-11 09:09:02.82463
102	93	27314916714	20171117	00669355	OT	000000828314	00000060078	00000000200	00000001202	52	2017-12-11 09:09:02.82463
102	94	27314916714	20171117	00669356	OT	000000828315	00000408540	00000000200	00000008171	52	2017-12-11 09:09:02.82463
102	95	27323784685	20171117	00670610	OT	000000829569	00001211184	00000000200	00000024224	52	2017-12-11 09:09:02.82463
102	96	20183437489	20171117	00670623	OT	000000829582	00003584400	00000000200	00000071688	52	2017-12-11 09:09:02.82463
102	97	20248868997	20171117	00670662	OT	000000829621	00000898992	00000000200	00000017980	52	2017-12-11 09:09:02.82463
102	98	20248868997	20171117	00670663	OT	000000829622	00000166200	00000000200	00000003324	52	2017-12-11 09:09:02.82463
102	99	23231149279	20171117	00669410	OT	000000828369	00000561315	00000000200	00000011226	52	2017-12-11 09:09:02.82463
102	100	23231149279	20171117	00669411	OT	000000828370	00000157080	00000000200	00000003142	52	2017-12-11 09:09:02.82463
102	101	20245845503	20171117	00670683	OT	000000829642	00000827640	00000000200	00000016553	52	2017-12-11 09:09:02.82463
102	102	20245845503	20171117	00670684	OT	000000829643	00003893340	00000000200	00000077867	52	2017-12-11 09:09:02.82463
102	103	27337740710	20171124	00675803	OT	000000830140	00000727200	00000000200	00000014544	52	2017-12-11 09:09:02.82463
102	104	27337740710	20171124	00675804	OT	000000830141	00000206040	00000000200	00000004121	52	2017-12-11 09:09:02.82463
102	105	20921932651	20171124	00680518	OT	000000834193	00000382174	00000000200	00000007643	52	2017-12-11 09:09:02.82463
102	106	20921932651	20171124	00680519	OT	000000834194	00000923888	00000000200	00000018478	52	2017-12-11 09:09:02.82463
102	107	20254097498	20171124	00680551	OT	000000834226	00000601275	00000000200	00000012025	52	2017-12-11 09:09:02.82463
102	108	20254097498	20171124	00680552	OT	000000834227	00001007860	00000000200	00000020157	52	2017-12-11 09:09:02.82463
102	109	27235674195	20171124	00678208	OT	000000832313	00000774480	00000000200	00000015490	52	2017-12-11 09:09:02.82463
102	110	20063876594	20171124	00675704	OT	000000830041	00002546907	00000000200	00000050938	52	2017-12-11 09:09:02.82463
102	111	20298788692	20171124	00680597	OT	000000834272	00000941522	00000000200	00000018830	52	2017-12-11 09:09:02.82463
102	112	20298788692	20171124	00680598	OT	000000834273	00001484166	00000000200	00000029683	52	2017-12-11 09:09:02.82463
102	113	27247623448	20171124	00680603	OT	000000834278	00000303017	00000000200	00000006060	52	2017-12-11 09:09:02.82463
102	114	27247623448	20171124	00680604	OT	000000834279	00000428572	00000000200	00000008571	52	2017-12-11 09:09:02.82463
102	115	20113547155	20171124	00680920	OT	000000834594	00003484000	00000000200	00000069680	52	2017-12-11 09:09:02.82463
102	116	20139085605	20171124	00678326	OT	000000832431	00001798332	00000000200	00000035967	52	2017-12-11 09:09:02.82463
102	117	20139085605	20171124	00678327	OT	000000832432	00000116603	00000000200	00000002332	52	2017-12-11 09:09:02.82463
102	118	27313810343	20171124	00675914	OT	000000830251	00000230667	00000000200	00000004613	52	2017-12-11 09:09:02.82463
102	119	20244366814	20171124	00680620	OT	000000834295	00000141692	00000000200	00000002834	52	2017-12-11 09:09:02.82463
102	120	20244366814	20171124	00680621	OT	000000834296	00000950057	00000000200	00000019001	52	2017-12-11 09:09:02.82463
102	121	20244366814	20171124	00680622	OT	000000834297	00000799572	00000000200	00000015991	52	2017-12-11 09:09:02.82463
102	122	20244366814	20171124	00680623	OT	000000834298	00001498150	00000000200	00000029963	52	2017-12-11 09:09:02.82463
102	123	20226732277	20171124	00678363	OT	000000832468	00001158071	00000000200	00000023161	52	2017-12-11 09:09:02.82463
102	124	20226732277	20171124	00678364	OT	000000832469	00001278030	00000000200	00000025561	52	2017-12-11 09:09:02.82463
102	125	20308764665	20171124	00678368	OT	000000832473	00000302184	00000000200	00000006044	52	2017-12-11 09:09:02.82463
102	126	27216865583	20171124	00678382	OT	000000832487	00000234600	00000000200	00000004692	52	2017-12-11 09:09:02.82463
102	127	23176736224	20171124	00678422	OT	000000832527	00000459540	00000000200	00000009191	52	2017-12-11 09:09:02.82463
102	128	27284002224	20171124	00678492	OT	000000832597	00000721500	00000000200	00000014430	52	2017-12-11 09:09:02.82463
102	129	27231146925	20171124	00676029	OT	000000830366	00000604957	00000000200	00000012099	52	2017-12-11 09:09:02.82463
102	130	23263723104	20171124	00678539	OT	000000832644	00000619020	00000000200	00000012380	52	2017-12-11 09:09:02.82463
102	131	20063876594	20171124	00678564	OT	000000832669	00000384800	00000000200	00000007696	52	2017-12-11 09:09:02.82463
102	132	27169129423	20171124	00678583	OT	000000832688	00000304080	00000000200	00000006082	52	2017-12-11 09:09:02.82463
102	133	27302489039	20171124	00676123	OT	000000830460	00000251029	00000000200	00000005021	52	2017-12-11 09:09:02.82463
102	134	20278318843	20171124	00678652	OT	000000832757	00000508680	00000000200	00000010174	52	2017-12-11 09:09:02.82463
102	135	20278318843	20171124	00678653	OT	000000832758	00001547700	00000000200	00000030954	52	2017-12-11 09:09:02.82463
102	136	27245847675	20171124	00678683	OT	000000832788	00000439830	00000000200	00000008797	52	2017-12-11 09:09:02.82463
102	137	20214681316	20171124	00680805	OT	000000834480	00000897121	00000000200	00000017942	52	2017-12-11 09:09:02.82463
102	138	20214681316	20171124	00680806	OT	000000834481	00001471986	00000000200	00000029440	52	2017-12-11 09:09:02.82463
102	139	20243253552	20171124	00678741	OT	000000832846	00001017780	00000000200	00000020356	52	2017-12-11 09:09:02.82463
102	140	23125832814	20171124	00676201	OT	000000830538	00000977097	00000000200	00000019542	52	2017-12-11 09:09:02.82463
102	141	27249795033	20171124	00678777	OT	000000832882	00001448370	00000000200	00000028967	52	2017-12-11 09:09:02.82463
102	142	27314916714	20171124	00676222	OT	000000830559	00000060078	00000000200	00000001202	52	2017-12-11 09:09:02.82463
102	143	27314916714	20171124	00676223	OT	000000830560	00000408540	00000000200	00000008171	52	2017-12-11 09:09:02.82463
102	144	27323784685	20171124	00678837	OT	000000832942	00001211184	00000000200	00000024224	52	2017-12-11 09:09:02.82463
102	145	20183437489	20171124	00678850	OT	000000832955	00003584400	00000000200	00000071688	52	2017-12-11 09:09:02.82463
102	146	20248868997	20171124	00678889	OT	000000832994	00000898993	00000000200	00000017980	52	2017-12-11 09:09:02.82463
102	147	20248868997	20171124	00678890	OT	000000832995	00000166200	00000000200	00000003324	52	2017-12-11 09:09:02.82463
102	148	23231149279	20171124	00676277	OT	000000830614	00000561315	00000000200	00000011226	52	2017-12-11 09:09:02.82463
102	149	23231149279	20171124	00676278	OT	000000830615	00000157080	00000000200	00000003142	52	2017-12-11 09:09:02.82463
102	150	20245845503	20171124	00678910	OT	000000833015	00000827640	00000000200	00000016553	52	2017-12-11 09:09:02.82463
102	151	20245845503	20171124	00678911	OT	000000833016	00003893340	00000000200	00000077867	52	2017-12-11 09:09:02.82463
102	152	27266077632	20171128	00681786	OT	000000834728	00002400594	00000000200	00000048012	52	2017-12-11 09:09:02.82463
102	153	27266077632	20171128	00681608	OT	000000834668	00001053666	00000000200	00000021073	52	2017-12-11 09:09:02.82463
102	154	27209923071	20171128	00681801	OT	000000834743	00004645845	00000000200	00000092917	52	2017-12-11 09:09:02.82463
005	2	27112378257	20171102	00000001	FA	000100154294	00000052345	00000000200	00000001047	9	2017-12-11 11:34:22.402922
005	3	20166734178	20171103	00000001	FA	000100057561	00000050900	00000000200	00000001018	9	2017-12-11 11:34:22.402922
005	4	20173955740	20171103	00000001	FA	000100057562	00000064463	00000000200	00000001289	9	2017-12-11 11:34:22.402922
005	5	27316072483	20171103	00000001	FA	000100057563	00000154929	00000000200	00000003099	9	2017-12-11 11:34:22.402922
005	6	27367571123	20171103	00000001	FA	000100057564	00000215828	00000000200	00000004317	9	2017-12-11 11:34:22.402922
005	7	20274035170	20171103	00000001	FA	000100057565	00000107723	00000000200	00000002154	9	2017-12-11 11:34:22.402922
005	8	27222713140	20171103	00000001	FA	000100057566	00000036013	00000000200	00000000720	9	2017-12-11 11:34:22.402922
005	9	23289795464	20171103	00000001	FA	000100057567	00000805785	00000000200	00000016116	9	2017-12-11 11:34:22.402922
005	10	27368604483	20171103	00000001	FA	000100057568	00000043260	00000000200	00000000865	9	2017-12-11 11:34:22.402922
005	11	20118857020	20171103	00000001	FA	000100154358	00000053594	00000000200	00000001072	9	2017-12-11 11:34:22.402922
005	12	20118857020	20171103	00000001	FA	000100057578	00000060805	00000000200	00000001216	9	2017-12-11 11:34:22.402922
005	13	27064350809	20171103	00000001	FA	000100057579	00000076860	00000000200	00000001537	9	2017-12-11 11:34:22.402922
005	14	27305781342	20171103	00000001	FA	000100154384	00000038429	00000000200	00000000769	9	2017-12-11 11:34:22.402922
005	15	20113547198	20171103	00000001	FA	000100057580	00001939137	00000000200	00000038783	9	2017-12-11 11:34:22.402922
005	16	20113547198	20171103	00000001	FA	000100057581	00001815170	00000000200	00000036303	9	2017-12-11 11:34:22.402922
005	17	20149833510	20171103	00000001	FA	000100154339	00000035400	00000000200	00000000708	9	2017-12-11 11:34:22.402922
005	18	27248176607	20171103	00000001	FA	000100154340	00000105571	00000000200	00000002111	9	2017-12-11 11:34:22.402922
005	19	20182383016	20171103	00000001	FA	000100154341	00000063413	00000000200	00000001268	9	2017-12-11 11:34:22.402922
005	20	20273644912	20171103	00000001	FA	000100154342	00000014419	00000000200	00000000288	9	2017-12-11 11:34:22.402922
005	21	27297722927	20171103	00000001	FA	000100154343	00000228375	00000000200	00000004568	9	2017-12-11 11:34:22.402922
005	22	27297722927	20171103	00000001	FA	000100154344	00000053712	00000000200	00000001074	9	2017-12-11 11:34:22.402922
005	23	27346653782	20171103	00000001	FA	000100154345	00000086052	00000000200	00000001721	9	2017-12-11 11:34:22.402922
005	24	30714924644	20171103	00000001	FA	000100154346	00000064462	00000000200	00000001289	9	2017-12-11 11:34:22.402922
005	25	27346653502	20171103	00000001	FA	000100154347	00000130357	00000000200	00000002607	9	2017-12-11 11:34:22.402922
005	26	27266554740	20171103	00000001	FA	000100154349	00000147804	00000000200	00000002956	9	2017-12-11 11:34:22.402922
005	27	27219274311	20171103	00000001	FA	000100154350	00000313636	00000000200	00000006273	9	2017-12-11 11:34:22.402922
005	28	20313340792	20171103	00000001	FA	000100154351	00000067531	00000000200	00000001351	9	2017-12-11 11:34:22.402922
005	29	27237091987	20171103	00000001	FA	000100154352	00000052418	00000000200	00000001048	9	2017-12-11 11:34:22.402922
005	30	27121002650	20171103	00000001	FA	000100154353	00000161157	00000000200	00000003223	9	2017-12-11 11:34:22.402922
005	31	20368602923	20171103	00000001	FA	000100154354	00000053719	00000000200	00000001074	9	2017-12-11 11:34:22.402922
005	32	27119671634	20171103	00000001	FA	000100154355	00000080495	00000000200	00000001610	9	2017-12-11 11:34:22.402922
005	33	20325453703	20171103	00000001	FA	000100154356	00000065890	00000000200	00000001318	9	2017-12-11 11:34:22.402922
005	34	27361302759	20171103	00000001	FA	000100154361	00000472000	00000000200	00000009440	9	2017-12-11 11:34:22.402922
005	35	27305781466	20171103	00000001	FA	000100154362	00000039000	00000000200	00000000780	9	2017-12-11 11:34:22.402922
005	36	27367571123	20171107	00000001	FA	000100057630	00000254814	00000000200	00000005096	9	2017-12-11 11:34:22.402922
005	37	27173955451	20171107	00000001	FA	000100057631	00000112750	00000000200	00000002255	9	2017-12-11 11:34:22.402922
005	38	27222713140	20171107	00000001	FA	000100057632	00000225970	00000000200	00000004519	9	2017-12-11 11:34:22.402922
005	39	20261719887	20171107	00000001	FA	000100057633	00000219271	00000000200	00000004385	9	2017-12-11 11:34:22.402922
005	40	27316072483	20171107	00000001	FA	000100057634	00000255868	00000000200	00000005117	9	2017-12-11 11:34:22.402922
005	41	27064350809	20171107	00000001	FA	000100057635	00000110909	00000000200	00000002218	9	2017-12-11 11:34:22.402922
005	42	20113547198	20171107	00000001	FA	000100057638	00002314050	00000000200	00000046281	9	2017-12-11 11:34:22.402922
005	43	23289795464	20171107	00000001	FA	000100057639	00003305785	00000000200	00000066116	9	2017-12-11 11:34:22.402922
005	44	23241216349	20171107	00000001	FA	000100057649	00000075167	00000000200	00000001503	9	2017-12-11 11:34:22.402922
005	45	27313340339	20171107	00000001	FA	000100154504	00000192895	00000000200	00000003858	9	2017-12-11 11:34:22.402922
005	46	27237091987	20171107	00000001	FA	000100154505	00000109517	00000000200	00000002190	9	2017-12-11 11:34:22.402922
005	47	20225250473	20171107	00000001	FA	000100154506	00000034710	00000000200	00000000694	9	2017-12-11 11:34:22.402922
005	48	23237095979	20171107	00000001	FA	000100154507	00000098157	00000000200	00000001963	9	2017-12-11 11:34:22.402922
005	49	27950274226	20171107	00000001	FA	000100154508	00000028047	00000000200	00000000561	9	2017-12-11 11:34:22.402922
005	50	27121002650	20171107	00000001	FA	000100154509	00000133946	00000000200	00000002679	9	2017-12-11 11:34:22.402922
005	51	20213549619	20171107	00000001	FA	000100154510	00000069421	00000000200	00000001388	9	2017-12-11 11:34:22.402922
005	52	20273644912	20171107	00000001	FA	000100154511	00000085666	00000000200	00000001713	9	2017-12-11 11:34:22.402922
005	53	27297722927	20171107	00000001	FA	000100154513	00000308065	00000000200	00000006161	9	2017-12-11 11:34:22.402922
005	54	20182383016	20171107	00000001	FA	000100154514	00000022925	00000000200	00000000459	9	2017-12-11 11:34:22.402922
005	55	27325453465	20171107	00000001	FA	000100154515	00000053502	00000000200	00000001070	9	2017-12-11 11:34:22.402922
005	56	20313340792	20171107	00000001	FA	000100154516	00000034710	00000000200	00000000694	9	2017-12-11 11:34:22.402922
005	57	27219274311	20171107	00000001	FA	000100154517	00000075166	00000000200	00000001503	9	2017-12-11 11:34:22.402922
005	58	20290121753	20171107	00000001	FA	000100154518	00000072294	00000000200	00000001446	9	2017-12-11 11:34:22.402922
005	59	27346653502	20171107	00000001	FA	000100154519	00000205186	00000000200	00000004104	9	2017-12-11 11:34:22.402922
005	60	30714924644	20171107	00000001	FA	000100154520	00000075166	00000000200	00000001503	9	2017-12-11 11:34:22.402922
005	61	30714924644	20171107	00000001	FA	000100154521	00000075166	00000000200	00000001503	9	2017-12-11 11:34:22.402922
005	62	27305781466	20171107	00000001	FA	000100154522	00000107004	00000000200	00000002140	9	2017-12-11 11:34:22.402922
005	63	27346653782	20171107	00000001	FA	000100154523	00000201167	00000000200	00000004023	9	2017-12-11 11:34:22.402922
005	64	20264630453	20171107	00000001	FA	000100154524	00000069421	00000000200	00000001388	9	2017-12-11 11:34:22.402922
005	65	20082698508	20171107	00000001	FA	000100154525	00000049523	00000000200	00000000990	9	2017-12-11 11:34:22.402922
005	66	20173955449	20171107	00000001	FA	000100154527	00000092555	00000000200	00000001851	9	2017-12-11 11:34:22.402922
005	67	27248176607	20171107	00000001	FA	000100154528	00000147452	00000000200	00000002949	9	2017-12-11 11:34:22.402922
005	68	20940818690	20171107	00000001	FA	000100154530	00000375834	00000000200	00000007517	9	2017-12-11 11:34:22.402922
005	69	27173955249	20171107	00000001	FA	000100154531	00000751669	00000000200	00000015033	9	2017-12-11 11:34:22.402922
005	70	20054139285	20171113	00000001	FA	000100057734	00000110040	00000000200	00000002201	9	2017-12-11 11:34:22.402922
005	71	27222713140	20171111	00000001	FA	000100057735	00000079815	00000000200	00000001596	9	2017-12-11 11:34:22.402922
005	72	27368604483	20171111	00000001	FA	000100057736	00000044736	00000000200	00000000895	9	2017-12-11 11:34:22.402922
005	73	27222713140	20171111	00000001	FA	000100057737	00000063260	00000000200	00000001265	9	2017-12-11 11:34:22.402922
005	74	27064350809	20171113	00000001	FA	000100057738	00000168591	00000000200	00000003372	9	2017-12-11 11:34:22.402922
005	75	27173955451	20171113	00000001	FA	000100057739	00000213423	00000000200	00000004268	9	2017-12-11 11:34:22.402922
005	76	27367571123	20171113	00000001	FA	000100057741	00000232964	00000000200	00000004659	9	2017-12-11 11:34:22.402922
005	77	20261719887	20171113	00000001	FA	000100057745	00000406947	00000000200	00000008139	9	2017-12-11 11:34:22.402922
005	78	27222713140	20171113	00000001	FA	000100057746	00000347107	00000000200	00000006942	9	2017-12-11 11:34:22.402922
005	79	27222713140	20171113	00000001	FA	000100057747	00000347107	00000000200	00000006942	9	2017-12-11 11:34:22.402922
005	80	27125944707	20171113	00000001	FA	000100057748	00000330579	00000000200	00000006612	9	2017-12-11 11:34:22.402922
002	272	27237091987	20171124	00004968	FA	001100049317	00000094642	00000000200	00000001893	6	2017-12-07 11:13:01.502569
002	273	27237091987	20171128	00004969	FA	001100049472	00000063390	00000000200	00000001268	6	2017-12-07 11:13:01.502569
002	274	27173955451	20171101	00004970	FA	001100023208	00000146904	00000000200	00000002938	6	2017-12-07 11:13:01.502569
002	275	27173955451	20171103	00004971	FA	001100023300	00000022363	00000000200	00000000447	6	2017-12-07 11:13:01.502569
002	276	27173955451	20171107	00004972	FA	001100023423	00000231590	00000000200	00000004632	6	2017-12-07 11:13:01.502569
002	277	27173955451	20171107	00004973	FA	001100001240	-0000035656	00000000200	-0000000713	6	2017-12-07 11:13:01.502569
002	278	27173955451	20171110	00004974	FA	001100023526	00000036687	00000000200	00000000734	6	2017-12-07 11:13:01.502569
002	279	27173955451	20171115	00004975	FA	001100023655	00000157373	00000000200	00000003147	6	2017-12-07 11:13:01.502569
002	280	27173955451	20171115	00004976	FA	001100023658	00000030456	00000000200	00000000609	6	2017-12-07 11:13:01.502569
002	281	27173955451	20171117	00004977	FA	001100023790	00000058607	00000000200	00000001172	6	2017-12-07 11:13:01.502569
002	282	27173955451	20171122	00004978	FA	001100023907	00000081063	00000000200	00000001621	6	2017-12-07 11:13:01.502569
002	283	27173955451	20171122	00004979	FA	001100023908	00000176308	00000000200	00000003526	6	2017-12-07 11:13:01.502569
002	284	27173955451	20171129	00004980	FA	001100024113	00000011799	00000000200	00000000236	6	2017-12-07 11:13:01.502569
002	285	23237095979	20171101	00004981	FA	001100048092	00000041822	00000000200	00000000836	6	2017-12-07 11:13:01.502569
002	286	23237095979	20171108	00004982	FA	001100048453	00000020945	00000000200	00000000419	6	2017-12-07 11:13:01.502569
002	287	23237095979	20171115	00004983	FA	001100048797	00000042876	00000000200	00000000858	6	2017-12-07 11:13:01.502569
002	288	23141622099	20171108	00004984	FA	001100048425	00000242880	00000000200	00000004858	6	2017-12-07 11:13:01.502569
002	289	23141622099	20171108	00004985	FA	001100048431	00000226642	00000000200	00000004533	6	2017-12-07 11:13:01.502569
002	290	23141622099	20171108	00004986	FA	001100048432	00000113460	00000000200	00000002269	6	2017-12-07 11:13:01.502569
002	291	23141622099	20171115	00004987	FA	001100048780	00000312114	00000000200	00000006242	6	2017-12-07 11:13:01.502569
002	292	23141622099	20171115	00004988	FA	001100048781	00000284494	00000000200	00000005690	6	2017-12-07 11:13:01.502569
002	293	23141622099	20171116	00004989	FA	001100048910	00000110757	00000000200	00000002215	6	2017-12-07 11:13:01.502569
002	294	23289795464	20171101	00004990	FA	001100023203	00000354467	00000000200	00000007089	6	2017-12-07 11:13:01.502569
002	295	23289795464	20171101	00004991	FA	001100023204	00000311940	00000000200	00000006239	6	2017-12-07 11:13:01.502569
002	296	23289795464	20171101	00004992	FA	001100023230	00000311940	00000000200	00000006239	6	2017-12-07 11:13:01.502569
002	297	23289795464	20171102	00004993	FA	001100001234	-0000102442	00000000200	-0000002049	6	2017-12-07 11:13:01.502569
002	298	23289795464	20171103	00004994	FA	001100023303	00000247680	00000000200	00000004954	6	2017-12-07 11:13:01.502569
002	299	23289795464	20171107	00004995	FA	001100023429	00000050453	00000000200	00000001009	6	2017-12-07 11:13:01.502569
002	300	23289795464	20171110	00004996	FA	001100023531	00000113615	00000000200	00000002272	6	2017-12-07 11:13:01.502569
002	301	23289795464	20171110	00004997	FA	001100023532	00000192978	00000000200	00000003860	6	2017-12-07 11:13:01.502569
002	302	23289795464	20171110	00004998	FA	001100023533	00000124740	00000000200	00000002495	6	2017-12-07 11:13:01.502569
002	303	23289795464	20171114	00004999	FA	001100023623	00000080592	00000000200	00000001612	6	2017-12-07 11:13:01.502569
002	304	23289795464	20171114	00005000	FA	001100023624	00000030240	00000000200	00000000605	6	2017-12-07 11:13:01.502569
002	305	23289795464	20171115	00005001	FA	001100023660	00000108566	00000000200	00000002171	6	2017-12-07 11:13:01.502569
002	306	23289795464	20171115	00005002	FA	001100023662	00000132123	00000000200	00000002642	6	2017-12-07 11:13:01.502569
002	307	23289795464	20171115	00005003	FA	001100023663	00000030240	00000000200	00000000605	6	2017-12-07 11:13:01.502569
002	308	23289795464	20171117	00005004	FA	001100023784	00000164634	00000000200	00000003293	6	2017-12-07 11:13:01.502569
002	309	23289795464	20171117	00005005	FA	001100023785	00000030240	00000000200	00000000605	6	2017-12-07 11:13:01.502569
002	310	23289795464	20171117	00005006	FA	001100023786	00000171820	00000000200	00000003436	6	2017-12-07 11:13:01.502569
002	311	23289795464	20171122	00005007	FA	001100023904	00000067914	00000000200	00000001358	6	2017-12-07 11:13:01.502569
002	312	23289795464	20171122	00005008	FA	001100023905	00000126630	00000000200	00000002533	6	2017-12-07 11:13:01.502569
002	313	23289795464	20171122	00005009	FA	001100023916	00000044767	00000000200	00000000895	6	2017-12-07 11:13:01.502569
002	314	23289795464	20171122	00005010	FA	001100023917	00000044767	00000000200	00000000895	6	2017-12-07 11:13:01.502569
013	442	30711644578	20171023	00098892	FA	001000098892	00000359122	00000000200	00000007182	17	2017-11-15 11:40:32.00095
013	443	30670359170	20171023	00098893	FA	001000098893	00000134555	00000000200	00000002691	17	2017-11-15 11:40:32.00095
013	444	30609719954	20171023	00098895	FA	001000098895	00000182747	00000000200	00000003655	17	2017-11-15 11:40:32.00095
013	445	30609719954	20171023	00098896	FA	001000098896	00000043547	00000000200	00000000871	17	2017-11-15 11:40:32.00095
013	446	30714735817	20171023	00098905	FA	001000098905	00000212816	00000000200	00000004256	17	2017-11-15 11:40:32.00095
013	447	30712398562	20171023	00098906	FA	001000098906	00000185557	00000000200	00000003711	17	2017-11-15 11:40:32.00095
013	448	27346653502	20171023	00227029	FA	001000227029	00000201535	00000000200	00000004031	17	2017-11-15 11:40:32.00095
013	449	27000000006	20171023	00227030	FA	001000227030	00000076816	00000000200	00000001536	17	2017-11-15 11:40:32.00095
013	450	27313340339	20171023	00227031	FA	001000227031	00000106170	00000000200	00000002123	17	2017-11-15 11:40:32.00095
013	451	30714924644	20171023	00227032	FA	001000227032	00000064173	00000000200	00000001283	17	2017-11-15 11:40:32.00095
013	452	27363206641	20171023	00227033	FA	001000227033	00000333647	00000000200	00000006673	17	2017-11-15 11:40:32.00095
013	453	27044920102	20171023	00227034	FA	001000227034	00000052071	00000000200	00000001041	17	2017-11-15 11:40:32.00095
013	454	27248176607	20171023	00227035	FA	001000227035	00000228671	00000000200	00000004573	17	2017-11-15 11:40:32.00095
013	455	27950274226	20171023	00227036	FA	001000227036	00000018573	00000000200	00000000371	17	2017-11-15 11:40:32.00095
013	456	27297722927	20171023	00227037	FA	001000227037	00000432293	00000000200	00000008646	17	2017-11-15 11:40:32.00095
013	457	27305781466	20171023	00227039	FA	001000227039	00000090144	00000000200	00000001803	17	2017-11-15 11:40:32.00095
013	458	27394430655	20171023	00227040	FA	001000227040	00000102584	00000000200	00000002052	17	2017-11-15 11:40:32.00095
013	459	23331976199	20171023	00227041	FA	001000227041	00000069214	00000000200	00000001384	17	2017-11-15 11:40:32.00095
013	460	20176333074	20171023	00227042	FA	001000227042	00000406624	00000000200	00000008132	17	2017-11-15 11:40:32.00095
002	315	23289795464	20171122	00005011	FA	001100023924	00000117624	00000000200	00000002352	6	2017-12-07 11:13:01.502569
002	316	23289795464	20171124	00005012	FA	001100024010	00000072083	00000000200	00000001442	6	2017-12-07 11:13:01.502569
002	317	23289795464	20171124	00005013	FA	001100024011	00000112078	00000000200	00000002242	6	2017-12-07 11:13:01.502569
002	318	23289795464	20171129	00005014	FA	001100024114	00000065444	00000000200	00000001309	6	2017-12-07 11:13:01.502569
002	319	23289795464	20171129	00005015	FA	001100024125	00000030240	00000000200	00000000605	6	2017-12-07 11:13:01.502569
002	320	23289795464	20171129	00005016	FA	001100024126	00000091290	00000000200	00000001826	6	2017-12-07 11:13:01.502569
002	321	23289795464	20171129	00005017	FA	001100024127	00000091290	00000000200	00000001826	6	2017-12-07 11:13:01.502569
002	322	23289795464	20171129	00005018	FA	001100001284	-0000091290	00000000200	-0000001826	6	2017-12-07 11:13:01.502569
002	323	27313340339	20171102	00005019	FA	001100002369	-0000067550	00000000200	-0000001351	6	2017-12-07 11:13:01.502569
002	324	27313340339	20171108	00005020	FA	001100048419	00000127866	00000000200	00000002557	6	2017-12-07 11:13:01.502569
002	325	27313340339	20171110	00005021	FA	001100048605	00000013140	00000000200	00000000263	6	2017-12-07 11:13:01.502569
002	326	27313340339	20171115	00005022	FA	001100048765	00000080436	00000000200	00000001609	6	2017-12-07 11:13:01.502569
002	327	27313340339	20171122	00005023	FA	001100049164	00000007900	00000000200	00000000158	6	2017-12-07 11:13:01.502569
002	328	27313340339	20171122	00005024	FA	001100049165	00000112990	00000000200	00000002260	6	2017-12-07 11:13:01.502569
002	329	27313340339	20171124	00005025	FA	001100049328	00000023081	00000000200	00000000462	6	2017-12-07 11:13:01.502569
002	330	27313340339	20171128	00005026	FA	001100049498	00000031599	00000000200	00000000632	6	2017-12-07 11:13:01.502569
002	331	27367571123	20171107	00005027	FA	001100023422	00000163020	00000000200	00000003260	6	2017-12-07 11:13:01.502569
002	332	27367571123	20171108	00005028	FA	001100023449	00000033150	00000000200	00000000663	6	2017-12-07 11:13:01.502569
002	333	27367571123	20171110	00005029	FA	001100023543	00000067250	00000000200	00000001345	6	2017-12-07 11:13:01.502569
002	334	27367571123	20171115	00005030	FA	001100023639	00000121340	00000000200	00000002427	6	2017-12-07 11:13:01.502569
002	335	27367571123	20171115	00005031	FA	001100023640	00000123701	00000000200	00000002474	6	2017-12-07 11:13:01.502569
002	336	27367571123	20171122	00005032	FA	001100023876	00000163003	00000000200	00000003260	6	2017-12-07 11:13:01.502569
002	337	27367571123	20171122	00005033	FA	001100023877	00000078888	00000000200	00000001578	6	2017-12-07 11:13:01.502569
002	338	27367571123	20171124	00005034	FA	001100001278	-0000041610	00000000200	-0000000832	6	2017-12-07 11:13:01.502569
002	339	27367571123	20171129	00005035	FA	001100024108	00000094421	00000000200	00000001888	6	2017-12-07 11:13:01.502569
002	340	27266554740	20171101	00005036	FA	001100048137	00000117391	00000000200	00000002348	6	2017-12-07 11:13:01.502569
002	341	27266554740	20171108	00005037	FA	001100048437	00000084049	00000000200	00000001681	6	2017-12-07 11:13:01.502569
002	342	27266554740	20171122	00005038	FA	001100049169	00000039415	00000000200	00000000788	6	2017-12-07 11:13:01.502569
002	343	27266554740	20171124	00005039	FA	001100049326	00000050807	00000000200	00000001016	6	2017-12-07 11:13:01.502569
002	344	20243042802	20171108	00005040	FA	001100048445	00000026599	00000000200	00000000532	6	2017-12-07 11:13:01.502569
002	345	20243042802	20171108	00005041	FA	001100048447	00000180619	00000000200	00000003612	6	2017-12-07 11:13:01.502569
002	346	20243042802	20171108	00005042	FA	001100048448	00000353475	00000000200	00000007070	6	2017-12-07 11:13:01.502569
002	347	20243042802	20171108	00005043	FA	001100048449	00000036388	00000000200	00000000728	6	2017-12-07 11:13:01.502569
002	348	20243042802	20171108	00005044	FA	001100048455	00000052681	00000000200	00000001054	6	2017-12-07 11:13:01.502569
002	349	20243042802	20171122	00005045	FA	001100049145	00000297980	00000000200	00000005960	6	2017-12-07 11:13:01.502569
002	350	20243042802	20171122	00005046	FA	001100049146	00000076026	00000000200	00000001521	6	2017-12-07 11:13:01.502569
002	351	20243042802	20171129	00005047	FA	001100049527	00000294950	00000000200	00000005899	6	2017-12-07 11:13:01.502569
002	352	20243042802	20171129	00005048	FA	001100049528	00000247008	00000000200	00000004940	6	2017-12-07 11:13:01.502569
002	353	20243042802	20171129	00005049	FA	001100049530	00000181030	00000000200	00000003621	6	2017-12-07 11:13:01.502569
002	354	27216609528	20171101	00005050	FA	001100048119	00000065901	00000000200	00000001318	6	2017-12-07 11:13:01.502569
002	355	27216609528	20171101	00005051	FA	001100048123	00000032950	00000000200	00000000659	6	2017-12-07 11:13:01.502569
002	356	27216609528	20171103	00005052	FA	001100048246	00000155859	00000000200	00000003117	6	2017-12-07 11:13:01.502569
002	357	27361302759	20171101	00005053	FA	001100048109	00000087489	00000000200	00000001750	6	2017-12-07 11:13:01.502569
002	358	27361302759	20171101	00005054	FA	001100002360	-0000016081	00000000200	-0000000322	6	2017-12-07 11:13:01.502569
002	359	27361302759	20171108	00005055	FA	001100048450	00000094171	00000000200	00000001883	6	2017-12-07 11:13:01.502569
002	360	27361302759	20171115	00005056	FA	001100048807	00000122380	00000000200	00000002448	6	2017-12-07 11:13:01.502569
013	461	27229348774	20171023	00227043	FA	001000227043	00000108745	00000000200	00000002175	17	2017-11-15 11:40:32.00095
013	462	20205896857	20171023	00227049	FA	001000227049	00000066437	00000000200	00000001329	17	2017-11-15 11:40:32.00095
013	463	30586600741	20171023	00009379	FA	001100009379	00000056468	00000000200	00000001129	17	2017-11-15 11:40:32.00095
013	464	27136572348	20171024	00098943	FA	001000098943	00000530910	00000000200	00000010618	17	2017-11-15 11:40:32.00095
013	465	27240625070	20171024	00227054	FA	001000227054	00000035722	00000000600	00000002143	17	2017-11-15 11:40:32.00095
013	466	27257772123	20171024	00227075	FA	001000227075	00000033638	00000000600	00000002018	17	2017-11-15 11:40:32.00095
013	467	20280000893	20171024	00027338	FA	001300027338	00000151343	00000000200	00000003027	17	2017-11-15 11:40:32.00095
013	468	30711644578	20171024	00009380	FA	001100009380	00000404406	00000000200	00000008088	17	2017-11-15 11:40:32.00095
013	469	20147388323	20171025	00098958	FA	001000098958	00000115328	00000000200	00000002307	17	2017-11-15 11:40:32.00095
013	470	20110567570	20171025	00098959	FA	001000098959	00000256491	00000000200	00000005130	17	2017-11-15 11:40:32.00095
013	471	20227583232	20171025	00098960	FA	001000098960	00000085074	00000000200	00000001701	17	2017-11-15 11:40:32.00095
013	472	27224236773	20171025	00098983	FA	001000098983	00000064880	00000000200	00000001298	17	2017-11-15 11:40:32.00095
013	473	27133302994	20171025	00098995	FA	001000098995	00000072895	00000000600	00000004374	17	2017-11-15 11:40:32.00095
013	474	27228688989	20171025	00227118	FA	001000227118	00000087073	00000000200	00000001741	17	2017-11-15 11:40:32.00095
013	475	27300258544	20171025	00227119	FA	001000227119	00000109350	00000000600	00000006561	17	2017-11-15 11:40:32.00095
013	476	27165291269	20171025	00227156	FA	001000227156	00000194683	00000000600	00000011681	17	2017-11-15 11:40:32.00095
013	477	20147959282	20171025	00227159	FA	001000227159	00000193776	00000000200	00000003876	17	2017-11-15 11:40:32.00095
013	478	33542719449	20171025	00009398	FA	001100009398	00000042258	00000000200	00000000845	17	2017-11-15 11:40:32.00095
013	479	27313340339	20171026	00227164	FA	001000227164	00000087693	00000000200	00000001754	17	2017-11-15 11:40:32.00095
013	480	27064350809	20171026	00099029	FA	001000099029	00000206484	00000000200	00000004130	17	2017-11-15 11:40:32.00095
013	481	27125944707	20171026	00099022	FA	001000099022	00000102083	00000000200	00000002042	17	2017-11-15 11:40:32.00095
013	482	23344036209	20171026	00099021	FA	001000099021	00000124488	00000000200	00000002490	17	2017-11-15 11:40:32.00095
013	483	27313340754	20171026	00227178	FA	001000227178	00000062639	00000000200	00000001253	17	2017-11-15 11:40:32.00095
013	484	27100281819	20171026	00099020	FA	001000099020	00000087897	00000000200	00000001758	17	2017-11-15 11:40:32.00095
013	485	27000000006	20171026	00227166	FA	001000227166	00000067875	00000000200	00000001358	17	2017-11-15 11:40:32.00095
013	486	27346653502	20171026	00227165	FA	001000227165	00000237408	00000000200	00000004748	17	2017-11-15 11:40:32.00095
013	487	23163173824	20171026	00099028	FA	001000099028	00000076617	00000000200	00000001532	17	2017-11-15 11:40:32.00095
013	488	27064350809	20171026	00099027	FA	001000099027	00000111283	00000000200	00000002226	17	2017-11-15 11:40:32.00095
013	489	27188206463	20171026	00099025	FA	001000099025	00000084801	00000000200	00000001696	17	2017-11-15 11:40:32.00095
002	361	27361302759	20171122	00005057	FA	001100049136	00000101702	00000000200	00000002034	6	2017-12-07 11:13:01.502569
002	362	27361302759	20171122	00005058	FA	001100049175	00000091119	00000000200	00000001822	6	2017-12-07 11:13:01.502569
013	490	20226157833	20171026	00099001	FA	001000099001	00000057233	00000000200	00000001145	17	2017-11-15 11:40:32.00095
013	491	27952625514	20171026	00099002	FA	001000099002	00000140020	00000000200	00000002800	17	2017-11-15 11:40:32.00095
013	492	27952625514	20171026	00099003	FA	001000099003	00000573548	00000000200	00000011471	17	2017-11-15 11:40:32.00095
013	493	27952625514	20171026	00099004	FA	001000099004	00000773861	00000000200	00000015477	17	2017-11-15 11:40:32.00095
013	494	27952625514	20171026	00099005	FA	001000099005	00000773861	00000000200	00000015477	17	2017-11-15 11:40:32.00095
013	495	27367571123	20171026	00099006	FA	001000099006	00000199311	00000000200	00000003986	17	2017-11-15 11:40:32.00095
013	496	23289795464	20171026	00099007	FA	001000099007	00000185285	00000000200	00000003706	17	2017-11-15 11:40:32.00095
013	497	27173955451	20171026	00099008	FA	001000099008	00000074033	00000000200	00000001481	17	2017-11-15 11:40:32.00095
013	498	20073261806	20171026	00099009	FA	001000099009	00000309545	00000000200	00000006191	17	2017-11-15 11:40:32.00095
013	499	27056603560	20171026	00099010	FA	001000099010	00000629519	00000000200	00000012590	17	2017-11-15 11:40:32.00095
013	500	30670364603	20171026	00099011	FA	001000099011	00000126746	00000000200	00000002535	17	2017-11-15 11:40:32.00095
013	501	20274035170	20171026	00099012	FA	001000099012	00000190709	00000000200	00000003814	17	2017-11-15 11:40:32.00095
013	502	20305781720	20171026	00099013	FA	001000099013	00000483769	00000000200	00000009675	17	2017-11-15 11:40:32.00095
013	503	20054139285	20171026	00099014	FA	001000099014	00000191518	00000000200	00000003830	17	2017-11-15 11:40:32.00095
013	504	27163184732	20171026	00099015	FA	001000099015	00000119243	00000000200	00000002385	17	2017-11-15 11:40:32.00095
013	505	23289795464	20171026	00099016	FA	001000099016	00000098670	00000000200	00000001973	17	2017-11-15 11:40:32.00095
013	506	27232869564	20171026	00099017	FA	001000099017	00000371225	00000000200	00000007425	17	2017-11-15 11:40:32.00095
013	507	27222713140	20171026	00099018	FA	001000099018	00000039834	00000000200	00000000797	17	2017-11-15 11:40:32.00095
013	508	20078122316	20171026	00099019	FA	001000099019	00000099656	00000000200	00000001993	17	2017-11-15 11:40:32.00095
013	509	20163184592	20171026	00099023	FA	001000099023	00000092928	00000000200	00000001859	17	2017-11-15 11:40:32.00095
013	510	27222713140	20171026	00099024	FA	001000099024	00000213245	00000000200	00000004265	17	2017-11-15 11:40:32.00095
013	511	20166734178	20171026	00099026	FA	001000099026	00000097068	00000000200	00000001941	17	2017-11-15 11:40:32.00095
013	512	20267029114	20171026	00227167	FA	001000227167	00000038277	00000000200	00000000766	17	2017-11-15 11:40:32.00095
013	513	27248176607	20171026	00227168	FA	001000227168	00000044888	00000000200	00000000898	17	2017-11-15 11:40:32.00095
013	514	27305781466	20171026	00227169	FA	001000227169	00000066437	00000000200	00000001329	17	2017-11-15 11:40:32.00095
013	515	27363206641	20171026	00227170	FA	001000227170	00000356434	00000000200	00000007129	17	2017-11-15 11:40:32.00095
013	516	23331976199	20171026	00227171	FA	001000227171	00000082173	00000000200	00000001643	17	2017-11-15 11:40:32.00095
002	363	27361302759	20171124	00005059	FA	001100049341	00000072886	00000000200	00000001458	6	2017-12-07 11:13:01.502569
002	364	27361302759	20171124	00005060	FA	001100049343	00000043800	00000000200	00000000876	6	2017-12-07 11:13:01.502569
002	365	27361302759	20171129	00005061	FA	001100049511	00000038645	00000000200	00000000773	6	2017-12-07 11:13:01.502569
002	366	20267029114	20171124	00005062	FA	001100049324	00000057532	00000000200	00000001151	6	2017-12-07 11:13:01.502569
002	367	30715184040	20171103	00005063	FA	001100023293	00000331945	00000000200	00000006639	6	2017-12-07 11:13:01.502569
002	368	30715184040	20171107	00005064	FA	001100023381	00000235729	00000000200	00000004715	6	2017-12-07 11:13:01.502569
002	369	30715184040	20171108	00005065	FA	001100023442	00000254809	00000000200	00000005096	6	2017-12-07 11:13:01.502569
002	370	30715184040	20171114	00005066	FA	001100023597	00000453560	00000000200	00000009071	6	2017-12-07 11:13:01.502569
002	371	30715184040	20171117	00005067	FA	001100023765	00000321576	00000000200	00000006432	6	2017-12-07 11:13:01.502569
002	372	30715184040	20171122	00005068	FA	001100023902	00000353405	00000000200	00000007068	6	2017-12-07 11:13:01.502569
002	373	30715184040	20171122	00005069	FA	001100023929	00000018552	00000000200	00000000371	6	2017-12-07 11:13:01.502569
002	374	30715184040	20171124	00005070	FA	001100024005	00000211763	00000000200	00000004235	6	2017-12-07 11:13:01.502569
002	375	30715184040	20171124	00005071	FA	001100024006	00000064191	00000000200	00000001284	6	2017-12-07 11:13:01.502569
002	376	30715184040	20171128	00005072	FA	001100024069	00000379921	00000000200	00000007598	6	2017-12-07 11:13:01.502569
002	377	30715184040	20171129	00005073	FA	001100024135	00000088586	00000000200	00000001772	6	2017-12-07 11:13:01.502569
002	378	20213541421	20171101	00005074	FA	001100023201	00000126602	00000000200	00000002532	6	2017-12-07 11:13:01.502569
002	379	20213541421	20171110	00005075	FA	001100023529	00000046250	00000000200	00000000925	6	2017-12-07 11:13:01.502569
002	380	20213541421	20171115	00005076	FA	001100023649	00000016065	00000000200	00000000321	6	2017-12-07 11:13:01.502569
002	381	20213541421	20171117	00005077	FA	001100023775	00000277743	00000000200	00000005555	6	2017-12-07 11:13:01.502569
002	382	20213541421	20171124	00005078	FA	001100024014	00000134135	00000000200	00000002683	6	2017-12-07 11:13:01.502569
002	383	30715278347	20171101	00005079	FA	001100023210	00000124153	00000000200	00000002483	6	2017-12-07 11:13:01.502569
002	384	30715278347	20171101	00005080	FA	001100001230	-0000231600	00000000200	-0000004632	6	2017-12-07 11:13:01.502569
002	385	27325453465	20171101	00005081	FA	001100048181	00000099828	00000000200	00000001997	6	2017-12-07 11:13:01.502569
002	386	27325453465	20171101	00005082	FA	001100002364	-0000104132	00000000200	-0000002083	6	2017-12-07 11:13:01.502569
002	387	27325453465	20171108	00005083	FA	001100048439	00000064816	00000000200	00000001296	6	2017-12-07 11:13:01.502569
002	388	27325453465	20171115	00005084	FA	001100048767	00000066599	00000000200	00000001332	6	2017-12-07 11:13:01.502569
002	389	27325453465	20171124	00005085	FA	001100049318	00000038560	00000000200	00000000771	6	2017-12-07 11:13:01.502569
002	390	27325453465	20171125	00005086	FA	001100002417	-0000038560	00000000200	-0000000771	6	2017-12-07 11:13:01.502569
002	391	27325453465	20171128	00005087	FA	001100049471	00000034269	00000000200	00000000685	6	2017-12-07 11:13:01.502569
002	392	20054099895	20171101	00005088	FA	001100048120	00000057409	00000000200	00000001148	6	2017-12-07 11:13:01.502569
002	393	27215184760	20171124	00005089	FA	001100049325	00000022478	00000000200	00000000450	6	2017-12-07 11:13:01.502569
002	394	27346653502	20171110	00005090	FA	001100048603	00000052859	00000000200	00000001057	6	2017-12-07 11:13:01.502569
002	395	27346653502	20171115	00005091	FA	001100048798	00000077075	00000000200	00000001542	6	2017-12-07 11:13:01.502569
002	396	27346653502	20171117	00005092	FA	001100048956	00000037741	00000000200	00000000755	6	2017-12-07 11:13:01.502569
002	397	27346653502	20171122	00005093	FA	001100049147	00000290802	00000000200	00000005816	6	2017-12-07 11:13:01.502569
002	398	20226158074	20171101	00005094	FA	001100048101	00000220676	00000000200	00000004414	6	2017-12-07 11:13:01.502569
002	399	20226158074	20171101	00005095	FA	001100048104	00000062406	00000000200	00000001248	6	2017-12-07 11:13:01.502569
002	400	20226158074	20171101	00005096	FA	001100048114	00000034238	00000000200	00000000685	6	2017-12-07 11:13:01.502569
002	401	20226158074	20171103	00005097	FA	001100048229	00000019462	00000000200	00000000389	6	2017-12-07 11:13:01.502569
002	402	20226158074	20171104	00005098	FA	001100048322	00000030128	00000000200	00000000603	6	2017-12-07 11:13:01.502569
002	403	20226158074	20171108	00005099	FA	001100048416	00000027379	00000000200	00000000548	6	2017-12-07 11:13:01.502569
002	404	20226158074	20171110	00005100	FA	001100048604	00000014600	00000000200	00000000292	6	2017-12-07 11:13:01.502569
002	405	20226158074	20171115	00005101	FA	001100048796	00000160537	00000000200	00000003211	6	2017-12-07 11:13:01.502569
002	406	20226158074	20171117	00005102	FA	001100048964	00000156423	00000000200	00000003128	6	2017-12-07 11:13:01.502569
002	407	20226158074	20171124	00005103	FA	001100049332	00000144812	00000000200	00000002896	6	2017-12-07 11:13:01.502569
002	408	20226158074	20171129	00005104	FA	001100049520	00000117906	00000000200	00000002358	6	2017-12-07 11:13:01.502569
002	409	20313340792	20171101	00005105	FA	001100048089	00000069496	00000000200	00000001390	6	2017-12-07 11:13:01.502569
002	410	20313340792	20171108	00005106	FA	001100048438	00000099914	00000000200	00000001998	6	2017-12-07 11:13:01.502569
002	411	20313340792	20171109	00005107	FA	001100048546	00000248895	00000000200	00000004978	6	2017-12-07 11:13:01.502569
002	412	20313340792	20171115	00005108	FA	001100048772	00000251992	00000000200	00000005040	6	2017-12-07 11:13:01.502569
002	413	20313340792	20171117	00005109	FA	001100048974	00000018242	00000000200	00000000365	6	2017-12-07 11:13:01.502569
002	414	20313340792	20171122	00005110	FA	001100049153	00000144394	00000000200	00000002888	6	2017-12-07 11:13:01.502569
002	415	20313340792	20171129	00005111	FA	001100049515	00000078770	00000000200	00000001575	6	2017-12-07 11:13:01.502569
002	416	20266077719	20171108	00005112	FA	001100023444	00000091013	00000000200	00000001820	6	2017-12-07 11:13:01.502569
002	417	20266077719	20171115	00005113	FA	001100023651	00000081183	00000000200	00000001624	6	2017-12-07 11:13:01.502569
002	418	20266077719	20171122	00005114	FA	001100023901	00001025613	00000000200	00000020512	6	2017-12-07 11:13:01.502569
002	419	20266077719	20171129	00005115	FA	001100024111	00000108946	00000000200	00000002179	6	2017-12-07 11:13:01.502569
002	420	20266077719	20171129	00005116	FA	001100024112	00000060421	00000000200	00000001208	6	2017-12-07 11:13:01.502569
002	421	27044920102	20171110	00005117	FA	001200002841	00000079734	00000000200	00000001595	6	2017-12-07 11:13:01.502569
002	422	27044920102	20171124	00005118	FA	001200002901	00000073805	00000000200	00000001476	6	2017-12-07 11:13:01.502569
013	517	30714924644	20171026	00227172	FA	001000227172	00000061909	00000000200	00000001238	17	2017-11-15 11:40:32.00095
013	518	27297722927	20171026	00227173	FA	001000227173	00000278909	00000000200	00000005578	17	2017-11-15 11:40:32.00095
013	519	27044920102	20171026	00227174	FA	001000227174	00000084364	00000000200	00000001687	17	2017-11-15 11:40:32.00095
013	520	27394430655	20171026	00227175	FA	001000227175	00000064173	00000000200	00000001283	17	2017-11-15 11:40:32.00095
013	521	27950274226	20171026	00227176	FA	001000227176	00000026028	00000000200	00000000521	17	2017-11-15 11:40:32.00095
013	522	20226158074	20171026	00227177	FA	001000227177	00000037517	00000000200	00000000750	17	2017-11-15 11:40:32.00095
013	523	20388075806	20171026	00227179	FA	001000227179	00000195482	00000000200	00000003910	17	2017-11-15 11:40:32.00095
013	524	27266554740	20171026	00227180	FA	001000227180	00000087073	00000000200	00000001741	17	2017-11-15 11:40:32.00095
013	525	20313340792	20171026	00227181	FA	001000227181	00000033219	00000000600	00000001993	17	2017-11-15 11:40:32.00095
013	526	23237095979	20171026	00227183	FA	001000227183	00000033219	00000000200	00000000664	17	2017-11-15 11:40:32.00095
013	527	27237091987	20171026	00227184	FA	001000227184	00000078107	00000000200	00000001562	17	2017-11-15 11:40:32.00095
013	528	20225250473	20171026	00227185	FA	001000227185	00000082864	00000000200	00000001657	17	2017-11-15 11:40:32.00095
013	529	27101402156	20171026	00227186	FA	001000227186	00000056881	00000000200	00000001138	17	2017-11-15 11:40:32.00095
013	530	20284642369	20171026	00227187	FA	001000227187	00000054964	00000000200	00000001099	17	2017-11-15 11:40:32.00095
013	531	20271066547	20171026	00227188	FA	001000227188	00000043537	00000000200	00000000871	17	2017-11-15 11:40:32.00095
013	532	20182383016	20171026	00227189	FA	001000227189	00000009868	00000000200	00000000197	17	2017-11-15 11:40:32.00095
013	533	27219273431	20171026	00227190	FA	001000227190	00000103582	00000000200	00000002072	17	2017-11-15 11:40:32.00095
013	534	27121002650	20171026	00227191	FA	001000227191	00000097391	00000000200	00000001948	17	2017-11-15 11:40:32.00095
013	535	20213549619	20171026	00227192	FA	001000227192	00000045180	00000000200	00000000904	17	2017-11-15 11:40:32.00095
013	536	27921235521	20171026	00227193	FA	001000227193	00000033219	00000000200	00000000664	17	2017-11-15 11:40:32.00095
013	537	20118857020	20171026	00227194	FA	001000227194	00000053155	00000000200	00000001063	17	2017-11-15 11:40:32.00095
013	538	20273644912	20171026	00227195	FA	001000227195	00000071648	00000000200	00000001433	17	2017-11-15 11:40:32.00095
013	539	20082698508	20171026	00227196	FA	001000227196	00000081914	00000000200	00000001638	17	2017-11-15 11:40:32.00095
013	540	30714735817	20171026	00099038	FA	001000099038	00000219314	00000000200	00000004386	17	2017-11-15 11:40:32.00095
013	541	30712398562	20171026	00099039	FA	001000099039	00000045634	00000000200	00000000913	17	2017-11-15 11:40:32.00095
013	542	20345487035	20171026	00099041	FA	001000099041	00000039634	00000000600	00000002378	17	2017-11-15 11:40:32.00095
013	543	20084272648	20171026	00227203	FA	001000227203	00000074483	00000000200	00000001490	17	2017-11-15 11:40:32.00095
013	544	27292122239	20171026	00227219	FA	001000227219	00000034570	00000000200	00000000691	17	2017-11-15 11:40:32.00095
013	545	27224183203	20171026	00227222	FA	001000227222	00000033670	00000000200	00000000673	17	2017-11-15 11:40:32.00095
013	546	20179005353	20171026	00227224	FA	001000227224	00000009868	00000000600	00000000592	17	2017-11-15 11:40:32.00095
013	547	27293830199	20171026	00027438	FA	001300027438	00000164387	00000000600	00000009863	17	2017-11-15 11:40:32.00095
013	548	27222713140	20171026	00009441	FA	001100009441	00000056470	00000000200	00000001129	17	2017-11-15 11:40:32.00095
013	549	30542722688	20171026	00009442	FA	001100009442	00000029416	00000000200	00000000588	17	2017-11-15 11:40:32.00095
013	550	20333551455	20171027	00227231	FA	001000227231	00000126080	00000000600	00000007565	17	2017-11-15 11:40:32.00095
013	551	23044920484	20171027	00227242	FA	001000227242	00000060814	00000000600	00000003649	17	2017-11-15 11:40:32.00095
013	552	27000000006	20171027	00099070	FA	001000099070	00000094396	00000000200	00000001888	17	2017-11-15 11:40:32.00095
013	553	20215184596	20171027	00227266	FA	001000227266	00000033219	00000000200	00000000664	17	2017-11-15 11:40:32.00095
013	554	27181000851	20171027	00227281	FA	001000227281	00000551314	00000000600	00000033079	17	2017-11-15 11:40:32.00095
013	555	27314998842	20171027	00227282	FA	001000227282	00000194113	00000000600	00000011647	17	2017-11-15 11:40:32.00095
013	556	27228688989	20171027	00023649	FA	001100023649	00000066437	00000000200	00000001329	17	2017-11-15 11:40:32.00095
013	557	20269447649	20171027	00023652	FA	001100023652	00000073190	00000000600	00000004391	17	2017-11-15 11:40:32.00095
013	558	20313340792	20171028	00023660	FA	001100023660	00000035729	00000000600	00000002144	17	2017-11-15 11:40:32.00095
013	559	27292122239	20171028	00023672	FA	001100023672	00000044521	00000000200	00000000890	17	2017-11-15 11:40:32.00095
013	560	27224183203	20171028	00023699	FA	001100023699	00000035729	00000000200	00000000715	17	2017-11-15 11:40:32.00095
013	561	27228688989	20171028	00023702	FA	001100023702	00000035729	00000000200	00000000715	17	2017-11-15 11:40:32.00095
013	562	27228688989	20171028	00227316	FA	001000227316	00000091197	00000000200	00000001824	17	2017-11-15 11:40:32.00095
013	563	20147388323	20171028	00099109	FA	001000099109	00000066852	00000000200	00000001337	17	2017-11-15 11:40:32.00095
013	564	27300258544	20171028	00227315	FA	001000227315	00000090455	00000000600	00000005427	17	2017-11-15 11:40:32.00095
013	565	27224236773	20171028	00099085	FA	001000099085	00000089715	00000000200	00000001794	17	2017-11-15 11:40:32.00095
013	566	30712127895	20171028	00099093	FA	001000099093	00000205552	00000000200	00000004111	17	2017-11-15 11:40:32.00095
013	567	30712127895	20171028	00099094	FA	001000099094	00000005058	00000000200	00000000101	17	2017-11-15 11:40:32.00095
013	568	27952625514	20171030	00099122	FA	001000099122	00000229818	00000000200	00000004596	17	2017-11-15 11:40:32.00095
013	569	27163184732	20171030	00099123	FA	001000099123	00000108033	00000000200	00000002161	17	2017-11-15 11:40:32.00095
013	570	23289795464	20171030	00099124	FA	001000099124	00000090606	00000000200	00000001812	17	2017-11-15 11:40:32.00095
013	571	27232869564	20171030	00099125	FA	001000099125	00000181863	00000000200	00000003637	17	2017-11-15 11:40:32.00095
013	572	20073261806	20171030	00099126	FA	001000099126	00000691198	00000000200	00000013824	17	2017-11-15 11:40:32.00095
013	573	30670364603	20171030	00099127	FA	001000099127	00000062969	00000000200	00000001259	17	2017-11-15 11:40:32.00095
013	574	27056603560	20171030	00099128	FA	001000099128	00000393607	00000000200	00000007872	17	2017-11-15 11:40:32.00095
013	575	27316072483	20171030	00099129	FA	001000099129	00000014708	00000000200	00000000294	17	2017-11-15 11:40:32.00095
013	576	20305781720	20171030	00099130	FA	001000099130	00000622550	00000000200	00000012451	17	2017-11-15 11:40:32.00095
013	577	27173955451	20171030	00099131	FA	001000099131	00000122014	00000000200	00000002440	17	2017-11-15 11:40:32.00095
013	578	23289795464	20171030	00099132	FA	001000099132	00000088925	00000000200	00000001779	17	2017-11-15 11:40:32.00095
013	579	27367571123	20171030	00099133	FA	001000099133	00000140578	00000000200	00000002812	17	2017-11-15 11:40:32.00095
013	580	20054139285	20171030	00099134	FA	001000099134	00000201763	00000000200	00000004035	17	2017-11-15 11:40:32.00095
013	581	27313340339	20171030	00227331	FA	001000227331	00000106231	00000000200	00000002125	17	2017-11-15 11:40:32.00095
013	582	27000000006	20171030	00227332	FA	001000227332	00000053531	00000000200	00000001071	17	2017-11-15 11:40:32.00095
013	583	27346653502	20171030	00227333	FA	001000227333	00000149548	00000000200	00000002991	17	2017-11-15 11:40:32.00095
013	584	23331976199	20171030	00227334	FA	001000227334	00000300466	00000000200	00000006009	17	2017-11-15 11:40:32.00095
013	585	27394430655	20171030	00227335	FA	001000227335	00000068141	00000000200	00000001363	17	2017-11-15 11:40:32.00095
013	586	27044920102	20171030	00227336	FA	001000227336	00000062964	00000000200	00000001259	17	2017-11-15 11:40:32.00095
013	587	27297722927	20171030	00227337	FA	001000227337	00000302684	00000000200	00000006054	17	2017-11-15 11:40:32.00095
013	588	27248176607	20171030	00227338	FA	001000227338	00000160742	00000000200	00000003215	17	2017-11-15 11:40:32.00095
013	589	27305781466	20171030	00227339	FA	001000227339	00000069120	00000000200	00000001382	17	2017-11-15 11:40:32.00095
013	590	27363206641	20171030	00227340	FA	001000227340	00000229750	00000000200	00000004595	17	2017-11-15 11:40:32.00095
013	591	30714924644	20171030	00227341	FA	001000227341	00000069120	00000000200	00000001382	17	2017-11-15 11:40:32.00095
013	592	30670359170	20171030	00099137	FA	001000099137	00000266034	00000000200	00000005321	17	2017-11-15 11:40:32.00095
013	593	30609719954	20171030	00099138	FA	001000099138	00000164604	00000000200	00000003292	17	2017-11-15 11:40:32.00095
013	594	30609719954	20171030	00099139	FA	001000099139	00000029038	00000000200	00000000581	17	2017-11-15 11:40:32.00095
013	595	30714735817	20171030	00099149	FA	001000099149	00000159284	00000000200	00000003186	17	2017-11-15 11:40:32.00095
013	596	30712398562	20171030	00099150	FA	001000099150	00000056710	00000000200	00000001134	17	2017-11-15 11:40:32.00095
013	597	20082121235	20171030	00023707	FA	001100023707	00000115979	00000000200	00000002320	17	2017-11-15 11:40:32.00095
013	598	27228688989	20171030	00023708	FA	001100023708	00000035729	00000000200	00000000715	17	2017-11-15 11:40:32.00095
013	599	27274033784	20171030	00023709	FA	001100023709	00000024723	00000000600	00000001483	17	2017-11-15 11:40:32.00095
013	600	20205896857	20171030	00023710	FA	001100023710	00000142916	00000000200	00000002858	17	2017-11-15 11:40:32.00095
013	601	20173981342	20171030	00023711	FA	001100023711	00000071289	00000000200	00000001426	17	2017-11-15 11:40:32.00095
013	602	27136572348	20171031	00099168	FA	001000099168	00000470159	00000000200	00000009403	17	2017-11-15 11:40:32.00095
013	603	27257772123	20171031	00227345	FA	001000227345	00000032436	00000000600	00000001946	17	2017-11-15 11:40:32.00095
013	604	20280000893	20171031	00027580	FA	001300027580	00000073931	00000000200	00000001479	17	2017-11-15 11:40:32.00095
013	605	27224183890	20171031	00027583	FA	001300027583	00000121573	00000000600	00000007294	17	2017-11-15 11:40:32.00095
013	606	27274033784	20171031	00227378	FA	001000227378	00000153919	00000000600	00000009235	17	2017-11-15 11:40:32.00095
013	607	27228688989	20171031	00023714	FA	001100023714	00000035729	00000000200	00000000715	17	2017-11-15 11:40:32.00095
001	256	02028464236	20171012	00019743	FA	110000019743	00000024114	00000000200	00000000482	5	2017-11-16 12:54:35.10011
001	257	02704492010	20171025	00020255	FA	110000020255	00000020864	00000000200	00000000418	5	2017-11-16 12:54:35.10011
001	258	02011885702	20171025	00020256	FA	110000020256	00000130153	00000000200	00000002603	5	2017-11-16 12:54:35.10011
001	259	02011885702	20171025	00020257	FA	110000020257	00000011820	00000000200	00000000236	5	2017-11-16 12:54:35.10011
001	260	02716318446	20171025	00020259	FA	110000020259	00000069100	00000000200	00000001382	5	2017-11-16 12:54:35.10011
001	261	02028464236	20171013	00019749	FA	110000019749	00000026300	00000000200	00000000526	5	2017-11-16 12:54:35.10011
001	262	02025082746	20171002	00019238	FA	110000019238	00000012604	00000000200	00000000252	5	2017-11-16 12:54:35.10011
001	263	02025082746	20171002	00019240	FA	110000019240	00000040408	00000000200	00000000808	5	2017-11-16 12:54:35.10011
001	264	02034665318	20171007	00019501	FA	110000019501	00000021042	00000000200	00000000421	5	2017-11-16 12:54:35.10011
001	265	02025082746	20171019	00020016	FA	110000020016	00000090249	00000000200	00000001805	5	2017-11-16 12:54:35.10011
001	266	02034665318	20171013	00019765	FA	110000019765	00000035491	00000000200	00000000710	5	2017-11-16 12:54:35.10011
001	267	02723927990	20171013	00019766	FA	110000019766	00000084765	00000000200	00000001696	5	2017-11-16 12:54:35.10011
001	268	27216609528	20171003	00019255	FA	110000019255	00000080800	00000000200	00000001616	5	2017-11-16 12:54:35.10011
001	269	02025082746	20171003	00019260	FA	110000019260	00000031770	00000000200	00000000635	5	2017-11-16 12:54:35.10011
001	270	02094314994	20171026	00020287	FA	110000020287	00000426188	00000000200	00000008522	5	2017-11-16 12:54:35.10011
001	271	02017395544	20171013	00019776	FA	110000019776	00000048875	00000000200	00000000978	5	2017-11-16 12:54:35.10011
001	272	02721927343	20171031	00020544	FA	110000020544	00000297283	00000000200	00000005947	5	2017-11-16 12:54:35.10011
001	273	02732545346	20171013	00019777	FA	110000019777	00000070317	00000000200	00000001406	5	2017-11-16 12:54:35.10011
001	274	02034665318	20171019	00020046	FA	110000020046	00000014028	00000000200	00000000281	5	2017-11-16 12:54:35.10011
001	275	02729398531	20171026	00020308	FA	110000020308	00000004108	00000000200	00000000082	5	2017-11-16 12:54:35.10011
001	276	02034665318	20171020	00020062	FA	110000020062	00000009498	00000000200	00000000190	5	2017-11-16 12:54:35.10011
001	277	02014983351	20171020	00020063	FA	110000020063	00000047304	00000000200	00000000945	5	2017-11-16 12:54:35.10011
001	278	02736320664	20171020	00020064	FA	110000020064	00000030168	00000000200	00000000603	5	2017-11-16 12:54:35.10011
001	279	02025082746	20171026	00020323	FA	110000020323	00000194448	00000000200	00000003889	5	2017-11-16 12:54:35.10011
001	280	02731653948	20171009	00019556	FA	110000019556	00000639934	00000000200	00000012798	5	2017-11-16 12:54:35.10011
001	281	27216609528	20171004	00019301	FA	110000019301	00000138227	00000000200	00000002765	5	2017-11-16 12:54:35.10011
001	282	02016559574	20171014	00019815	FA	110000019815	00000025878	00000000200	00000000517	5	2017-11-16 12:54:35.10011
001	283	02736320664	20171004	00019304	FA	110000019304	00000069321	00000000200	00000001387	5	2017-11-16 12:54:35.10011
001	284	02014983351	20171014	00019816	FA	110000019816	00000095515	00000000200	00000001910	5	2017-11-16 12:54:35.10011
001	285	02028464236	20171020	00020072	FA	110000020072	00000030101	00000000200	00000000602	5	2017-11-16 12:54:35.10011
001	286	02011885702	20171004	00019306	FA	110000019306	00000077967	00000000200	00000001559	5	2017-11-16 12:54:35.10011
001	287	02716318446	20171004	00019307	FA	110000019307	00000396190	00000000200	00000007923	5	2017-11-16 12:54:35.10011
001	288	02724664522	20171020	00020079	FA	110000020079	00000070653	00000000200	00000001413	5	2017-11-16 12:54:35.10011
001	289	02723621663	20171020	00020080	FA	110000020080	00000338810	00000000200	00000006775	5	2017-11-16 12:54:35.10011
001	290	02011885702	20171004	00019314	FA	110000019314	00000148782	00000000200	00000002975	5	2017-11-16 12:54:35.10011
001	291	02704161991	20171004	00019315	FA	110000019315	00000056023	00000000200	00000001122	5	2017-11-16 12:54:35.10011
001	292	20011994378	20171004	00019323	FA	110000019323	00000318219	00000000200	00000006363	5	2017-11-16 12:54:35.10011
001	293	02094314994	20171014	00019835	FA	110000019835	00000196594	00000000200	00000003932	5	2017-11-16 12:54:35.10011
001	294	02729398531	20171004	00019324	FA	110000019324	00000041148	00000000200	00000000823	5	2017-11-16 12:54:35.10011
001	295	02724664522	20171027	00020352	FA	110000020352	00000093666	00000000200	00000001872	5	2017-11-16 12:54:35.10011
001	296	02028464236	20171027	00020353	FA	110000020353	00000046055	00000000200	00000000921	5	2017-11-16 12:54:35.10011
001	297	02017395544	20171014	00019842	FA	110000019842	00000105190	00000000200	00000002104	5	2017-11-16 12:54:35.10011
001	298	02704492010	20171004	00019332	FA	110000019332	00000071754	00000000200	00000001436	5	2017-11-16 12:54:35.10011
001	299	02014983351	20171027	00020356	FA	110000020356	00000089465	00000000200	00000001789	5	2017-11-16 12:54:35.10011
001	300	02794226956	20171004	00019333	FA	110000019333	00000029536	00000000200	00000000591	5	2017-11-16 12:54:35.10011
001	301	02723621663	20171027	00020357	FA	110000020357	00000090605	00000000200	00000001812	5	2017-11-16 12:54:35.10011
001	302	02017395544	20171004	00019334	FA	110000019334	00000151075	00000000200	00000003022	5	2017-11-16 12:54:35.10011
001	303	27216609528	20171027	00020362	FA	110000020362	00000025359	00000000200	00000000507	5	2017-11-16 12:54:35.10011
001	304	02011885702	20171021	00020107	FA	110000020107	00000025908	00000000200	00000000518	5	2017-11-16 12:54:35.10011
001	305	20011994378	20171021	00020108	FA	110000020108	00000081573	00000000200	00000001631	5	2017-11-16 12:54:35.10011
001	306	02034665318	20171021	00020112	FA	110000020112	00000069312	00000000200	00000001386	5	2017-11-16 12:54:35.10011
001	307	02724664522	20171021	00020113	FA	110000020113	00000059857	00000000200	00000001197	5	2017-11-16 12:54:35.10011
001	308	02017395544	20171021	00020118	FA	110000020118	00000114158	00000000200	00000002283	5	2017-11-16 12:54:35.10011
001	309	02729772292	20171021	00020119	FA	110000020119	00000081763	00000000200	00000001635	5	2017-11-16 12:54:35.10011
001	310	02723621663	20171021	00020122	FA	110000020122	00000123575	00000000200	00000002472	5	2017-11-16 12:54:35.10011
001	311	02794226956	20171005	00019361	FA	110000019361	00000019786	00000000200	00000000396	5	2017-11-16 12:54:35.10011
001	312	02335320490	20171027	00020385	FA	110000020385	00000009996	00000000200	00000000200	5	2017-11-16 12:54:35.10011
001	313	02011885702	20171021	00020133	FA	110000020133	00000056998	00000000200	00000001141	5	2017-11-16 12:54:35.10011
001	314	02094314994	20171017	00019878	FA	110000019878	00000222180	00000000200	00000004443	5	2017-11-16 12:54:35.10011
001	315	02704161991	20171011	00019627	FA	110000019627	00000042000	00000000200	00000000840	5	2017-11-16 12:54:35.10011
001	316	02716318446	20171011	00019628	FA	110000019628	00000087005	00000000200	00000001741	5	2017-11-16 12:54:35.10011
001	317	02034665318	20171017	00019885	FA	110000019885	00000045946	00000000200	00000000919	5	2017-11-16 12:54:35.10011
001	318	20011994378	20171011	00019634	FA	110000019634	00000099684	00000000200	00000001993	5	2017-11-16 12:54:35.10011
001	319	02794226956	20171011	00019635	FA	110000019635	00000223402	00000000200	00000004466	5	2017-11-16 12:54:35.10011
001	320	27216609528	20171017	00019894	FA	110000019894	00000182429	00000000200	00000003649	5	2017-11-16 12:54:35.10011
001	321	02028464236	20171011	00019640	FA	110000019640	00000068865	00000000200	00000001377	5	2017-11-16 12:54:35.10011
001	322	02731653948	20171017	00019896	FA	110000019896	00000077168	00000000200	00000001543	5	2017-11-16 12:54:35.10011
001	323	02704161991	20171011	00019642	FA	110000019642	00000331267	00000000200	00000006625	5	2017-11-16 12:54:35.10011
001	324	02729398531	20171011	00019646	FA	110000019646	00000018935	00000000200	00000000378	5	2017-11-16 12:54:35.10011
001	325	02721927343	20171011	00019647	FA	110000019647	00000027360	00000000200	00000000547	5	2017-11-16 12:54:35.10011
001	326	02016559574	20171011	00019648	FA	110000019648	00000035559	00000000200	00000000711	5	2017-11-16 12:54:35.10011
001	327	02017395544	20171011	00019650	FA	110000019650	00000113868	00000000200	00000002278	5	2017-11-16 12:54:35.10011
001	328	02731653948	20171011	00019652	FA	110000019652	00000024247	00000000200	00000000485	5	2017-11-16 12:54:35.10011
001	329	02005409989	20171011	00019653	FA	110000019653	00000027279	00000000200	00000000545	5	2017-11-16 12:54:35.10011
001	330	02723927990	20171028	00020422	FA	110000020422	00000062385	00000000200	00000001248	5	2017-11-16 12:54:35.10011
001	331	02716318446	20171011	00019655	FA	110000019655	00000877401	00000000200	00000017550	5	2017-11-16 12:54:35.10011
001	332	02034665318	20171028	00020423	FA	110000020423	00000015432	00000000200	00000000309	5	2017-11-16 12:54:35.10011
001	333	02731653948	20171023	00020168	FA	110000020168	00000729253	00000000200	00000014584	5	2017-11-16 12:54:35.10011
001	334	02011885702	20171028	00020424	FA	110000020424	00000104821	00000000200	00000002097	5	2017-11-16 12:54:35.10011
001	335	02722680323	20171023	00020171	FA	110000020171	00000030548	00000000200	00000000611	5	2017-11-16 12:54:35.10011
001	336	02021518189	20171028	00020429	FA	110000020429	00000082084	00000000200	00000001642	5	2017-11-16 12:54:35.10011
001	337	02094314994	20171028	00020430	FA	110000020430	00000047628	00000000200	00000000953	5	2017-11-16 12:54:35.10011
001	338	02011885702	20171028	00020431	FA	110000020431	00000104821	00000000200	00000002097	5	2017-11-16 12:54:35.10011
001	339	02716318446	20171011	00019666	FA	110000019666	00000459744	00000000200	00000009197	5	2017-11-16 12:54:35.10011
001	340	27216609528	20171024	00020180	FA	110000020180	00000180697	00000000200	00000003614	5	2017-11-16 12:54:35.10011
001	341	20011994378	20171006	00019419	FA	110000019419	00000082626	00000000200	00000001652	5	2017-11-16 12:54:35.10011
001	342	02017395544	20171006	00019420	FA	110000019420	00000104809	00000000200	00000002096	5	2017-11-16 12:54:35.10011
001	343	02731334075	20171018	00019933	FA	110000019933	00000104919	00000000200	00000002098	5	2017-11-16 12:54:35.10011
001	344	02094081864	20171006	00019422	FA	110000019422	00000069966	00000000200	00000001399	5	2017-11-16 12:54:35.10011
001	345	02723927990	20171018	00019934	FA	110000019934	00000115805	00000000200	00000002316	5	2017-11-16 12:54:35.10011
001	346	02016559574	20171018	00019935	FA	110000019935	00000025057	00000000200	00000000501	5	2017-11-16 12:54:35.10011
001	347	02011885702	20171018	00019936	FA	110000019936	00000179180	00000000200	00000003583	5	2017-11-16 12:54:35.10011
001	348	02314162209	20171018	00019937	FA	110000019937	00000524105	00000000200	00000010481	5	2017-11-16 12:54:35.10011
001	349	27216609528	20171006	00019426	FA	110000019426	00000073251	00000000200	00000001465	5	2017-11-16 12:54:35.10011
001	350	02704492010	20171018	00019938	FA	110000019938	00000190361	00000000200	00000003806	5	2017-11-16 12:54:35.10011
001	351	02028464236	20171006	00019428	FA	110000019428	00000026782	00000000200	00000000536	5	2017-11-16 12:54:35.10011
001	352	02732545346	20171024	00020196	FA	110000020196	00000034704	00000000200	00000000694	5	2017-11-16 12:54:35.10011
001	353	02724664522	20171006	00019429	FA	110000019429	00000045295	00000000200	00000000906	5	2017-11-16 12:54:35.10011
001	354	02014983351	20171006	00019430	FA	110000019430	00000054355	00000000200	00000001087	5	2017-11-16 12:54:35.10011
001	355	02723621663	20171018	00019943	FA	110000019943	00000056600	00000000200	00000001132	5	2017-11-16 12:54:35.10011
001	356	02005409989	20171018	00019944	FA	110000019944	00000046239	00000000200	00000000925	5	2017-11-16 12:54:35.10011
001	357	02017395544	20171018	00019945	FA	110000019945	00000143782	00000000200	00000002875	5	2017-11-16 12:54:35.10011
001	358	02794226956	20171018	00019946	FA	110000019946	00000232007	00000000200	00000004640	5	2017-11-16 12:54:35.10011
001	359	02014983351	20171006	00019435	FA	110000019435	00000072189	00000000200	00000001444	5	2017-11-16 12:54:35.10011
001	360	02736320664	20171018	00019947	FA	110000019947	00000077132	00000000200	00000001542	5	2017-11-16 12:54:35.10011
001	361	02736320664	20171018	00019949	FA	110000019949	00000033718	00000000200	00000000674	5	2017-11-16 12:54:35.10011
001	362	02314162209	20171018	00019950	FA	110000019950	00000923257	00000000200	00000018465	5	2017-11-16 12:54:35.10011
001	363	20011994378	20171018	00019951	FA	110000019951	00000314415	00000000200	00000006288	5	2017-11-16 12:54:35.10011
001	364	02314162209	20171018	00019952	FA	110000019952	00000494314	00000000200	00000009885	5	2017-11-16 12:54:35.10011
001	365	02704161991	20171018	00019957	FA	110000019957	00000135204	00000000200	00000002704	5	2017-11-16 12:54:35.10011
001	366	02729398531	20171018	00019958	FA	110000019958	00000122226	00000000200	00000002444	5	2017-11-16 12:54:35.10011
001	367	02335320490	20171012	00019709	FA	110000019709	00000012495	00000000200	00000000250	5	2017-11-16 12:54:35.10011
002	423	27064350809	20171110	00005119	FA	001200001601	00000140182	00000000200	00000002804	6	2017-12-07 11:13:01.502569
002	424	27064350809	20171124	00005120	FA	001200001627	00000098642	00000000200	00000001973	6	2017-12-07 11:13:01.502569
002	425	27297722927	20171110	00005121	FA	001200002839	00000216387	00000000200	00000004328	6	2017-12-07 11:13:01.502569
002	426	27297722927	20171110	00005122	FA	001200002840	00000081481	00000000200	00000001630	6	2017-12-07 11:13:01.502569
002	427	27297722927	20171124	00005123	FA	001200002893	00000102062	00000000200	00000002041	6	2017-12-07 11:13:01.502569
002	428	20160060183	20171110	00005124	FA	001200002838	00000196314	00000000200	00000003926	6	2017-12-07 11:13:01.502569
002	429	20160060183	20171124	00005125	FA	001200002891	00000141614	00000000200	00000002832	6	2017-12-07 11:13:01.502569
002	430	27351546862	20171124	00005126	FA	001200002899	00000052248	00000000200	00000001045	6	2017-12-07 11:13:01.502569
002	431	27363206641	20171110	00005127	FA	001200002845	00000170148	00000000200	00000003403	6	2017-12-07 11:13:01.502569
002	432	27363206641	20171124	00005128	FA	001200002897	00000172073	00000000200	00000003441	6	2017-12-07 11:13:01.502569
002	433	27367571123	20171110	00005129	FA	001200001600	00000159021	00000000200	00000003180	6	2017-12-07 11:13:01.502569
002	434	27367571123	20171124	00005130	FA	001200001628	00000178985	00000000200	00000003580	6	2017-12-07 11:13:01.502569
002	435	27219273431	20171110	00005131	FA	001200002842	00000123912	00000000200	00000002478	6	2017-12-07 11:13:01.502569
002	436	27219273431	20171124	00005132	FA	001200002898	00000165814	00000000200	00000003316	6	2017-12-07 11:13:01.502569
002	437	23141622099	20171124	00005133	FA	001200002894	00000187054	00000000200	00000003741	6	2017-12-07 11:13:01.502569
002	438	23141622099	20171124	00005134	FA	001200002895	00000018050	00000000200	00000000361	6	2017-12-07 11:13:01.502569
002	439	20313340792	20171110	00005135	FA	001200002846	00000097719	00000000200	00000001954	6	2017-12-07 11:13:01.502569
002	440	20313340792	20171124	00005136	FA	001200002896	00000041747	00000000200	00000000835	6	2017-12-07 11:13:01.502569
005	81	27125944707	20171113	00000001	FA	000100057749	00000433628	00000000200	00000008673	9	2017-12-11 11:34:22.402922
005	82	30708978562	20171113	00000001	FA	000100057751	00000277686	00000000200	00000005554	9	2017-12-11 11:34:22.402922
005	83	20160060183	20171109	00000001	FA	000100154662	00000037400	00000000200	00000000748	9	2017-12-11 11:34:22.402922
005	84	30714924644	20171111	00000001	FA	000100154752	00000084000	00000000200	00000001680	9	2017-12-11 11:34:22.402922
005	85	20290121753	20171111	00000001	FA	000100154753	00000084000	00000000200	00000001680	9	2017-12-11 11:34:22.402922
005	86	27361302759	20171111	00000001	FA	000100154754	00000257796	00000000200	00000005156	9	2017-12-11 11:34:22.402922
005	87	27219274311	20171111	00000001	FA	000100154755	00000187101	00000000200	00000003742	9	2017-12-11 11:34:22.402922
005	88	27219274311	20171111	00000001	FA	000100154756	00000157034	00000000200	00000003141	9	2017-12-11 11:34:22.402922
005	89	27346653782	20171111	00000001	FA	000100154757	00000202570	00000000200	00000004051	9	2017-12-11 11:34:22.402922
005	90	27346653502	20171111	00000001	FA	000100154759	00000250087	00000000200	00000005002	9	2017-12-11 11:34:22.402922
005	91	27121002650	20171111	00000001	FA	000100154760	00000152172	00000000200	00000003043	9	2017-12-11 11:34:22.402922
005	92	20213549619	20171111	00000001	FA	000100154761	00000084000	00000000200	00000001680	9	2017-12-11 11:34:22.402922
005	93	27950274226	20171111	00000001	FA	000100154762	00000034642	00000000200	00000000693	9	2017-12-11 11:34:22.402922
005	94	27305781466	20171113	00000001	FA	000100154763	00000040000	00000000200	00000000800	9	2017-12-11 11:34:22.402922
005	95	20283075878	20171113	00000001	FA	000100154764	00000041205	00000000200	00000000824	9	2017-12-11 11:34:22.402922
005	96	27297722927	20171113	00000001	FA	000100154765	00000284880	00000000200	00000005698	9	2017-12-11 11:34:22.402922
005	97	27248176607	20171113	00000001	FA	000100154766	00000126022	00000000200	00000002520	9	2017-12-11 11:34:22.402922
005	98	20173955449	20171113	00000001	FA	000100154767	00000108574	00000000200	00000002171	9	2017-12-11 11:34:22.402922
005	99	27266554740	20171113	00000001	FA	000100154771	00000114194	00000000200	00000002284	9	2017-12-11 11:34:22.402922
005	100	27325453465	20171113	00000001	FA	000100154772	00000079720	00000000200	00000001594	9	2017-12-11 11:34:22.402922
005	101	20264630453	20171113	00000001	FA	000100154773	00000038906	00000000200	00000000778	9	2017-12-11 11:34:22.402922
005	102	27173955249	20171113	00000001	FA	000100154774	00000347107	00000000200	00000006942	9	2017-12-11 11:34:22.402922
005	103	23930384003	20171113	00000001	FA	000100154775	00000694214	00000000200	00000013884	9	2017-12-11 11:34:22.402922
005	104	20243042802	20171114	00000001	FA	000100154826	00000694214	00000000200	00000013884	9	2017-12-11 11:34:22.402922
005	105	20274035170	20171118	00000001	FA	000100057830	00000187181	00000000200	00000003744	9	2017-12-11 11:34:22.402922
005	106	27125944707	20171118	00000001	FA	000100057831	00000661157	00000000200	00000013223	9	2017-12-11 11:34:22.402922
005	107	27056603560	20171118	00000001	FA	000100057832	00000513700	00000000200	00000010274	9	2017-12-11 11:34:22.402922
005	108	27316072483	20171118	00000001	FA	000100057833	00000250320	00000000200	00000005006	9	2017-12-11 11:34:22.402922
005	109	20173955740	20171118	00000001	FA	000100057834	00000104132	00000000200	00000002083	9	2017-12-11 11:34:22.402922
005	110	27367571123	20171118	00000001	FA	000100057835	00000217221	00000000200	00000004344	9	2017-12-11 11:34:22.402922
005	111	27173955451	20171118	00000001	FA	000100057837	00000188320	00000000200	00000003766	9	2017-12-11 11:34:22.402922
005	112	20054139285	20171118	00000001	FA	000100057844	00000089731	00000000200	00000001795	9	2017-12-11 11:34:22.402922
005	113	20118857020	20171118	00000001	FA	000100057845	00000043260	00000000200	00000000865	9	2017-12-11 11:34:22.402922
005	114	20118857020	20171118	00000001	FA	000100057846	00000089331	00000000200	00000001787	9	2017-12-11 11:34:22.402922
005	115	30715184040	20171118	00000001	FA	000100057852	00000220080	00000000200	00000004402	9	2017-12-11 11:34:22.402922
005	116	27222713140	20171118	00000001	FA	000100057853	00000162378	00000000200	00000003248	9	2017-12-11 11:34:22.402922
005	117	27222713140	20171118	00000001	FA	000100057854	00000173554	00000000200	00000003471	9	2017-12-11 11:34:22.402922
005	118	20054139285	20171124	00000001	FA	000100057914	00000055021	00000000200	00000001100	9	2017-12-11 11:34:22.402922
005	119	20118857020	20171124	00000001	FA	000100057915	00000062706	00000000200	00000001254	9	2017-12-11 11:34:22.402922
005	120	20113547198	20171124	00000001	FA	000100057916	00000647347	00000000200	00000012947	9	2017-12-11 11:34:22.402922
005	121	27064350809	20171124	00000001	FA	000100057917	00000160143	00000000200	00000003203	9	2017-12-11 11:34:22.402922
005	122	20073261806	20171124	00000001	FA	000100057918	00001561983	00000000200	00000031240	9	2017-12-11 11:34:22.402922
005	123	27222713140	20171124	00000001	FA	000100057919	00000072263	00000000200	00000001445	9	2017-12-11 11:34:22.402922
005	124	27100281819	20171124	00000001	FA	000100057920	00000104132	00000000200	00000002083	9	2017-12-11 11:34:22.402922
005	125	27367571123	20171124	00000001	FA	000100057921	00000260072	00000000200	00000005201	9	2017-12-11 11:34:22.402922
005	126	20274035170	20171124	00000001	FA	000100057922	00000136603	00000000200	00000002732	9	2017-12-11 11:34:22.402922
005	127	27222713140	20171124	00000001	FA	000100057923	00000061962	00000000200	00000001239	9	2017-12-11 11:34:22.402922
005	128	20261719887	20171124	00000001	FA	000100057924	00000112681	00000000200	00000002254	9	2017-12-11 11:34:22.402922
005	129	27119671634	20171118	00000001	FA	000100154960	00000079455	00000000200	00000001589	9	2017-12-11 11:34:22.402922
005	130	23280189049	20171118	00000001	FA	000100154962	00000034710	00000000200	00000000694	9	2017-12-11 11:34:22.402922
005	131	20213549619	20171118	00000001	FA	000100154963	00000069421	00000000200	00000001388	9	2017-12-11 11:34:22.402922
005	132	27121002650	20171118	00000001	FA	000100154964	00000069421	00000000200	00000001388	9	2017-12-11 11:34:22.402922
005	133	27305781466	20171118	00000001	FA	000100154965	00000083841	00000000200	00000001677	9	2017-12-11 11:34:22.402922
005	134	27044920102	20171118	00000001	FA	000100154967	00000119650	00000000200	00000002393	9	2017-12-11 11:34:22.402922
005	135	27950274226	20171118	00000001	FA	000100154968	00000046318	00000000200	00000000926	9	2017-12-11 11:34:22.402922
005	136	27237091987	20171118	00000001	FA	000100154970	00000140601	00000000200	00000002812	9	2017-12-11 11:34:22.402922
005	137	27346653502	20171118	00000001	FA	000100154971	00000292044	00000000200	00000005841	9	2017-12-11 11:34:22.402922
005	138	27219274311	20171118	00000001	FA	000100154987	00000330578	00000000200	00000006612	9	2017-12-11 11:34:22.402922
005	139	27226803233	20171118	00000001	FA	000100154988	00000088678	00000000200	00000001774	9	2017-12-11 11:34:22.402922
005	140	27325453465	20171118	00000001	FA	000100154990	00000064630	00000000200	00000001293	9	2017-12-11 11:34:22.402922
005	141	20173955449	20171118	00000001	FA	000100154991	00000119680	00000000200	00000002394	9	2017-12-11 11:34:22.402922
005	142	20273644912	20171118	00000001	FA	000100154992	00000061480	00000000200	00000001230	9	2017-12-11 11:34:22.402922
005	143	30714924644	20171118	00000001	FA	000100154993	00000103100	00000000200	00000002062	9	2017-12-11 11:34:22.402922
005	144	27297722927	20171118	00000001	FA	000100155006	00000269909	00000000200	00000005398	9	2017-12-11 11:34:22.402922
005	145	27173955249	20171124	00000001	FA	000100155202	00000347107	00000000200	00000006942	9	2017-12-11 11:34:22.402922
005	146	27305781466	20171124	00000001	FA	000100155203	00000069421	00000000200	00000001388	9	2017-12-11 11:34:22.402922
005	147	27313340339	20171124	00000001	FA	000100155205	00000136684	00000000200	00000002734	9	2017-12-11 11:34:22.402922
005	148	27237091987	20171124	00000001	FA	000100155206	00000047061	00000000200	00000000941	9	2017-12-11 11:34:22.402922
005	149	20313340792	20171124	00000001	FA	000100155207	00000058407	00000000200	00000001168	9	2017-12-11 11:34:22.402922
005	150	23237095979	20171124	00000001	FA	000100155208	00000064577	00000000200	00000001292	9	2017-12-11 11:34:22.402922
005	151	30714924644	20171124	00000001	FA	000100155209	00000069421	00000000200	00000001388	9	2017-12-11 11:34:22.402922
005	152	27361302759	20171124	00000001	FA	000100155210	00000249904	00000000200	00000004998	9	2017-12-11 11:34:22.402922
005	153	27361302759	20171124	00000001	FA	000100155211	00000193100	00000000200	00000003862	9	2017-12-11 11:34:22.402922
005	154	27346653782	20171124	00000001	FA	000100155212	00000057785	00000000200	00000001156	9	2017-12-11 11:34:22.402922
005	155	27266554740	20171124	00000001	FA	000100155213	00000075528	00000000200	00000001511	9	2017-12-11 11:34:22.402922
005	156	27266554740	20171124	00000001	FA	000100155214	00000133599	00000000200	00000002672	9	2017-12-11 11:34:22.402922
005	157	27346653502	20171124	00000001	FA	000100155215	00000104958	00000000200	00000002099	9	2017-12-11 11:34:22.402922
005	158	20290121753	20171124	00000001	FA	000100155216	00000069421	00000000200	00000001388	9	2017-12-11 11:34:22.402922
005	159	27121002650	20171124	00000001	FA	000100155218	00000173553	00000000200	00000003471	9	2017-12-11 11:34:22.402922
005	160	20325453703	20171124	00000001	FA	000100155219	00000069421	00000000200	00000001388	9	2017-12-11 11:34:22.402922
005	161	20173955449	20171124	00000001	FA	000100155221	00000055019	00000000200	00000001100	9	2017-12-11 11:34:22.402922
005	162	27325453465	20171124	00000001	FA	000100155222	00000087271	00000000200	00000001745	9	2017-12-11 11:34:22.402922
005	163	27330231675	20171123	00000001	FA	000100155224	00000034710	00000000200	00000000694	9	2017-12-11 11:34:22.402922
005	164	20113547198	20171128	00000001	FA	000100057989	00000156400	00000000200	00000003128	9	2017-12-11 11:34:22.402922
005	165	20173955449	20171128	00000001	FA	000100155352	00000055019	00000000200	00000001100	9	2017-12-11 11:34:22.402922
005	166	27325453465	20171128	00000001	FA	000100155353	00000086521	00000000200	00000001730	9	2017-12-11 11:34:22.402922
005	167	27297722927	20171128	00000001	FA	000100155354	00000064539	00000000200	00000001291	9	2017-12-11 11:34:22.402922
005	168	20273644912	20171128	00000001	FA	000100155355	00000051866	00000000200	00000001037	9	2017-12-11 11:34:22.402922
005	169	23394435384	20171128	00000001	FA	000100155356	00000410000	00000000200	00000008200	9	2017-12-11 11:34:22.402922
005	170	27044920102	20171128	00000001	FA	000100155357	00000055019	00000000200	00000001100	9	2017-12-11 11:34:22.402922
005	171	27056603560	20171128	00000001	FA	000100057990	00000410000	00000000200	00000008200	9	2017-12-11 11:34:22.402922
005	172	27950274226	20171128	00000001	FA	000100155358	00000021629	00000000200	00000000433	9	2017-12-11 11:34:22.402922
005	173	27064350809	20171128	00000001	FA	000100057991	00000137020	00000000200	00000002740	9	2017-12-11 11:34:22.402922
005	174	23289795464	20171128	00000001	FA	000100057992	00000123500	00000000200	00000002470	9	2017-12-11 11:34:22.402922
005	175	20271066547	20171128	00000001	FA	000100155359	00000034710	00000000200	00000000694	9	2017-12-11 11:34:22.402922
005	176	23241216349	20171128	00000001	FA	000100057993	00000122850	00000000200	00000002457	9	2017-12-11 11:34:22.402922
005	177	27222713140	20171128	00000001	FA	000100057994	00000028739	00000000200	00000000575	9	2017-12-11 11:34:22.402922
005	178	23280189049	20171128	00000001	FA	000100155362	00000043260	00000000200	00000000865	9	2017-12-11 11:34:22.402922
005	179	20213549619	20171128	00000001	FA	000100155363	00000059860	00000000200	00000001197	9	2017-12-11 11:34:22.402922
005	180	27125944707	20171128	00000001	FA	000100057995	00000467740	00000000200	00000009355	9	2017-12-11 11:34:22.402922
005	181	27367571123	20171128	00000001	FA	000100057996	00000303590	00000000200	00000006072	9	2017-12-11 11:34:22.402922
005	182	27100281819	20171128	00000001	FA	000100057997	00000104132	00000000200	00000002083	9	2017-12-11 11:34:22.402922
005	183	27313340339	20171128	00000001	FA	000100155365	00000069421	00000000200	00000001388	9	2017-12-11 11:34:22.402922
005	184	20290121753	20171128	00000001	FA	000100155366	00000034710	00000000200	00000000694	9	2017-12-11 11:34:22.402922
005	185	27219274311	20171128	00000001	FA	000100155367	00000331735	00000000200	00000006635	9	2017-12-11 11:34:22.402922
005	186	27219274311	20171128	00000001	FA	000100155368	00000042633	00000000200	00000000853	9	2017-12-11 11:34:22.402922
005	187	27266554740	20171128	00000001	FA	000100155369	00000080944	00000000200	00000001619	9	2017-12-11 11:34:22.402922
005	188	27346653782	20171128	00000001	FA	000100155370	00000167723	00000000200	00000003354	9	2017-12-11 11:34:22.402922
005	189	27173955249	20171128	00000001	FA	000100155371	00000284052	00000000200	00000005681	9	2017-12-11 11:34:22.402922
005	190	27305781466	20171128	00000001	FA	000100155372	00000024700	00000000200	00000000494	9	2017-12-11 11:34:22.402922
005	191	20325453703	20171128	00000001	FA	000100155373	00000104132	00000000200	00000002083	9	2017-12-11 11:34:22.402922
005	192	20225250473	20171127	00000001	FA	000100155389	00000069421	00000000200	00000001388	9	2017-12-11 11:34:22.402922
005	193	20273644912	20171130	00000001	FA	000100155503	00000067664	00000000200	00000001353	9	2017-12-11 11:34:22.402922
005	194	27297722927	20171130	00000001	FA	000100155504	00000296348	00000000200	00000005927	9	2017-12-11 11:34:22.402922
005	195	27064350809	20171130	00000001	FA	000100058054	00000124441	00000000200	00000002489	9	2017-12-11 11:34:22.402922
005	196	20082698508	20171130	00000001	FA	000100155505	00000000200	00000000200	00000000004	9	2017-12-11 11:34:22.402922
005	197	20082698508	20171130	00000001	FA	000100155505	00000084004	00000000200	00000001680	9	2017-12-11 11:34:22.402922
005	198	27950274226	20171130	00000001	FA	000100155506	00000042000	00000000200	00000000840	9	2017-12-11 11:34:22.402922
005	199	27305781342	20171130	00000001	FA	000100155507	00000091772	00000000200	00000001835	9	2017-12-11 11:34:22.402922
005	200	20325453703	20171130	00000001	FA	000100155508	00000063700	00000000200	00000001274	9	2017-12-11 11:34:22.402922
005	201	23289795464	20171130	00000001	FA	000100058055	00000123500	00000000200	00000002470	9	2017-12-11 11:34:22.402922
005	202	23280189049	20171130	00000001	FA	000100155510	00000051764	00000000200	00000001035	9	2017-12-11 11:34:22.402922
005	203	20274035170	20171130	00000001	FA	000100058057	00000144693	00000000200	00000002894	9	2017-12-11 11:34:22.402922
005	204	23241216349	20171130	00000001	FA	000100058058	00000024700	00000000200	00000000494	9	2017-12-11 11:34:22.402922
005	205	27222713140	20171130	00000001	FA	000100058059	00000050036	00000000200	00000001001	9	2017-12-11 11:34:22.402922
005	206	27173955249	20171130	00000001	FA	000100155522	00000159152	00000000200	00000003183	9	2017-12-11 11:34:22.402922
005	207	27367571123	20171130	00000001	FA	000100058061	00000157144	00000000200	00000003143	9	2017-12-11 11:34:22.402922
005	208	20073261806	20171130	00000001	FA	000100058062	00001561983	00000000200	00000031240	9	2017-12-11 11:34:22.402922
005	209	20213549619	20171130	00000001	FA	000100155525	00000059860	00000000200	00000001197	9	2017-12-11 11:34:22.402922
005	210	20113547198	20171130	00000001	FA	000100058063	00000347107	00000000200	00000006942	9	2017-12-11 11:34:22.402922
005	211	27173955451	20171130	00000001	FA	000100058065	00000246781	00000000200	00000004936	9	2017-12-11 11:34:22.402922
005	212	27100281819	20171130	00000001	FA	000100058066	00000104132	00000000200	00000002083	9	2017-12-11 11:34:22.402922
005	213	20290121753	20171130	00000001	FA	000100155529	00000069421	00000000200	00000001388	9	2017-12-11 11:34:22.402922
005	214	27222713140	20171130	00000001	FA	000100058067	00000412469	00000000200	00000008249	9	2017-12-11 11:34:22.402922
005	215	27361302759	20171130	00000001	FA	000100155531	00000414876	00000000200	00000008298	9	2017-12-11 11:34:22.402922
005	216	27346653782	20171130	00000001	FA	000100155533	00000166990	00000000200	00000003340	9	2017-12-11 11:34:22.402922
005	217	27237091987	20171130	00000001	FA	000100155534	00000069421	00000000200	00000001388	9	2017-12-11 11:34:22.402922
005	218	27226803233	20171130	00000001	FA	000100155535	00000108860	00000000200	00000002177	9	2017-12-11 11:34:22.402922
\.


--
-- TOC entry 6024 (class 0 OID 5287319)
-- Dependencies: 654
-- Data for Name: ag_rete_log; Type: TABLE DATA; Schema: temp; Owner: postgres
--

COPY temp.ag_rete_log (ag_rete, fila, mensaje, usrmod) FROM stdin;
015	0	-Es conveniente que el Número sea correlativo por año (00000001).	18
015	2	Fila 2: El Numero no debe repetirse para el mismo agente en el año.	18
015	3	Fila 3: El Numero no debe repetirse para el mismo agente en el año.	18
015	4	Fila 4: El Numero no debe repetirse para el mismo agente en el año.	18
015	5	Fila 5: El Numero no debe repetirse para el mismo agente en el año.	18
015	6	Fila 6: El Numero no debe repetirse para el mismo agente en el año.	18
015	7	Fila 7: El Numero no debe repetirse para el mismo agente en el año.	18
015	8	Fila 8: El Numero no debe repetirse para el mismo agente en el año.	18
015	9	Fila 9: El Numero no debe repetirse para el mismo agente en el año.	18
015	10	Fila 10: El Numero no debe repetirse para el mismo agente en el año.	18
015	11	Fila 11: El Numero no debe repetirse para el mismo agente en el año.	18
015	12	Fila 12: El Numero no debe repetirse para el mismo agente en el año.	18
015	13	Fila 13: El Numero no debe repetirse para el mismo agente en el año.	18
015	14	Fila 14: El Numero no debe repetirse para el mismo agente en el año.	18
015	15	Fila 15: El Numero no debe repetirse para el mismo agente en el año.	18
015	16	Fila 16: El Numero no debe repetirse para el mismo agente en el año.	18
015	17	Fila 17: El Numero no debe repetirse para el mismo agente en el año.	18
015	18	Fila 18: El Numero no debe repetirse para el mismo agente en el año.	18
015	19	Fila 19: El Numero no debe repetirse para el mismo agente en el año.	18
015	20	Fila 20: El Numero no debe repetirse para el mismo agente en el año.	18
015	21	Fila 21: El Numero no debe repetirse para el mismo agente en el año.	18
015	22	Fila 22: El Numero no debe repetirse para el mismo agente en el año.	18
015	23	Fila 23: El Numero no debe repetirse para el mismo agente en el año.	18
015	24	Fila 24: El Numero no debe repetirse para el mismo agente en el año.	18
015	25	Fila 25: El Numero no debe repetirse para el mismo agente en el año.	18
015	26	Fila 26: El Numero no debe repetirse para el mismo agente en el año.	18
015	27	Fila 27: El Numero no debe repetirse para el mismo agente en el año.	18
015	28	Fila 28: El Numero no debe repetirse para el mismo agente en el año.	18
015	29	Fila 29: El Numero no debe repetirse para el mismo agente en el año.	18
015	30	Fila 30: El Numero no debe repetirse para el mismo agente en el año.	18
015	31	Fila 31: El Numero no debe repetirse para el mismo agente en el año.	18
015	32	Fila 32: El Numero no debe repetirse para el mismo agente en el año.	18
015	33	Fila 33: El Numero no debe repetirse para el mismo agente en el año.	18
015	34	Fila 34: El Numero no debe repetirse para el mismo agente en el año.	18
015	35	Fila 35: El Numero no debe repetirse para el mismo agente en el año.	18
015	36	Fila 36: El Numero no debe repetirse para el mismo agente en el año.	18
015	37	Fila 37: El Numero no debe repetirse para el mismo agente en el año.	18
015	38	Fila 38: El Numero no debe repetirse para el mismo agente en el año.	18
015	39	Fila 39: El Numero no debe repetirse para el mismo agente en el año.	18
015	40	Fila 40: El Numero no debe repetirse para el mismo agente en el año.	18
015	41	Fila 41: El Numero no debe repetirse para el mismo agente en el año.	18
015	42	Fila 42: El Numero no debe repetirse para el mismo agente en el año.	18
015	43	Fila 43: El Numero no debe repetirse para el mismo agente en el año.	18
015	44	Fila 44: El Numero no debe repetirse para el mismo agente en el año.	18
015	45	Fila 45: El Numero no debe repetirse para el mismo agente en el año.	18
015	46	Fila 46: El Numero no debe repetirse para el mismo agente en el año.	18
015	47	Fila 47: El Numero no debe repetirse para el mismo agente en el año.	18
015	48	Fila 48: El Numero no debe repetirse para el mismo agente en el año.	18
015	49	Fila 49: El Numero no debe repetirse para el mismo agente en el año.	18
015	50	Fila 50: El Numero no debe repetirse para el mismo agente en el año.	18
015	51	Fila 51: El Numero no debe repetirse para el mismo agente en el año.	18
015	52	Fila 52: El Numero no debe repetirse para el mismo agente en el año.	18
015	53	Fila 53: El Numero no debe repetirse para el mismo agente en el año.	18
015	54	Fila 54: El Numero no debe repetirse para el mismo agente en el año.	18
015	55	Fila 55: El Numero no debe repetirse para el mismo agente en el año.	18
015	56	Fila 56: El Numero no debe repetirse para el mismo agente en el año.	18
015	57	Fila 57: El Numero no debe repetirse para el mismo agente en el año.	18
015	58	Fila 58: El Numero no debe repetirse para el mismo agente en el año.	18
015	59	Fila 59: El Numero no debe repetirse para el mismo agente en el año.	18
015	60	Fila 60: El Numero no debe repetirse para el mismo agente en el año.	18
015	61	Fila 61: El Numero no debe repetirse para el mismo agente en el año.	18
015	62	Fila 62: El Numero no debe repetirse para el mismo agente en el año.	18
015	63	Fila 63: El Numero no debe repetirse para el mismo agente en el año.	18
015	64	Fila 64: El Numero no debe repetirse para el mismo agente en el año.	18
015	65	Fila 65: El Numero no debe repetirse para el mismo agente en el año.	18
015	66	Fila 66: El Numero no debe repetirse para el mismo agente en el año.	18
015	67	Fila 67: El Numero no debe repetirse para el mismo agente en el año.	18
015	68	Fila 68: El Numero no debe repetirse para el mismo agente en el año.	18
015	69	Fila 69: El Numero no debe repetirse para el mismo agente en el año.	18
015	70	Fila 70: El Numero no debe repetirse para el mismo agente en el año.	18
015	71	Fila 71: El Numero no debe repetirse para el mismo agente en el año.	18
015	72	Fila 72: El Numero no debe repetirse para el mismo agente en el año.	18
015	73	Fila 73: El Numero no debe repetirse para el mismo agente en el año.	18
015	74	Fila 74: El Numero no debe repetirse para el mismo agente en el año.	18
015	75	Fila 75: El Numero no debe repetirse para el mismo agente en el año.	18
015	76	Fila 76: El Numero no debe repetirse para el mismo agente en el año.	18
015	77	Fila 77: El Numero no debe repetirse para el mismo agente en el año.	18
015	78	Fila 78: El Numero no debe repetirse para el mismo agente en el año.	18
015	79	Fila 79: El Numero no debe repetirse para el mismo agente en el año.	18
015	80	Fila 80: El Numero no debe repetirse para el mismo agente en el año.	18
015	81	Fila 81: El Numero no debe repetirse para el mismo agente en el año.	18
015	82	Fila 82: El Numero no debe repetirse para el mismo agente en el año.	18
015	83	Fila 83: El Numero no debe repetirse para el mismo agente en el año.	18
015	84	Fila 84: El Numero no debe repetirse para el mismo agente en el año.	18
015	85	Fila 85: El Numero no debe repetirse para el mismo agente en el año.	18
015	86	Fila 86: El Numero no debe repetirse para el mismo agente en el año.	18
015	87	Fila 87: El Numero no debe repetirse para el mismo agente en el año.	18
015	88	Fila 88: El Numero no debe repetirse para el mismo agente en el año.	18
015	89	Fila 89: El Numero no debe repetirse para el mismo agente en el año.	18
015	90	Fila 90: El Numero no debe repetirse para el mismo agente en el año.	18
015	91	Fila 91: El Numero no debe repetirse para el mismo agente en el año.	18
015	92	Fila 92: El Numero no debe repetirse para el mismo agente en el año.	18
015	93	Fila 93: El Numero no debe repetirse para el mismo agente en el año.	18
015	94	Fila 94: El Numero no debe repetirse para el mismo agente en el año.	18
015	95	Fila 95: El Numero no debe repetirse para el mismo agente en el año.	18
015	96	Fila 96: El Numero no debe repetirse para el mismo agente en el año.	18
015	97	Fila 97: El Numero no debe repetirse para el mismo agente en el año.	18
015	98	Fila 98: El Numero no debe repetirse para el mismo agente en el año.	18
015	99	Fila 99: El Numero no debe repetirse para el mismo agente en el año.	18
015	100	Fila 100: El Numero no debe repetirse para el mismo agente en el año.	18
015	101	Fila 101: El Numero no debe repetirse para el mismo agente en el año.	18
015	102	Fila 102: El Numero no debe repetirse para el mismo agente en el año.	18
015	103	Fila 103: El Numero no debe repetirse para el mismo agente en el año.	18
015	104	Fila 104: El Numero no debe repetirse para el mismo agente en el año.	18
015	105	Fila 105: El Numero no debe repetirse para el mismo agente en el año.	18
015	106	Fila 106: El Numero no debe repetirse para el mismo agente en el año.	18
015	107	Fila 107: El Numero no debe repetirse para el mismo agente en el año.	18
015	108	Fila 108: El Numero no debe repetirse para el mismo agente en el año.	18
015	109	Fila 109: El Numero no debe repetirse para el mismo agente en el año.	18
015	110	Fila 110: El Numero no debe repetirse para el mismo agente en el año.	18
015	111	Fila 111: El Numero no debe repetirse para el mismo agente en el año.	18
015	112	Fila 112: El Numero no debe repetirse para el mismo agente en el año.	18
015	113	Fila 113: El Numero no debe repetirse para el mismo agente en el año.	18
015	114	Fila 114: El Numero no debe repetirse para el mismo agente en el año.	18
015	115	Fila 115: El Numero no debe repetirse para el mismo agente en el año.	18
015	116	Fila 116: El Numero no debe repetirse para el mismo agente en el año.	18
015	117	Fila 117: El Numero no debe repetirse para el mismo agente en el año.	18
015	118	Fila 118: El Numero no debe repetirse para el mismo agente en el año.	18
015	119	Fila 119: El Numero no debe repetirse para el mismo agente en el año.	18
015	120	Fila 120: El Numero no debe repetirse para el mismo agente en el año.	18
015	121	Fila 121: El Numero no debe repetirse para el mismo agente en el año.	18
015	122	Fila 122: El Numero no debe repetirse para el mismo agente en el año.	18
015	123	Fila 123: El Numero no debe repetirse para el mismo agente en el año.	18
015	124	Fila 124: El Numero no debe repetirse para el mismo agente en el año.	18
015	125	Fila 125: El Numero no debe repetirse para el mismo agente en el año.	18
015	126	Fila 126: El Numero no debe repetirse para el mismo agente en el año.	18
015	127	Fila 127: El Numero no debe repetirse para el mismo agente en el año.	18
015	128	Fila 128: El Numero no debe repetirse para el mismo agente en el año.	18
015	129	Fila 129: El Numero no debe repetirse para el mismo agente en el año.	18
015	130	Fila 130: El Numero no debe repetirse para el mismo agente en el año.	18
015	131	Fila 131: El Numero no debe repetirse para el mismo agente en el año.	18
015	132	Fila 132: El Numero no debe repetirse para el mismo agente en el año.	18
015	133	Fila 133: El Numero no debe repetirse para el mismo agente en el año.	18
015	134	Fila 134: El Numero no debe repetirse para el mismo agente en el año.	18
015	135	Fila 135: El Numero no debe repetirse para el mismo agente en el año.	18
015	136	Fila 136: El Numero no debe repetirse para el mismo agente en el año.	18
015	137	Fila 137: El Numero no debe repetirse para el mismo agente en el año.	18
015	138	Fila 138: El Numero no debe repetirse para el mismo agente en el año.	18
015	139	Fila 139: El Numero no debe repetirse para el mismo agente en el año.	18
015	140	Fila 140: El Numero no debe repetirse para el mismo agente en el año.	18
015	141	Fila 141: El Numero no debe repetirse para el mismo agente en el año.	18
015	142	Fila 142: El Numero no debe repetirse para el mismo agente en el año.	18
015	143	Fila 143: El Numero no debe repetirse para el mismo agente en el año.	18
015	144	Fila 144: El Numero no debe repetirse para el mismo agente en el año.	18
015	145	Fila 145: El Numero no debe repetirse para el mismo agente en el año.	18
015	146	Fila 146: El Numero no debe repetirse para el mismo agente en el año.	18
015	147	Fila 147: El Numero no debe repetirse para el mismo agente en el año.	18
015	148	Fila 148: El Numero no debe repetirse para el mismo agente en el año.	18
015	149	Fila 149: El Numero no debe repetirse para el mismo agente en el año.	18
015	150	Fila 150: El Numero no debe repetirse para el mismo agente en el año.	18
015	151	Fila 151: El Numero no debe repetirse para el mismo agente en el año.	18
015	152	Fila 152: El Numero no debe repetirse para el mismo agente en el año.	18
015	153	Fila 153: El Numero no debe repetirse para el mismo agente en el año.	18
015	154	Fila 154: El Numero no debe repetirse para el mismo agente en el año.	18
015	155	Fila 155: El Numero no debe repetirse para el mismo agente en el año.	18
015	156	Fila 156: El Numero no debe repetirse para el mismo agente en el año.	18
015	157	Fila 157: El Numero no debe repetirse para el mismo agente en el año.	18
015	158	Fila 158: El Numero no debe repetirse para el mismo agente en el año.	18
015	159	Fila 159: El Numero no debe repetirse para el mismo agente en el año.	18
015	160	Fila 160: El Numero no debe repetirse para el mismo agente en el año.	18
015	161	Fila 161: El Numero no debe repetirse para el mismo agente en el año.	18
015	162	Fila 162: El Numero no debe repetirse para el mismo agente en el año.	18
015	163	Fila 163: El Numero no debe repetirse para el mismo agente en el año.	18
015	164	Fila 164: El Numero no debe repetirse para el mismo agente en el año.	18
015	165	Fila 165: El Numero no debe repetirse para el mismo agente en el año.	18
015	166	Fila 166: El Numero no debe repetirse para el mismo agente en el año.	18
015	167	Fila 167: El Numero no debe repetirse para el mismo agente en el año.	18
015	168	Fila 168: El Numero no debe repetirse para el mismo agente en el año.	18
015	169	Fila 169: El Numero no debe repetirse para el mismo agente en el año.	18
015	170	Fila 170: El Numero no debe repetirse para el mismo agente en el año.	18
015	171	Fila 171: El Numero no debe repetirse para el mismo agente en el año.	18
015	172	Fila 172: El Numero no debe repetirse para el mismo agente en el año.	18
015	173	Fila 173: El Numero no debe repetirse para el mismo agente en el año.	18
015	174	Fila 174: El Numero no debe repetirse para el mismo agente en el año.	18
015	175	Fila 175: El Numero no debe repetirse para el mismo agente en el año.	18
015	176	Fila 176: El Numero no debe repetirse para el mismo agente en el año.	18
015	177	Fila 177: El Numero no debe repetirse para el mismo agente en el año.	18
015	178	Fila 178: El Numero no debe repetirse para el mismo agente en el año.	18
015	179	Fila 179: El Numero no debe repetirse para el mismo agente en el año.	18
015	180	Fila 180: El Numero no debe repetirse para el mismo agente en el año.	18
015	181	Fila 181: El Numero no debe repetirse para el mismo agente en el año.	18
015	182	Fila 182: El Numero no debe repetirse para el mismo agente en el año.	18
015	183	Fila 183: El Numero no debe repetirse para el mismo agente en el año.	18
015	184	Fila 184: El Numero no debe repetirse para el mismo agente en el año.	18
015	185	Fila 185: El Numero no debe repetirse para el mismo agente en el año.	18
015	186	Fila 186: El Numero no debe repetirse para el mismo agente en el año.	18
015	187	Fila 187: El Numero no debe repetirse para el mismo agente en el año.	18
015	188	Fila 188: El Numero no debe repetirse para el mismo agente en el año.	18
015	189	Fila 189: El Numero no debe repetirse para el mismo agente en el año.	18
015	190	Fila 190: El Numero no debe repetirse para el mismo agente en el año.	18
015	191	Fila 191: El Numero no debe repetirse para el mismo agente en el año.	18
015	192	Fila 192: El Numero no debe repetirse para el mismo agente en el año.	18
015	193	Fila 193: El Numero no debe repetirse para el mismo agente en el año.	18
015	194	Fila 194: El Numero no debe repetirse para el mismo agente en el año.	18
015	195	Fila 195: El Numero no debe repetirse para el mismo agente en el año.	18
015	196	Fila 196: El Numero no debe repetirse para el mismo agente en el año.	18
015	197	Fila 197: El Numero no debe repetirse para el mismo agente en el año.	18
015	198	Fila 198: El Numero no debe repetirse para el mismo agente en el año.	18
015	199	Fila 199: El Numero no debe repetirse para el mismo agente en el año.	18
015	200	Fila 200: El Numero no debe repetirse para el mismo agente en el año.	18
015	201	Fila 201: El Numero no debe repetirse para el mismo agente en el año.	18
015	202	Fila 202: El Numero no debe repetirse para el mismo agente en el año.	18
015	203	Fila 203: El Numero no debe repetirse para el mismo agente en el año.	18
015	204	Fila 204: El Numero no debe repetirse para el mismo agente en el año.	18
015	205	Fila 205: El Numero no debe repetirse para el mismo agente en el año.	18
015	206	Fila 206: El Numero no debe repetirse para el mismo agente en el año.	18
015	207	Fila 207: El Numero no debe repetirse para el mismo agente en el año.	18
015	208	Fila 208: El Numero no debe repetirse para el mismo agente en el año.	18
015	209	Fila 209: El Numero no debe repetirse para el mismo agente en el año.	18
015	210	Fila 210: El Numero no debe repetirse para el mismo agente en el año.	18
015	211	Fila 211: El Numero no debe repetirse para el mismo agente en el año.	18
015	212	Fila 212: El Numero no debe repetirse para el mismo agente en el año.	18
015	213	Fila 213: El Numero no debe repetirse para el mismo agente en el año.	18
015	214	Fila 214: El Numero no debe repetirse para el mismo agente en el año.	18
015	215	Fila 215: El Numero no debe repetirse para el mismo agente en el año.	18
015	216	Fila 216: El Numero no debe repetirse para el mismo agente en el año.	18
015	217	Fila 217: El Numero no debe repetirse para el mismo agente en el año.	18
015	218	Fila 218: El Numero no debe repetirse para el mismo agente en el año.	18
015	219	Fila 219: El Numero no debe repetirse para el mismo agente en el año.	18
015	220	Fila 220: El Numero no debe repetirse para el mismo agente en el año.	18
015	221	Fila 221: El Numero no debe repetirse para el mismo agente en el año.	18
015	222	Fila 222: El Numero no debe repetirse para el mismo agente en el año.	18
015	223	Fila 223: El Numero no debe repetirse para el mismo agente en el año.	18
015	224	Fila 224: El Numero no debe repetirse para el mismo agente en el año.	18
015	225	Fila 225: El Numero no debe repetirse para el mismo agente en el año.	18
015	226	Fila 226: El Numero no debe repetirse para el mismo agente en el año.	18
015	227	Fila 227: El Numero no debe repetirse para el mismo agente en el año.	18
015	228	Fila 228: El Numero no debe repetirse para el mismo agente en el año.	18
015	229	Fila 229: El Numero no debe repetirse para el mismo agente en el año.	18
015	230	Fila 230: El Numero no debe repetirse para el mismo agente en el año.	18
015	231	Fila 231: El Numero no debe repetirse para el mismo agente en el año.	18
015	232	Fila 232: El Numero no debe repetirse para el mismo agente en el año.	18
015	233	Fila 233: El Numero no debe repetirse para el mismo agente en el año.	18
015	234	Fila 234: El Numero no debe repetirse para el mismo agente en el año.	18
015	235	Fila 235: El Numero no debe repetirse para el mismo agente en el año.	18
015	236	Fila 236: El Numero no debe repetirse para el mismo agente en el año.	18
015	237	Fila 237: El Numero no debe repetirse para el mismo agente en el año.	18
015	238	Fila 238: El Numero no debe repetirse para el mismo agente en el año.	18
015	239	Fila 239: El Numero no debe repetirse para el mismo agente en el año.	18
015	240	Fila 240: El Numero no debe repetirse para el mismo agente en el año.	18
015	241	Fila 241: El Numero no debe repetirse para el mismo agente en el año.	18
015	242	Fila 242: El Numero no debe repetirse para el mismo agente en el año.	18
015	243	Fila 243: El Numero no debe repetirse para el mismo agente en el año.	18
015	244	Fila 244: El Numero no debe repetirse para el mismo agente en el año.	18
015	245	Fila 245: El Numero no debe repetirse para el mismo agente en el año.	18
015	246	Fila 246: El Numero no debe repetirse para el mismo agente en el año.	18
015	247	Fila 247: El Numero no debe repetirse para el mismo agente en el año.	18
015	248	Fila 248: El Numero no debe repetirse para el mismo agente en el año.	18
015	249	Fila 249: El Numero no debe repetirse para el mismo agente en el año.	18
015	250	Fila 250: El Numero no debe repetirse para el mismo agente en el año.	18
015	251	Fila 251: El Numero no debe repetirse para el mismo agente en el año.	18
015	252	Fila 252: El Numero no debe repetirse para el mismo agente en el año.	18
015	253	Fila 253: El Numero no debe repetirse para el mismo agente en el año.	18
015	254	Fila 254: El Numero no debe repetirse para el mismo agente en el año.	18
015	255	Fila 255: El Numero no debe repetirse para el mismo agente en el año.	18
015	256	Fila 256: El Numero no debe repetirse para el mismo agente en el año.	18
015	257	Fila 257: El Numero no debe repetirse para el mismo agente en el año.	18
015	258	Fila 258: El Numero no debe repetirse para el mismo agente en el año.	18
015	259	Fila 259: El Numero no debe repetirse para el mismo agente en el año.	18
015	260	Fila 260: El Numero no debe repetirse para el mismo agente en el año.	18
015	261	Fila 261: El Numero no debe repetirse para el mismo agente en el año.	18
015	262	Fila 262: El Numero no debe repetirse para el mismo agente en el año.	18
015	263	Fila 263: El Numero no debe repetirse para el mismo agente en el año.	18
015	264	Fila 264: El Numero no debe repetirse para el mismo agente en el año.	18
015	265	Fila 265: El Numero no debe repetirse para el mismo agente en el año.	18
015	266	Fila 266: El Numero no debe repetirse para el mismo agente en el año.	18
015	267	Fila 267: El Numero no debe repetirse para el mismo agente en el año.	18
015	268	Fila 268: El Numero no debe repetirse para el mismo agente en el año.	18
015	269	Fila 269: El Numero no debe repetirse para el mismo agente en el año.	18
015	270	Fila 270: El Numero no debe repetirse para el mismo agente en el año.	18
015	271	Fila 271: El Numero no debe repetirse para el mismo agente en el año.	18
015	272	Fila 272: El Numero no debe repetirse para el mismo agente en el año.	18
015	273	Fila 273: El Numero no debe repetirse para el mismo agente en el año.	18
015	274	Fila 274: El Numero no debe repetirse para el mismo agente en el año.	18
015	275	Fila 275: El Numero no debe repetirse para el mismo agente en el año.	18
015	276	Fila 276: El Numero no debe repetirse para el mismo agente en el año.	18
015	277	Fila 277: El Numero no debe repetirse para el mismo agente en el año.	18
015	278	Fila 278: El Numero no debe repetirse para el mismo agente en el año.	18
015	279	Fila 279: El Numero no debe repetirse para el mismo agente en el año.	18
015	280	Fila 280: El Numero no debe repetirse para el mismo agente en el año.	18
015	281	Fila 281: El Numero no debe repetirse para el mismo agente en el año.	18
015	282	Fila 282: El Numero no debe repetirse para el mismo agente en el año.	18
015	283	Fila 283: El Numero no debe repetirse para el mismo agente en el año.	18
015	284	Fila 284: El Numero no debe repetirse para el mismo agente en el año.	18
015	285	Fila 285: El Numero no debe repetirse para el mismo agente en el año.	18
015	286	Fila 286: El Numero no debe repetirse para el mismo agente en el año.	18
015	287	Fila 287: El Numero no debe repetirse para el mismo agente en el año.	18
015	288	Fila 288: El Numero no debe repetirse para el mismo agente en el año.	18
015	289	Fila 289: El Numero no debe repetirse para el mismo agente en el año.	18
015	290	Fila 290: El Numero no debe repetirse para el mismo agente en el año.	18
015	291	Fila 291: El Numero no debe repetirse para el mismo agente en el año.	18
015	292	Fila 292: El Numero no debe repetirse para el mismo agente en el año.	18
015	293	Fila 293: El Numero no debe repetirse para el mismo agente en el año.	18
015	294	Fila 294: El Numero no debe repetirse para el mismo agente en el año.	18
015	295	Fila 295: El Numero no debe repetirse para el mismo agente en el año.	18
015	296	Fila 296: El Numero no debe repetirse para el mismo agente en el año.	18
015	297	Fila 297: El Numero no debe repetirse para el mismo agente en el año.	18
015	298	Fila 298: El Numero no debe repetirse para el mismo agente en el año.	18
015	299	Fila 299: El Numero no debe repetirse para el mismo agente en el año.	18
015	300	Fila 300: El Numero no debe repetirse para el mismo agente en el año.	18
015	301	Fila 301: El Numero no debe repetirse para el mismo agente en el año.	18
015	302	Fila 302: El Numero no debe repetirse para el mismo agente en el año.	18
015	303	Fila 303: El Numero no debe repetirse para el mismo agente en el año.	18
015	304	Fila 304: El Numero no debe repetirse para el mismo agente en el año.	18
015	305	Fila 305: El Numero no debe repetirse para el mismo agente en el año.	18
015	306	Fila 306: El Numero no debe repetirse para el mismo agente en el año.	18
015	307	Fila 307: El Numero no debe repetirse para el mismo agente en el año.	18
015	308	Fila 308: El Numero no debe repetirse para el mismo agente en el año.	18
015	309	Fila 309: El Numero no debe repetirse para el mismo agente en el año.	18
015	310	Fila 310: El Numero no debe repetirse para el mismo agente en el año.	18
015	311	Fila 311: El Numero no debe repetirse para el mismo agente en el año.	18
015	312	Fila 312: El Numero no debe repetirse para el mismo agente en el año.	18
015	313	Fila 313: El Numero no debe repetirse para el mismo agente en el año.	18
015	314	Fila 314: El Numero no debe repetirse para el mismo agente en el año.	18
015	315	Fila 315: El Numero no debe repetirse para el mismo agente en el año.	18
015	316	Fila 316: El Numero no debe repetirse para el mismo agente en el año.	18
015	317	Fila 317: El Numero no debe repetirse para el mismo agente en el año.	18
015	318	Fila 318: El Numero no debe repetirse para el mismo agente en el año.	18
015	319	Fila 319: El Numero no debe repetirse para el mismo agente en el año.	18
015	320	Fila 320: El Numero no debe repetirse para el mismo agente en el año.	18
015	321	Fila 321: El Numero no debe repetirse para el mismo agente en el año.	18
015	322	Fila 322: El Numero no debe repetirse para el mismo agente en el año.	18
015	323	Fila 323: El Numero no debe repetirse para el mismo agente en el año.	18
015	324	Fila 324: El Numero no debe repetirse para el mismo agente en el año.	18
015	325	Fila 325: El Numero no debe repetirse para el mismo agente en el año.	18
015	326	Fila 326: El Numero no debe repetirse para el mismo agente en el año.	18
015	327	Fila 327: El Numero no debe repetirse para el mismo agente en el año.	18
015	328	Fila 328: El Numero no debe repetirse para el mismo agente en el año.	18
015	329	Fila 329: El Numero no debe repetirse para el mismo agente en el año.	18
015	330	Fila 330: El Numero no debe repetirse para el mismo agente en el año.	18
015	331	Fila 331: El Numero no debe repetirse para el mismo agente en el año.	18
015	332	Fila 332: El Numero no debe repetirse para el mismo agente en el año.	18
015	333	Fila 333: El Numero no debe repetirse para el mismo agente en el año.	18
015	334	Fila 334: El Numero no debe repetirse para el mismo agente en el año.	18
015	335	Fila 335: El Numero no debe repetirse para el mismo agente en el año.	18
015	336	Fila 336: El Numero no debe repetirse para el mismo agente en el año.	18
015	337	Fila 337: El Numero no debe repetirse para el mismo agente en el año.	18
015	338	Fila 338: El Numero no debe repetirse para el mismo agente en el año.	18
015	339	Fila 339: El Numero no debe repetirse para el mismo agente en el año.	18
015	340	Fila 340: El Numero no debe repetirse para el mismo agente en el año.	18
015	341	Fila 341: El Numero no debe repetirse para el mismo agente en el año.	18
015	342	Fila 342: El Numero no debe repetirse para el mismo agente en el año.	18
015	343	Fila 343: El Numero no debe repetirse para el mismo agente en el año.	18
015	344	Fila 344: El Numero no debe repetirse para el mismo agente en el año.	18
015	345	Fila 345: El Numero no debe repetirse para el mismo agente en el año.	18
015	346	Fila 346: El Numero no debe repetirse para el mismo agente en el año.	18
015	347	Fila 347: El Numero no debe repetirse para el mismo agente en el año.	18
015	348	Fila 348: El Numero no debe repetirse para el mismo agente en el año.	18
015	349	Fila 349: El Numero no debe repetirse para el mismo agente en el año.	18
015	350	Fila 350: El Numero no debe repetirse para el mismo agente en el año.	18
015	351	Fila 351: El Numero no debe repetirse para el mismo agente en el año.	18
015	352	Fila 352: El Numero no debe repetirse para el mismo agente en el año.	18
015	353	Fila 353: El Numero no debe repetirse para el mismo agente en el año.	18
015	354	Fila 354: El Numero no debe repetirse para el mismo agente en el año.	18
015	355	Fila 355: El Numero no debe repetirse para el mismo agente en el año.	18
015	356	Fila 356: El Numero no debe repetirse para el mismo agente en el año.	18
015	357	Fila 357: El Numero no debe repetirse para el mismo agente en el año.	18
015	358	Fila 358: El Numero no debe repetirse para el mismo agente en el año.	18
015	359	Fila 359: El Numero no debe repetirse para el mismo agente en el año.	18
015	360	Fila 360: El Numero no debe repetirse para el mismo agente en el año.	18
015	361	Fila 361: El Numero no debe repetirse para el mismo agente en el año.	18
015	362	Fila 362: El Numero no debe repetirse para el mismo agente en el año.	18
015	363	Fila 363: El Numero no debe repetirse para el mismo agente en el año.	18
015	364	Fila 364: El Numero no debe repetirse para el mismo agente en el año.	18
015	365	Fila 365: El Numero no debe repetirse para el mismo agente en el año.	18
015	366	Fila 366: El Numero no debe repetirse para el mismo agente en el año.	18
015	367	Fila 367: El Numero no debe repetirse para el mismo agente en el año.	18
015	368	Fila 368: El Numero no debe repetirse para el mismo agente en el año.	18
015	369	Fila 369: El Numero no debe repetirse para el mismo agente en el año.	18
015	370	Fila 370: El Numero no debe repetirse para el mismo agente en el año.	18
015	371	Fila 371: El Numero no debe repetirse para el mismo agente en el año.	18
015	372	Fila 372: El Numero no debe repetirse para el mismo agente en el año.	18
015	373	Fila 373: El Numero no debe repetirse para el mismo agente en el año.	18
015	374	Fila 374: El Numero no debe repetirse para el mismo agente en el año.	18
015	375	Fila 375: El Numero no debe repetirse para el mismo agente en el año.	18
015	376	Fila 376: El Numero no debe repetirse para el mismo agente en el año.	18
015	377	Fila 377: El Numero no debe repetirse para el mismo agente en el año.	18
015	378	Fila 378: El Numero no debe repetirse para el mismo agente en el año.	18
015	379	Fila 379: El Numero no debe repetirse para el mismo agente en el año.	18
015	380	Fila 380: El Numero no debe repetirse para el mismo agente en el año.	18
015	381	Fila 381: El Numero no debe repetirse para el mismo agente en el año.	18
015	382	Fila 382: El Numero no debe repetirse para el mismo agente en el año.	18
015	383	Fila 383: El Numero no debe repetirse para el mismo agente en el año.	18
015	384	Fila 384: El Numero no debe repetirse para el mismo agente en el año.	18
015	385	Fila 385: El Numero no debe repetirse para el mismo agente en el año.	18
015	386	Fila 386: El Numero no debe repetirse para el mismo agente en el año.	18
015	387	Fila 387: El Numero no debe repetirse para el mismo agente en el año.	18
015	388	Fila 388: El Numero no debe repetirse para el mismo agente en el año.	18
015	389	Fila 389: El Numero no debe repetirse para el mismo agente en el año.	18
015	390	Fila 390: El Numero no debe repetirse para el mismo agente en el año.	18
015	391	Fila 391: El Numero no debe repetirse para el mismo agente en el año.	18
015	392	Fila 392: El Numero no debe repetirse para el mismo agente en el año.	18
015	393	Fila 393: El Numero no debe repetirse para el mismo agente en el año.	18
015	394	Fila 394: El Numero no debe repetirse para el mismo agente en el año.	18
015	395	Fila 395: El Numero no debe repetirse para el mismo agente en el año.	18
015	396	Fila 396: El Numero no debe repetirse para el mismo agente en el año.	18
015	397	Fila 397: El Numero no debe repetirse para el mismo agente en el año.	18
015	398	Fila 398: El Numero no debe repetirse para el mismo agente en el año.	18
015	399	Fila 399: El Numero no debe repetirse para el mismo agente en el año.	18
015	400	Fila 400: El Numero no debe repetirse para el mismo agente en el año.	18
015	401	Fila 401: El Numero no debe repetirse para el mismo agente en el año.	18
015	402	Fila 402: El Numero no debe repetirse para el mismo agente en el año.	18
015	403	Fila 403: El Numero no debe repetirse para el mismo agente en el año.	18
015	404	Fila 404: El Numero no debe repetirse para el mismo agente en el año.	18
015	405	Fila 405: El Numero no debe repetirse para el mismo agente en el año.	18
015	406	Fila 406: El Numero no debe repetirse para el mismo agente en el año.	18
015	407	Fila 407: El Numero no debe repetirse para el mismo agente en el año.	18
015	408	Fila 408: El Numero no debe repetirse para el mismo agente en el año.	18
015	409	Fila 409: El Numero no debe repetirse para el mismo agente en el año.	18
015	410	Fila 410: El Numero no debe repetirse para el mismo agente en el año.	18
015	411	Fila 411: El Numero no debe repetirse para el mismo agente en el año.	18
015	412	Fila 412: El Numero no debe repetirse para el mismo agente en el año.	18
005	0	-Es conveniente que el Número sea correlativo por año (00000001).	9
005	2	Fila 2: El Numero no debe repetirse para el mismo agente en el año.	9
005	3	Fila 3: El Numero no debe repetirse para el mismo agente en el año.	9
005	4	Fila 4: El Numero no debe repetirse para el mismo agente en el año.	9
005	5	Fila 5: El Numero no debe repetirse para el mismo agente en el año.	9
005	6	Fila 6: El Numero no debe repetirse para el mismo agente en el año.	9
005	7	Fila 7: El Numero no debe repetirse para el mismo agente en el año.	9
005	8	Fila 8: El Numero no debe repetirse para el mismo agente en el año.	9
005	9	Fila 9: El Numero no debe repetirse para el mismo agente en el año.	9
005	10	Fila 10: El Numero no debe repetirse para el mismo agente en el año.	9
005	11	Fila 11: El Numero no debe repetirse para el mismo agente en el año.	9
005	12	Fila 12: El Numero no debe repetirse para el mismo agente en el año.	9
005	13	Fila 13: El Numero no debe repetirse para el mismo agente en el año.	9
005	14	Fila 14: El Numero no debe repetirse para el mismo agente en el año.	9
005	15	Fila 15: El Numero no debe repetirse para el mismo agente en el año.	9
005	16	Fila 16: El Numero no debe repetirse para el mismo agente en el año.	9
005	17	Fila 17: El Numero no debe repetirse para el mismo agente en el año.	9
005	18	Fila 18: El Numero no debe repetirse para el mismo agente en el año.	9
005	19	Fila 19: El Numero no debe repetirse para el mismo agente en el año.	9
005	20	Fila 20: El Numero no debe repetirse para el mismo agente en el año.	9
005	21	Fila 21: El Numero no debe repetirse para el mismo agente en el año.	9
005	22	Fila 22: El Numero no debe repetirse para el mismo agente en el año.	9
005	23	Fila 23: El Numero no debe repetirse para el mismo agente en el año.	9
005	24	Fila 24: El Numero no debe repetirse para el mismo agente en el año.	9
005	25	Fila 25: El Numero no debe repetirse para el mismo agente en el año.	9
005	26	Fila 26: El Numero no debe repetirse para el mismo agente en el año.	9
001	211	Fila 211: CUIT inválido (02731607248).	5
001	212	Fila 212: CUIT inválido (02717395545).	5
001	213	Fila 213: CUIT inválido (02722271314).	5
001	214	Fila 214: CUIT inválido (02021354142).	5
001	215	Fila 215: CUIT inválido (02316317382).	5
001	216	Fila 216: CUIT inválido (03071527834).	5
001	217	Fila 217: CUIT inválido (02728019123).	5
001	218	Fila 218: CUIT inválido (02728019123).	5
001	219	Fila 219: CUIT inválido (03070904243).	5
001	220	Fila 220: CUIT inválido (02021354142).	5
001	221	Fila 221: CUIT inválido (02730974907).	5
001	224	Fila 224: CUIT inválido (02795262551).	5
001	225	Fila 225: CUIT inválido (02005413928).	5
001	226	Fila 226: CUIT inválido (02722271314).	5
001	227	Fila 227: CUIT inválido (02005413928).	5
001	228	Fila 228: CUIT inválido (02016673417).	5
001	229	Fila 229: CUIT inválido (02005413928).	5
001	230	Fila 230: CUIT inválido (02005413928).	5
001	231	Fila 231: CUIT inválido (02725975327).	5
001	235	Fila 235: CUIT inválido (02704492010).	5
001	236	Fila 236: CUIT inválido (02314162209).	5
001	238	Fila 238: CUIT inválido (02094314994).	5
001	239	Fila 239: CUIT inválido (02011885702).	5
001	239	Fila 239: El Numero no debe repetirse para el mismo agente en el año.	5
001	240	Fila 240: CUIT inválido (02723927990).	5
001	241	Fila 241: CUIT inválido (02011885702).	5
001	242	Fila 242: CUIT inválido (02716446176).	5
001	243	Fila 243: CUIT inválido (02794226956).	5
001	244	Fila 244: CUIT inválido (02729398531).	5
001	247	Fila 247: CUIT inválido (02022525047).	5
001	250	Fila 250: CUIT inválido (02011885702).	5
001	253	Fila 253: CUIT inválido (02723927990).	5
001	254	Fila 254: CUIT inválido (02022525047).	5
001	255	Fila 255: CUIT inválido (02007331687).	5
001	255	Fila 255: El Numero no debe repetirse para el mismo agente en el año.	5
001	256	Fila 256: CUIT inválido (02028464236).	5
001	257	Fila 257: CUIT inválido (02704492010).	5
001	258	Fila 258: CUIT inválido (02011885702).	5
001	259	Fila 259: CUIT inválido (02011885702).	5
001	261	Fila 261: CUIT inválido (02028464236).	5
001	262	Fila 262: CUIT inválido (02025082746).	5
001	263	Fila 263: CUIT inválido (02025082746).	5
001	264	Fila 264: CUIT inválido (02034665318).	5
001	265	Fila 265: CUIT inválido (02025082746).	5
001	266	Fila 266: CUIT inválido (02034665318).	5
001	267	Fila 267: CUIT inválido (02723927990).	5
001	269	Fila 269: CUIT inválido (02025082746).	5
001	270	Fila 270: CUIT inválido (02094314994).	5
001	271	Fila 271: CUIT inválido (02017395544).	5
001	272	Fila 272: CUIT inválido (02721927343).	5
001	273	Fila 273: CUIT inválido (02732545346).	5
001	274	Fila 274: CUIT inválido (02034665318).	5
001	275	Fila 275: CUIT inválido (02729398531).	5
001	276	Fila 276: CUIT inválido (02034665318).	5
001	277	Fila 277: CUIT inválido (02014983351).	5
001	278	Fila 278: CUIT inválido (02736320664).	5
001	279	Fila 279: CUIT inválido (02025082746).	5
001	280	Fila 280: CUIT inválido (02731653948).	5
001	283	Fila 283: CUIT inválido (02736320664).	5
001	283	Fila 283: El Numero no debe repetirse para el mismo agente en el año.	5
001	284	Fila 284: CUIT inválido (02014983351).	5
001	285	Fila 285: CUIT inválido (02028464236).	5
001	286	Fila 286: CUIT inválido (02011885702).	5
001	288	Fila 288: CUIT inválido (02724664522).	5
001	289	Fila 289: CUIT inválido (02723621663).	5
005	27	Fila 27: CUIT inválido (27219274311).	9
005	27	Fila 27: El Numero no debe repetirse para el mismo agente en el año.	9
001	0	-Es conveniente que el Número sea correlativo por año (00020244).	5
001	2	Fila 2: CUIT inválido (03070904243).	5
001	3	Fila 3: CUIT inválido (02005413928).	5
001	4	Fila 4: CUIT inválido (02021354142).	5
001	4	Fila 4: El Numero no debe repetirse para el mismo agente en el año.	5
001	5	Fila 5: CUIT inválido (03070904243).	5
001	5	Fila 5: El Numero no debe repetirse para el mismo agente en el año.	5
001	6	Fila 6: CUIT inválido (02712594470).	5
001	6	Fila 6: El Numero no debe repetirse para el mismo agente en el año.	5
001	7	Fila 7: CUIT inválido (03070904243).	5
001	8	Fila 8: CUIT inválido (02795262551).	5
001	9	Fila 9: CUIT inválido (02706435080).	5
008	2	Fila 2: El Numero no debe repetirse para el mismo agente en el año.	12
008	3	Fila 3: El Numero no debe repetirse para el mismo agente en el año.	12
008	4	Fila 4: El Numero no debe repetirse para el mismo agente en el año.	12
008	5	Fila 5: El Numero no debe repetirse para el mismo agente en el año.	12
008	6	Fila 6: El Numero no debe repetirse para el mismo agente en el año.	12
008	7	Fila 7: El Numero no debe repetirse para el mismo agente en el año.	12
008	8	Fila 8: El Numero no debe repetirse para el mismo agente en el año.	12
008	9	Fila 9: El Numero no debe repetirse para el mismo agente en el año.	12
008	10	Fila 10: El Numero no debe repetirse para el mismo agente en el año.	12
008	11	Fila 11: El Numero no debe repetirse para el mismo agente en el año.	12
008	12	Fila 12: El Numero no debe repetirse para el mismo agente en el año.	12
008	13	Fila 13: El Numero no debe repetirse para el mismo agente en el año.	12
008	14	Fila 14: El Numero no debe repetirse para el mismo agente en el año.	12
008	15	Fila 15: El Numero no debe repetirse para el mismo agente en el año.	12
008	16	Fila 16: El Numero no debe repetirse para el mismo agente en el año.	12
008	17	Fila 17: El Numero no debe repetirse para el mismo agente en el año.	12
008	18	Fila 18: El Numero no debe repetirse para el mismo agente en el año.	12
008	19	Fila 19: El Numero no debe repetirse para el mismo agente en el año.	12
008	20	Fila 20: El Numero no debe repetirse para el mismo agente en el año.	12
008	21	Fila 21: El Numero no debe repetirse para el mismo agente en el año.	12
008	22	Fila 22: El Numero no debe repetirse para el mismo agente en el año.	12
008	23	Fila 23: El Numero no debe repetirse para el mismo agente en el año.	12
008	24	Fila 24: El Numero no debe repetirse para el mismo agente en el año.	12
008	25	Fila 25: El Numero no debe repetirse para el mismo agente en el año.	12
008	26	Fila 26: El Numero no debe repetirse para el mismo agente en el año.	12
001	10	Fila 10: CUIT inválido (03070904243).	5
001	10	Fila 10: El Numero no debe repetirse para el mismo agente en el año.	5
001	11	Fila 11: CUIT inválido (02731607248).	5
001	12	Fila 12: CUIT inválido (03070904243).	5
001	13	Fila 13: CUIT inválido (02005413928).	5
001	16	Fila 16: CUIT inválido (03070904243).	5
001	17	Fila 17: CUIT inválido (02022201457).	5
001	17	Fila 17: El Numero no debe repetirse para el mismo agente en el año.	5
001	18	Fila 18: CUIT inválido (02022201457).	5
001	19	Fila 19: CUIT inválido (02022201457).	5
001	20	Fila 20: CUIT inválido (02014983351).	5
001	21	Fila 21: CUIT inválido (02034665318).	5
001	21	Fila 21: El Numero no debe repetirse para el mismo agente en el año.	5
001	23	Fila 23: CUIT inválido (02721927343).	5
001	24	Fila 24: CUIT inválido (02094314994).	5
001	25	Fila 25: CUIT inválido (02314162209).	5
001	26	Fila 26: CUIT inválido (02704492010).	5
001	28	Fila 28: El Numero no debe repetirse para el mismo agente en el año.	5
001	29	Fila 29: CUIT inválido (02094314994).	5
001	30	Fila 30: CUIT inválido (02011885702).	5
001	31	Fila 31: CUIT inválido (02722271314).	5
001	32	Fila 32: CUIT inválido (02005413928).	5
001	33	Fila 33: CUIT inválido (02706435080).	5
001	34	Fila 34: CUIT inválido (02728019123).	5
001	35	Fila 35: CUIT inválido (02007326180).	5
001	36	Fila 36: CUIT inválido (03070904243).	5
001	37	Fila 37: CUIT inválido (02324121634).	5
001	38	Fila 38: CUIT inválido (02705660356).	5
001	39	Fila 39: CUIT inválido (02722271314).	5
001	40	Fila 40: CUIT inválido (02021354142).	5
001	41	Fila 41: CUIT inválido (02710028181).	5
001	42	Fila 42: CUIT inválido (02022201457).	5
001	43	Fila 43: CUIT inválido (02022201457).	5
001	44	Fila 44: CUIT inválido (02731607248).	5
001	45	Fila 45: CUIT inválido (02732545346).	5
001	46	Fila 46: CUIT inválido (02094314994).	5
001	47	Fila 47: CUIT inválido (02729772292).	5
001	48	Fila 48: CUIT inválido (02022525047).	5
001	49	Fila 49: CUIT inválido (02704492010).	5
001	51	Fila 51: CUIT inválido (02022525047).	5
001	52	Fila 52: CUIT inválido (02736320664).	5
001	54	Fila 54: CUIT inválido (02017395544).	5
001	55	Fila 55: CUIT inválido (02732545346).	5
001	56	Fila 56: CUIT inválido (02021518189).	5
001	57	Fila 57: CUIT inválido (02005413928).	5
001	58	Fila 58: CUIT inválido (02005413928).	5
001	59	Fila 59: CUIT inválido (02005413928).	5
001	60	Fila 60: CUIT inválido (02324121634).	5
001	61	Fila 61: CUIT inválido (02324121634).	5
001	62	Fila 62: CUIT inválido (02712594470).	5
001	63	Fila 63: CUIT inválido (02706435080).	5
001	64	Fila 64: CUIT inválido (02716318473).	5
001	65	Fila 65: CUIT inválido (02007326180).	5
001	67	Fila 67: CUIT inválido (02005413928).	5
001	68	Fila 68: CUIT inválido (02005413928).	5
001	69	Fila 69: CUIT inválido (03070904243).	5
001	70	Fila 70: CUIT inválido (03070904243).	5
001	71	Fila 71: CUIT inválido (02005413928).	5
001	72	Fila 72: CUIT inválido (03070904243).	5
001	73	Fila 73: CUIT inválido (02730974907).	5
001	74	Fila 74: CUIT inválido (03071527834).	5
001	75	Fila 75: CUIT inválido (02324121726).	5
001	76	Fila 76: CUIT inválido (02710028181).	5
001	77	Fila 77: CUIT inválido (02021354142).	5
001	78	Fila 78: CUIT inválido (02722509257).	5
001	79	Fila 79: CUIT inválido (03070904243).	5
001	80	Fila 80: CUIT inválido (02021354142).	5
001	81	Fila 81: CUIT inválido (02005413928).	5
001	82	Fila 82: CUIT inválido (02021354142).	5
001	83	Fila 83: CUIT inválido (02730974907).	5
001	84	Fila 84: CUIT inválido (03070904243).	5
001	85	Fila 85: CUIT inválido (03070904243).	5
001	86	Fila 86: CUIT inválido (02795262551).	5
001	87	Fila 87: CUIT inválido (03070904243).	5
001	88	Fila 88: CUIT inválido (02005413928).	5
001	89	Fila 89: CUIT inválido (02705660356).	5
001	90	Fila 90: CUIT inválido (03070904243).	5
001	91	Fila 91: CUIT inválido (02705660356).	5
001	92	Fila 92: CUIT inválido (03070904243).	5
001	93	Fila 93: CUIT inválido (03071527834).	5
001	95	Fila 95: CUIT inválido (02005413928).	5
001	97	Fila 97: CUIT inválido (03071527834).	5
001	98	Fila 98: CUIT inválido (02716318473).	5
001	99	Fila 99: CUIT inválido (03070904243).	5
001	100	Fila 100: CUIT inválido (02005413928).	5
001	101	Fila 101: CUIT inválido (02005413928).	5
001	102	Fila 102: CUIT inválido (02005413928).	5
001	103	Fila 103: CUIT inválido (02706435080).	5
001	104	Fila 104: CUIT inválido (02722271314).	5
001	105	Fila 105: CUIT inválido (02795262551).	5
001	106	Fila 106: CUIT inválido (03071527834).	5
001	107	Fila 107: CUIT inválido (02005413928).	5
001	113	Fila 113: CUIT inválido (02016673417).	5
001	114	Fila 114: CUIT inválido (02016673417).	5
001	115	Fila 115: CUIT inválido (02021354142).	5
001	116	Fila 116: CUIT inválido (02706435080).	5
001	117	Fila 117: CUIT inválido (02016318459).	5
001	120	Fila 120: CUIT inválido (02324121634).	5
001	121	Fila 121: CUIT inválido (03070904243).	5
001	122	Fila 122: CUIT inválido (02716318473).	5
001	123	Fila 123: CUIT inválido (02795262551).	5
001	124	Fila 124: CUIT inválido (02722271314).	5
001	125	Fila 125: CUIT inválido (02795262551).	5
001	126	Fila 126: CUIT inválido (03071527834).	5
001	127	Fila 127: CUIT inválido (02005413928).	5
001	128	Fila 128: CUIT inválido (02731607248).	5
001	129	Fila 129: CUIT inválido (02795262551).	5
001	130	Fila 130: CUIT inválido (02795262551).	5
001	131	Fila 131: CUIT inválido (02022201457).	5
001	132	Fila 132: CUIT inválido (02722271314).	5
001	133	Fila 133: CUIT inválido (02795262551).	5
001	134	Fila 134: CUIT inválido (02795262551).	5
001	135	Fila 135: CUIT inválido (02730974907).	5
001	136	Fila 136: CUIT inválido (02705660356).	5
001	137	Fila 137: CUIT inválido (02728019123).	5
001	139	Fila 139: CUIT inválido (02021354142).	5
001	140	Fila 140: CUIT inválido (03067036231).	5
001	141	Fila 141: CUIT inválido (02005413928).	5
001	142	Fila 142: CUIT inválido (02005413928).	5
001	143	Fila 143: CUIT inválido (02005413928).	5
001	144	Fila 144: CUIT inválido (02324121634).	5
001	145	Fila 145: CUIT inválido (03070904243).	5
001	146	Fila 146: CUIT inválido (02722271314).	5
001	147	Fila 147: CUIT inválido (03070904243).	5
001	149	Fila 149: CUIT inválido (02795262551).	5
001	150	Fila 150: CUIT inválido (02728019123).	5
001	151	Fila 151: CUIT inválido (02795262551).	5
001	152	Fila 152: CUIT inválido (02706435080).	5
001	153	Fila 153: CUIT inválido (02706435080).	5
001	154	Fila 154: CUIT inválido (02016673417).	5
001	155	Fila 155: CUIT inválido (02716318473).	5
001	157	Fila 157: CUIT inválido (03071527834).	5
001	158	Fila 158: CUIT inválido (03070904243).	5
001	159	Fila 159: CUIT inválido (03071527834).	5
001	160	Fila 160: CUIT inválido (02016673417).	5
001	161	Fila 161: CUIT inválido (03070904243).	5
001	163	Fila 163: CUIT inválido (03071527834).	5
001	164	Fila 164: CUIT inválido (02017395574).	5
001	165	Fila 165: CUIT inválido (03070904243).	5
001	166	Fila 166: CUIT inválido (02795262551).	5
001	167	Fila 167: CUIT inválido (02795262551).	5
001	168	Fila 168: CUIT inválido (03071527834).	5
001	169	Fila 169: CUIT inválido (02324121634).	5
001	171	Fila 171: CUIT inválido (02731607248).	5
001	172	Fila 172: CUIT inválido (02730974907).	5
001	173	Fila 173: CUIT inválido (02021354142).	5
001	174	Fila 174: CUIT inválido (03070904243).	5
001	175	Fila 175: CUIT inválido (03070904243).	5
001	176	Fila 176: CUIT inválido (02016673417).	5
005	28	Fila 28: El Numero no debe repetirse para el mismo agente en el año.	9
005	29	Fila 29: El Numero no debe repetirse para el mismo agente en el año.	9
005	30	Fila 30: El Numero no debe repetirse para el mismo agente en el año.	9
005	31	Fila 31: El Numero no debe repetirse para el mismo agente en el año.	9
005	32	Fila 32: El Numero no debe repetirse para el mismo agente en el año.	9
005	33	Fila 33: El Numero no debe repetirse para el mismo agente en el año.	9
005	34	Fila 34: El Numero no debe repetirse para el mismo agente en el año.	9
005	35	Fila 35: El Numero no debe repetirse para el mismo agente en el año.	9
005	36	Fila 36: El Numero no debe repetirse para el mismo agente en el año.	9
005	37	Fila 37: El Numero no debe repetirse para el mismo agente en el año.	9
005	38	Fila 38: El Numero no debe repetirse para el mismo agente en el año.	9
005	39	Fila 39: El Numero no debe repetirse para el mismo agente en el año.	9
005	40	Fila 40: El Numero no debe repetirse para el mismo agente en el año.	9
005	41	Fila 41: El Numero no debe repetirse para el mismo agente en el año.	9
005	42	Fila 42: El Numero no debe repetirse para el mismo agente en el año.	9
005	43	Fila 43: El Numero no debe repetirse para el mismo agente en el año.	9
005	44	Fila 44: El Numero no debe repetirse para el mismo agente en el año.	9
005	45	Fila 45: El Numero no debe repetirse para el mismo agente en el año.	9
005	46	Fila 46: El Numero no debe repetirse para el mismo agente en el año.	9
001	177	Fila 177: CUIT inválido (02725975327).	5
001	178	Fila 178: CUIT inválido (02021354142).	5
001	179	Fila 179: CUIT inválido (03070904243).	5
001	180	Fila 180: CUIT inválido (02712594470).	5
001	182	Fila 182: CUIT inválido (02795262551).	5
001	184	Fila 184: CUIT inválido (03067036460).	5
001	185	Fila 185: CUIT inválido (02795262551).	5
001	186	Fila 186: CUIT inválido (02795262551).	5
001	187	Fila 187: CUIT inválido (02725975327).	5
001	188	Fila 188: CUIT inválido (02016673417).	5
001	189	Fila 189: CUIT inválido (02731607248).	5
001	190	Fila 190: CUIT inválido (03070904243).	5
001	191	Fila 191: CUIT inválido (02722271314).	5
001	192	Fila 192: CUIT inválido (02016673417).	5
001	193	Fila 193: CUIT inválido (03070904243).	5
001	194	Fila 194: CUIT inválido (02795262551).	5
001	195	Fila 195: CUIT inválido (03070904243).	5
001	196	Fila 196: CUIT inválido (02706435080).	5
001	198	Fila 198: CUIT inválido (02712594470).	5
001	199	Fila 199: CUIT inválido (02795262551).	5
001	200	Fila 200: CUIT inválido (02712594470).	5
001	201	Fila 201: CUIT inválido (03070904243).	5
001	202	Fila 202: CUIT inválido (02722271314).	5
001	203	Fila 203: CUIT inválido (02731607248).	5
001	204	Fila 204: CUIT inválido (02728019123).	5
001	205	Fila 205: CUIT inválido (02731607248).	5
001	206	Fila 206: CUIT inválido (02722271314).	5
001	207	Fila 207: CUIT inválido (02324121726).	5
001	208	Fila 208: CUIT inválido (02005413928).	5
001	209	Fila 209: CUIT inválido (02706435080).	5
001	210	Fila 210: CUIT inválido (02705660356).	5
001	290	Fila 290: CUIT inválido (02011885702).	5
001	291	Fila 291: El Numero no debe repetirse para el mismo agente en el año.	5
001	292	Fila 292: El Numero no debe repetirse para el mismo agente en el año.	5
001	293	Fila 293: CUIT inválido (02094314994).	5
001	294	Fila 294: CUIT inválido (02729398531).	5
001	294	Fila 294: El Numero no debe repetirse para el mismo agente en el año.	5
001	295	Fila 295: CUIT inválido (02724664522).	5
001	296	Fila 296: CUIT inválido (02028464236).	5
001	297	Fila 297: CUIT inválido (02017395544).	5
001	298	Fila 298: CUIT inválido (02704492010).	5
001	298	Fila 298: El Numero no debe repetirse para el mismo agente en el año.	5
001	299	Fila 299: CUIT inválido (02014983351).	5
001	300	Fila 300: CUIT inválido (02794226956).	5
001	301	Fila 301: CUIT inválido (02723621663).	5
001	302	Fila 302: CUIT inválido (02017395544).	5
001	304	Fila 304: CUIT inválido (02011885702).	5
001	306	Fila 306: CUIT inválido (02034665318).	5
001	306	Fila 306: El Numero no debe repetirse para el mismo agente en el año.	5
001	307	Fila 307: CUIT inválido (02724664522).	5
001	308	Fila 308: CUIT inválido (02017395544).	5
001	309	Fila 309: CUIT inválido (02729772292).	5
001	310	Fila 310: CUIT inválido (02723621663).	5
001	311	Fila 311: CUIT inválido (02794226956).	5
001	312	Fila 312: CUIT inválido (02335320490).	5
001	313	Fila 313: CUIT inválido (02011885702).	5
001	314	Fila 314: CUIT inválido (02094314994).	5
001	315	Fila 315: El Numero no debe repetirse para el mismo agente en el año.	5
001	316	Fila 316: El Numero no debe repetirse para el mismo agente en el año.	5
001	317	Fila 317: CUIT inválido (02034665318).	5
001	319	Fila 319: CUIT inválido (02794226956).	5
001	321	Fila 321: CUIT inválido (02028464236).	5
001	322	Fila 322: CUIT inválido (02731653948).	5
001	324	Fila 324: CUIT inválido (02729398531).	5
001	325	Fila 325: CUIT inválido (02721927343).	5
001	327	Fila 327: CUIT inválido (02017395544).	5
001	328	Fila 328: CUIT inválido (02731653948).	5
001	329	Fila 329: CUIT inválido (02005409989).	5
001	330	Fila 330: CUIT inválido (02723927990).	5
001	331	Fila 331: El Numero no debe repetirse para el mismo agente en el año.	5
001	332	Fila 332: CUIT inválido (02034665318).	5
001	333	Fila 333: CUIT inválido (02731653948).	5
001	334	Fila 334: CUIT inválido (02011885702).	5
001	335	Fila 335: CUIT inválido (02722680323).	5
001	336	Fila 336: CUIT inválido (02021518189).	5
001	337	Fila 337: CUIT inválido (02094314994).	5
001	338	Fila 338: CUIT inválido (02011885702).	5
001	342	Fila 342: CUIT inválido (02017395544).	5
001	343	Fila 343: El Numero no debe repetirse para el mismo agente en el año.	5
001	344	Fila 344: CUIT inválido (02094081864).	5
001	345	Fila 345: CUIT inválido (02723927990).	5
001	345	Fila 345: El Numero no debe repetirse para el mismo agente en el año.	5
001	347	Fila 347: CUIT inválido (02011885702).	5
001	347	Fila 347: El Numero no debe repetirse para el mismo agente en el año.	5
001	348	Fila 348: CUIT inválido (02314162209).	5
001	350	Fila 350: CUIT inválido (02704492010).	5
001	350	Fila 350: El Numero no debe repetirse para el mismo agente en el año.	5
001	351	Fila 351: CUIT inválido (02028464236).	5
001	352	Fila 352: CUIT inválido (02732545346).	5
001	353	Fila 353: CUIT inválido (02724664522).	5
001	354	Fila 354: CUIT inválido (02014983351).	5
001	355	Fila 355: CUIT inválido (02723621663).	5
001	356	Fila 356: CUIT inválido (02005409989).	5
001	357	Fila 357: CUIT inválido (02017395544).	5
001	357	Fila 357: El Numero no debe repetirse para el mismo agente en el año.	5
001	358	Fila 358: CUIT inválido (02794226956).	5
001	358	Fila 358: El Numero no debe repetirse para el mismo agente en el año.	5
001	359	Fila 359: CUIT inválido (02014983351).	5
001	360	Fila 360: CUIT inválido (02736320664).	5
001	360	Fila 360: El Numero no debe repetirse para el mismo agente en el año.	5
001	361	Fila 361: CUIT inválido (02736320664).	5
001	362	Fila 362: CUIT inválido (02314162209).	5
001	364	Fila 364: CUIT inválido (02314162209).	5
001	364	Fila 364: El Numero no debe repetirse para el mismo agente en el año.	5
001	366	Fila 366: CUIT inválido (02729398531).	5
001	367	Fila 367: CUIT inválido (02335320490).	5
001	367	Fila 367: El Numero no debe repetirse para el mismo agente en el año.	5
005	47	Fila 47: El Numero no debe repetirse para el mismo agente en el año.	9
005	48	Fila 48: El Numero no debe repetirse para el mismo agente en el año.	9
005	49	Fila 49: El Numero no debe repetirse para el mismo agente en el año.	9
005	50	Fila 50: El Numero no debe repetirse para el mismo agente en el año.	9
005	51	Fila 51: El Numero no debe repetirse para el mismo agente en el año.	9
005	52	Fila 52: El Numero no debe repetirse para el mismo agente en el año.	9
005	53	Fila 53: El Numero no debe repetirse para el mismo agente en el año.	9
005	54	Fila 54: El Numero no debe repetirse para el mismo agente en el año.	9
005	55	Fila 55: El Numero no debe repetirse para el mismo agente en el año.	9
005	56	Fila 56: El Numero no debe repetirse para el mismo agente en el año.	9
005	57	Fila 57: CUIT inválido (27219274311).	9
005	57	Fila 57: El Numero no debe repetirse para el mismo agente en el año.	9
005	58	Fila 58: El Numero no debe repetirse para el mismo agente en el año.	9
005	59	Fila 59: El Numero no debe repetirse para el mismo agente en el año.	9
005	60	Fila 60: El Numero no debe repetirse para el mismo agente en el año.	9
005	61	Fila 61: El Numero no debe repetirse para el mismo agente en el año.	9
005	62	Fila 62: El Numero no debe repetirse para el mismo agente en el año.	9
005	63	Fila 63: El Numero no debe repetirse para el mismo agente en el año.	9
005	64	Fila 64: El Numero no debe repetirse para el mismo agente en el año.	9
005	65	Fila 65: El Numero no debe repetirse para el mismo agente en el año.	9
005	66	Fila 66: El Numero no debe repetirse para el mismo agente en el año.	9
005	67	Fila 67: El Numero no debe repetirse para el mismo agente en el año.	9
005	68	Fila 68: CUIT inválido (20940818690).	9
005	68	Fila 68: El Numero no debe repetirse para el mismo agente en el año.	9
005	69	Fila 69: El Numero no debe repetirse para el mismo agente en el año.	9
005	70	Fila 70: El Numero no debe repetirse para el mismo agente en el año.	9
005	71	Fila 71: El Numero no debe repetirse para el mismo agente en el año.	9
005	72	Fila 72: El Numero no debe repetirse para el mismo agente en el año.	9
005	73	Fila 73: El Numero no debe repetirse para el mismo agente en el año.	9
005	74	Fila 74: El Numero no debe repetirse para el mismo agente en el año.	9
005	75	Fila 75: El Numero no debe repetirse para el mismo agente en el año.	9
005	76	Fila 76: El Numero no debe repetirse para el mismo agente en el año.	9
005	77	Fila 77: El Numero no debe repetirse para el mismo agente en el año.	9
005	78	Fila 78: El Numero no debe repetirse para el mismo agente en el año.	9
005	79	Fila 79: El Numero no debe repetirse para el mismo agente en el año.	9
005	80	Fila 80: El Numero no debe repetirse para el mismo agente en el año.	9
005	81	Fila 81: El Numero no debe repetirse para el mismo agente en el año.	9
005	82	Fila 82: El Numero no debe repetirse para el mismo agente en el año.	9
005	83	Fila 83: El Numero no debe repetirse para el mismo agente en el año.	9
005	84	Fila 84: El Numero no debe repetirse para el mismo agente en el año.	9
005	85	Fila 85: El Numero no debe repetirse para el mismo agente en el año.	9
005	86	Fila 86: El Numero no debe repetirse para el mismo agente en el año.	9
005	87	Fila 87: CUIT inválido (27219274311).	9
005	87	Fila 87: El Numero no debe repetirse para el mismo agente en el año.	9
005	88	Fila 88: CUIT inválido (27219274311).	9
005	88	Fila 88: El Numero no debe repetirse para el mismo agente en el año.	9
005	89	Fila 89: El Numero no debe repetirse para el mismo agente en el año.	9
005	90	Fila 90: El Numero no debe repetirse para el mismo agente en el año.	9
005	91	Fila 91: El Numero no debe repetirse para el mismo agente en el año.	9
005	92	Fila 92: El Numero no debe repetirse para el mismo agente en el año.	9
005	93	Fila 93: El Numero no debe repetirse para el mismo agente en el año.	9
005	94	Fila 94: El Numero no debe repetirse para el mismo agente en el año.	9
005	95	Fila 95: El Numero no debe repetirse para el mismo agente en el año.	9
005	96	Fila 96: El Numero no debe repetirse para el mismo agente en el año.	9
005	97	Fila 97: El Numero no debe repetirse para el mismo agente en el año.	9
005	98	Fila 98: El Numero no debe repetirse para el mismo agente en el año.	9
005	99	Fila 99: El Numero no debe repetirse para el mismo agente en el año.	9
005	100	Fila 100: El Numero no debe repetirse para el mismo agente en el año.	9
005	101	Fila 101: El Numero no debe repetirse para el mismo agente en el año.	9
005	102	Fila 102: El Numero no debe repetirse para el mismo agente en el año.	9
005	103	Fila 103: El Numero no debe repetirse para el mismo agente en el año.	9
005	104	Fila 104: El Numero no debe repetirse para el mismo agente en el año.	9
005	105	Fila 105: El Numero no debe repetirse para el mismo agente en el año.	9
005	106	Fila 106: El Numero no debe repetirse para el mismo agente en el año.	9
005	107	Fila 107: El Numero no debe repetirse para el mismo agente en el año.	9
005	108	Fila 108: El Numero no debe repetirse para el mismo agente en el año.	9
005	109	Fila 109: El Numero no debe repetirse para el mismo agente en el año.	9
005	110	Fila 110: El Numero no debe repetirse para el mismo agente en el año.	9
005	111	Fila 111: El Numero no debe repetirse para el mismo agente en el año.	9
005	112	Fila 112: El Numero no debe repetirse para el mismo agente en el año.	9
005	113	Fila 113: El Numero no debe repetirse para el mismo agente en el año.	9
005	114	Fila 114: El Numero no debe repetirse para el mismo agente en el año.	9
005	115	Fila 115: El Numero no debe repetirse para el mismo agente en el año.	9
005	116	Fila 116: El Numero no debe repetirse para el mismo agente en el año.	9
005	117	Fila 117: El Numero no debe repetirse para el mismo agente en el año.	9
005	118	Fila 118: El Numero no debe repetirse para el mismo agente en el año.	9
005	119	Fila 119: El Numero no debe repetirse para el mismo agente en el año.	9
005	120	Fila 120: El Numero no debe repetirse para el mismo agente en el año.	9
005	121	Fila 121: El Numero no debe repetirse para el mismo agente en el año.	9
005	122	Fila 122: El Numero no debe repetirse para el mismo agente en el año.	9
005	123	Fila 123: El Numero no debe repetirse para el mismo agente en el año.	9
005	124	Fila 124: El Numero no debe repetirse para el mismo agente en el año.	9
005	125	Fila 125: El Numero no debe repetirse para el mismo agente en el año.	9
005	126	Fila 126: El Numero no debe repetirse para el mismo agente en el año.	9
005	127	Fila 127: El Numero no debe repetirse para el mismo agente en el año.	9
005	128	Fila 128: El Numero no debe repetirse para el mismo agente en el año.	9
005	129	Fila 129: El Numero no debe repetirse para el mismo agente en el año.	9
005	130	Fila 130: El Numero no debe repetirse para el mismo agente en el año.	9
005	131	Fila 131: El Numero no debe repetirse para el mismo agente en el año.	9
005	132	Fila 132: El Numero no debe repetirse para el mismo agente en el año.	9
005	133	Fila 133: El Numero no debe repetirse para el mismo agente en el año.	9
005	134	Fila 134: El Numero no debe repetirse para el mismo agente en el año.	9
005	135	Fila 135: El Numero no debe repetirse para el mismo agente en el año.	9
005	136	Fila 136: El Numero no debe repetirse para el mismo agente en el año.	9
005	137	Fila 137: El Numero no debe repetirse para el mismo agente en el año.	9
005	138	Fila 138: CUIT inválido (27219274311).	9
005	138	Fila 138: El Numero no debe repetirse para el mismo agente en el año.	9
005	139	Fila 139: El Numero no debe repetirse para el mismo agente en el año.	9
005	140	Fila 140: El Numero no debe repetirse para el mismo agente en el año.	9
005	141	Fila 141: El Numero no debe repetirse para el mismo agente en el año.	9
005	142	Fila 142: El Numero no debe repetirse para el mismo agente en el año.	9
005	143	Fila 143: El Numero no debe repetirse para el mismo agente en el año.	9
005	144	Fila 144: El Numero no debe repetirse para el mismo agente en el año.	9
005	145	Fila 145: El Numero no debe repetirse para el mismo agente en el año.	9
005	146	Fila 146: El Numero no debe repetirse para el mismo agente en el año.	9
005	147	Fila 147: El Numero no debe repetirse para el mismo agente en el año.	9
005	148	Fila 148: El Numero no debe repetirse para el mismo agente en el año.	9
005	149	Fila 149: El Numero no debe repetirse para el mismo agente en el año.	9
005	150	Fila 150: El Numero no debe repetirse para el mismo agente en el año.	9
005	151	Fila 151: El Numero no debe repetirse para el mismo agente en el año.	9
005	152	Fila 152: El Numero no debe repetirse para el mismo agente en el año.	9
005	153	Fila 153: El Numero no debe repetirse para el mismo agente en el año.	9
005	154	Fila 154: El Numero no debe repetirse para el mismo agente en el año.	9
005	155	Fila 155: El Numero no debe repetirse para el mismo agente en el año.	9
005	156	Fila 156: El Numero no debe repetirse para el mismo agente en el año.	9
005	157	Fila 157: El Numero no debe repetirse para el mismo agente en el año.	9
005	158	Fila 158: El Numero no debe repetirse para el mismo agente en el año.	9
005	159	Fila 159: El Numero no debe repetirse para el mismo agente en el año.	9
005	160	Fila 160: El Numero no debe repetirse para el mismo agente en el año.	9
005	161	Fila 161: El Numero no debe repetirse para el mismo agente en el año.	9
005	162	Fila 162: El Numero no debe repetirse para el mismo agente en el año.	9
005	163	Fila 163: El Numero no debe repetirse para el mismo agente en el año.	9
005	164	Fila 164: El Numero no debe repetirse para el mismo agente en el año.	9
005	165	Fila 165: El Numero no debe repetirse para el mismo agente en el año.	9
005	166	Fila 166: El Numero no debe repetirse para el mismo agente en el año.	9
005	167	Fila 167: El Numero no debe repetirse para el mismo agente en el año.	9
005	168	Fila 168: El Numero no debe repetirse para el mismo agente en el año.	9
005	169	Fila 169: El Numero no debe repetirse para el mismo agente en el año.	9
005	170	Fila 170: El Numero no debe repetirse para el mismo agente en el año.	9
005	171	Fila 171: El Numero no debe repetirse para el mismo agente en el año.	9
005	172	Fila 172: El Numero no debe repetirse para el mismo agente en el año.	9
005	173	Fila 173: El Numero no debe repetirse para el mismo agente en el año.	9
005	174	Fila 174: El Numero no debe repetirse para el mismo agente en el año.	9
005	175	Fila 175: El Numero no debe repetirse para el mismo agente en el año.	9
005	176	Fila 176: El Numero no debe repetirse para el mismo agente en el año.	9
005	177	Fila 177: El Numero no debe repetirse para el mismo agente en el año.	9
005	178	Fila 178: El Numero no debe repetirse para el mismo agente en el año.	9
005	179	Fila 179: El Numero no debe repetirse para el mismo agente en el año.	9
005	180	Fila 180: El Numero no debe repetirse para el mismo agente en el año.	9
005	181	Fila 181: El Numero no debe repetirse para el mismo agente en el año.	9
005	182	Fila 182: El Numero no debe repetirse para el mismo agente en el año.	9
005	183	Fila 183: El Numero no debe repetirse para el mismo agente en el año.	9
005	184	Fila 184: El Numero no debe repetirse para el mismo agente en el año.	9
005	185	Fila 185: CUIT inválido (27219274311).	9
005	185	Fila 185: El Numero no debe repetirse para el mismo agente en el año.	9
005	186	Fila 186: CUIT inválido (27219274311).	9
005	186	Fila 186: El Numero no debe repetirse para el mismo agente en el año.	9
005	187	Fila 187: El Numero no debe repetirse para el mismo agente en el año.	9
005	188	Fila 188: El Numero no debe repetirse para el mismo agente en el año.	9
005	189	Fila 189: El Numero no debe repetirse para el mismo agente en el año.	9
005	190	Fila 190: El Numero no debe repetirse para el mismo agente en el año.	9
005	191	Fila 191: El Numero no debe repetirse para el mismo agente en el año.	9
005	192	Fila 192: El Numero no debe repetirse para el mismo agente en el año.	9
005	193	Fila 193: El Numero no debe repetirse para el mismo agente en el año.	9
005	194	Fila 194: El Numero no debe repetirse para el mismo agente en el año.	9
005	195	Fila 195: El Numero no debe repetirse para el mismo agente en el año.	9
005	196	Fila 196: El Numero no debe repetirse para el mismo agente en el año.	9
005	197	Fila 197: El Numero no debe repetirse para el mismo agente en el año.	9
005	198	Fila 198: El Numero no debe repetirse para el mismo agente en el año.	9
005	199	Fila 199: El Numero no debe repetirse para el mismo agente en el año.	9
005	200	Fila 200: El Numero no debe repetirse para el mismo agente en el año.	9
005	201	Fila 201: El Numero no debe repetirse para el mismo agente en el año.	9
005	202	Fila 202: El Numero no debe repetirse para el mismo agente en el año.	9
005	203	Fila 203: El Numero no debe repetirse para el mismo agente en el año.	9
005	204	Fila 204: El Numero no debe repetirse para el mismo agente en el año.	9
005	205	Fila 205: El Numero no debe repetirse para el mismo agente en el año.	9
005	206	Fila 206: El Numero no debe repetirse para el mismo agente en el año.	9
005	207	Fila 207: El Numero no debe repetirse para el mismo agente en el año.	9
005	208	Fila 208: El Numero no debe repetirse para el mismo agente en el año.	9
005	209	Fila 209: El Numero no debe repetirse para el mismo agente en el año.	9
005	210	Fila 210: El Numero no debe repetirse para el mismo agente en el año.	9
005	211	Fila 211: El Numero no debe repetirse para el mismo agente en el año.	9
005	212	Fila 212: El Numero no debe repetirse para el mismo agente en el año.	9
005	213	Fila 213: El Numero no debe repetirse para el mismo agente en el año.	9
003	0	-Es conveniente que el Número sea correlativo por año (00026645).	7
005	214	Fila 214: El Numero no debe repetirse para el mismo agente en el año.	9
005	215	Fila 215: El Numero no debe repetirse para el mismo agente en el año.	9
005	216	Fila 216: El Numero no debe repetirse para el mismo agente en el año.	9
005	217	Fila 217: El Numero no debe repetirse para el mismo agente en el año.	9
005	218	Fila 218: El Numero no debe repetirse para el mismo agente en el año.	9
009	0	-Es conveniente que el Número sea correlativo por año (00000001).	13
009	2	Fila 2: El Numero no debe repetirse para el mismo agente en el año.	13
009	3	Fila 3: El Numero no debe repetirse para el mismo agente en el año.	13
009	4	Fila 4: El Numero no debe repetirse para el mismo agente en el año.	13
009	5	Fila 5: El Numero no debe repetirse para el mismo agente en el año.	13
009	6	Fila 6: El Numero no debe repetirse para el mismo agente en el año.	13
009	7	Fila 7: El Numero no debe repetirse para el mismo agente en el año.	13
009	8	Fila 8: El Numero no debe repetirse para el mismo agente en el año.	13
009	9	Fila 9: El Numero no debe repetirse para el mismo agente en el año.	13
009	10	Fila 10: El Numero no debe repetirse para el mismo agente en el año.	13
009	11	Fila 11: El Numero no debe repetirse para el mismo agente en el año.	13
009	12	Fila 12: El Numero no debe repetirse para el mismo agente en el año.	13
009	13	Fila 13: El Numero no debe repetirse para el mismo agente en el año.	13
009	14	Fila 14: El Numero no debe repetirse para el mismo agente en el año.	13
009	15	Fila 15: El Numero no debe repetirse para el mismo agente en el año.	13
009	16	Fila 16: El Numero no debe repetirse para el mismo agente en el año.	13
009	17	Fila 17: El Numero no debe repetirse para el mismo agente en el año.	13
009	18	Fila 18: El Numero no debe repetirse para el mismo agente en el año.	13
009	19	Fila 19: El Numero no debe repetirse para el mismo agente en el año.	13
009	20	Fila 20: El Numero no debe repetirse para el mismo agente en el año.	13
009	21	Fila 21: El Numero no debe repetirse para el mismo agente en el año.	13
009	22	Fila 22: El Numero no debe repetirse para el mismo agente en el año.	13
009	23	Fila 23: El Numero no debe repetirse para el mismo agente en el año.	13
009	24	Fila 24: El Numero no debe repetirse para el mismo agente en el año.	13
009	25	Fila 25: El Numero no debe repetirse para el mismo agente en el año.	13
009	26	Fila 26: El Numero no debe repetirse para el mismo agente en el año.	13
009	27	Fila 27: El Numero no debe repetirse para el mismo agente en el año.	13
009	28	Fila 28: El Numero no debe repetirse para el mismo agente en el año.	13
009	29	Fila 29: El Numero no debe repetirse para el mismo agente en el año.	13
009	30	Fila 30: El Numero no debe repetirse para el mismo agente en el año.	13
009	31	Fila 31: El Numero no debe repetirse para el mismo agente en el año.	13
009	32	Fila 32: El Numero no debe repetirse para el mismo agente en el año.	13
009	33	Fila 33: El Numero no debe repetirse para el mismo agente en el año.	13
009	34	Fila 34: El Numero no debe repetirse para el mismo agente en el año.	13
009	35	Fila 35: El Numero no debe repetirse para el mismo agente en el año.	13
009	36	Fila 36: El Numero no debe repetirse para el mismo agente en el año.	13
009	37	Fila 37: El Numero no debe repetirse para el mismo agente en el año.	13
009	38	Fila 38: El Numero no debe repetirse para el mismo agente en el año.	13
009	39	Fila 39: El Numero no debe repetirse para el mismo agente en el año.	13
009	40	Fila 40: El Numero no debe repetirse para el mismo agente en el año.	13
009	41	Fila 41: El Numero no debe repetirse para el mismo agente en el año.	13
009	42	Fila 42: El Numero no debe repetirse para el mismo agente en el año.	13
009	43	Fila 43: El Numero no debe repetirse para el mismo agente en el año.	13
009	44	Fila 44: El Numero no debe repetirse para el mismo agente en el año.	13
009	45	Fila 45: El Numero no debe repetirse para el mismo agente en el año.	13
009	46	Fila 46: El Numero no debe repetirse para el mismo agente en el año.	13
009	47	Fila 47: El Numero no debe repetirse para el mismo agente en el año.	13
009	48	Fila 48: El Numero no debe repetirse para el mismo agente en el año.	13
009	49	Fila 49: El Numero no debe repetirse para el mismo agente en el año.	13
009	50	Fila 50: El Numero no debe repetirse para el mismo agente en el año.	13
009	51	Fila 51: El Numero no debe repetirse para el mismo agente en el año.	13
009	52	Fila 52: El Numero no debe repetirse para el mismo agente en el año.	13
009	53	Fila 53: El Numero no debe repetirse para el mismo agente en el año.	13
009	54	Fila 54: El Numero no debe repetirse para el mismo agente en el año.	13
009	55	Fila 55: El Numero no debe repetirse para el mismo agente en el año.	13
009	56	Fila 56: El Numero no debe repetirse para el mismo agente en el año.	13
009	57	Fila 57: El Numero no debe repetirse para el mismo agente en el año.	13
009	58	Fila 58: El Numero no debe repetirse para el mismo agente en el año.	13
009	59	Fila 59: El Numero no debe repetirse para el mismo agente en el año.	13
009	60	Fila 60: El Numero no debe repetirse para el mismo agente en el año.	13
009	61	Fila 61: El Numero no debe repetirse para el mismo agente en el año.	13
009	62	Fila 62: El Numero no debe repetirse para el mismo agente en el año.	13
009	63	Fila 63: El Numero no debe repetirse para el mismo agente en el año.	13
009	64	Fila 64: El Numero no debe repetirse para el mismo agente en el año.	13
009	65	Fila 65: El Numero no debe repetirse para el mismo agente en el año.	13
009	66	Fila 66: El Numero no debe repetirse para el mismo agente en el año.	13
009	67	Fila 67: El Numero no debe repetirse para el mismo agente en el año.	13
009	68	Fila 68: El Numero no debe repetirse para el mismo agente en el año.	13
009	69	Fila 69: El Numero no debe repetirse para el mismo agente en el año.	13
009	70	Fila 70: El Numero no debe repetirse para el mismo agente en el año.	13
009	71	Fila 71: El Numero no debe repetirse para el mismo agente en el año.	13
009	72	Fila 72: El Numero no debe repetirse para el mismo agente en el año.	13
009	73	Fila 73: El Numero no debe repetirse para el mismo agente en el año.	13
009	74	Fila 74: El Numero no debe repetirse para el mismo agente en el año.	13
009	75	Fila 75: El Numero no debe repetirse para el mismo agente en el año.	13
009	76	Fila 76: El Numero no debe repetirse para el mismo agente en el año.	13
009	77	Fila 77: El Numero no debe repetirse para el mismo agente en el año.	13
009	78	Fila 78: El Numero no debe repetirse para el mismo agente en el año.	13
009	79	Fila 79: El Numero no debe repetirse para el mismo agente en el año.	13
009	80	Fila 80: El Numero no debe repetirse para el mismo agente en el año.	13
009	81	Fila 81: El Numero no debe repetirse para el mismo agente en el año.	13
009	82	Fila 82: El Numero no debe repetirse para el mismo agente en el año.	13
009	83	Fila 83: El Numero no debe repetirse para el mismo agente en el año.	13
009	84	Fila 84: El Numero no debe repetirse para el mismo agente en el año.	13
009	85	Fila 85: El Numero no debe repetirse para el mismo agente en el año.	13
009	86	Fila 86: El Numero no debe repetirse para el mismo agente en el año.	13
009	87	Fila 87: El Numero no debe repetirse para el mismo agente en el año.	13
009	88	Fila 88: El Numero no debe repetirse para el mismo agente en el año.	13
009	89	Fila 89: El Numero no debe repetirse para el mismo agente en el año.	13
009	90	Fila 90: El Numero no debe repetirse para el mismo agente en el año.	13
009	91	Fila 91: El Numero no debe repetirse para el mismo agente en el año.	13
009	92	Fila 92: El Numero no debe repetirse para el mismo agente en el año.	13
009	93	Fila 93: El Numero no debe repetirse para el mismo agente en el año.	13
009	94	Fila 94: El Numero no debe repetirse para el mismo agente en el año.	13
009	95	Fila 95: El Numero no debe repetirse para el mismo agente en el año.	13
009	96	Fila 96: El Numero no debe repetirse para el mismo agente en el año.	13
009	97	Fila 97: El Numero no debe repetirse para el mismo agente en el año.	13
009	98	Fila 98: El Numero no debe repetirse para el mismo agente en el año.	13
009	99	Fila 99: El Numero no debe repetirse para el mismo agente en el año.	13
009	100	Fila 100: El Numero no debe repetirse para el mismo agente en el año.	13
009	101	Fila 101: El Numero no debe repetirse para el mismo agente en el año.	13
009	102	Fila 102: El Numero no debe repetirse para el mismo agente en el año.	13
009	103	Fila 103: El Numero no debe repetirse para el mismo agente en el año.	13
009	104	Fila 104: El Numero no debe repetirse para el mismo agente en el año.	13
009	105	Fila 105: El Numero no debe repetirse para el mismo agente en el año.	13
009	106	Fila 106: El Numero no debe repetirse para el mismo agente en el año.	13
009	107	Fila 107: El Numero no debe repetirse para el mismo agente en el año.	13
009	108	Fila 108: El Numero no debe repetirse para el mismo agente en el año.	13
009	109	Fila 109: El Numero no debe repetirse para el mismo agente en el año.	13
009	110	Fila 110: El Numero no debe repetirse para el mismo agente en el año.	13
009	111	Fila 111: El Numero no debe repetirse para el mismo agente en el año.	13
009	112	Fila 112: El Numero no debe repetirse para el mismo agente en el año.	13
009	113	Fila 113: El Numero no debe repetirse para el mismo agente en el año.	13
009	114	Fila 114: El Numero no debe repetirse para el mismo agente en el año.	13
009	115	Fila 115: El Numero no debe repetirse para el mismo agente en el año.	13
009	116	Fila 116: El Numero no debe repetirse para el mismo agente en el año.	13
009	117	Fila 117: El Numero no debe repetirse para el mismo agente en el año.	13
009	118	Fila 118: El Numero no debe repetirse para el mismo agente en el año.	13
009	119	Fila 119: El Numero no debe repetirse para el mismo agente en el año.	13
009	120	Fila 120: El Numero no debe repetirse para el mismo agente en el año.	13
009	121	Fila 121: El Numero no debe repetirse para el mismo agente en el año.	13
009	122	Fila 122: El Numero no debe repetirse para el mismo agente en el año.	13
009	123	Fila 123: El Numero no debe repetirse para el mismo agente en el año.	13
009	124	Fila 124: El Numero no debe repetirse para el mismo agente en el año.	13
009	125	Fila 125: El Numero no debe repetirse para el mismo agente en el año.	13
009	126	Fila 126: El Numero no debe repetirse para el mismo agente en el año.	13
009	127	Fila 127: El Numero no debe repetirse para el mismo agente en el año.	13
009	128	Fila 128: El Numero no debe repetirse para el mismo agente en el año.	13
009	129	Fila 129: El Numero no debe repetirse para el mismo agente en el año.	13
009	130	Fila 130: El Numero no debe repetirse para el mismo agente en el año.	13
009	131	Fila 131: El Numero no debe repetirse para el mismo agente en el año.	13
009	132	Fila 132: El Numero no debe repetirse para el mismo agente en el año.	13
009	133	Fila 133: El Numero no debe repetirse para el mismo agente en el año.	13
009	134	Fila 134: El Numero no debe repetirse para el mismo agente en el año.	13
009	135	Fila 135: El Numero no debe repetirse para el mismo agente en el año.	13
009	136	Fila 136: El Numero no debe repetirse para el mismo agente en el año.	13
009	137	Fila 137: El Numero no debe repetirse para el mismo agente en el año.	13
009	138	Fila 138: El Numero no debe repetirse para el mismo agente en el año.	13
009	139	Fila 139: El Numero no debe repetirse para el mismo agente en el año.	13
009	140	Fila 140: El Numero no debe repetirse para el mismo agente en el año.	13
009	141	Fila 141: El Numero no debe repetirse para el mismo agente en el año.	13
009	142	Fila 142: El Numero no debe repetirse para el mismo agente en el año.	13
009	143	Fila 143: El Numero no debe repetirse para el mismo agente en el año.	13
009	144	Fila 144: El Numero no debe repetirse para el mismo agente en el año.	13
009	145	Fila 145: El Numero no debe repetirse para el mismo agente en el año.	13
009	146	Fila 146: El Numero no debe repetirse para el mismo agente en el año.	13
009	147	Fila 147: El Numero no debe repetirse para el mismo agente en el año.	13
009	148	Fila 148: El Numero no debe repetirse para el mismo agente en el año.	13
009	149	Fila 149: El Numero no debe repetirse para el mismo agente en el año.	13
009	150	Fila 150: El Numero no debe repetirse para el mismo agente en el año.	13
009	151	Fila 151: El Numero no debe repetirse para el mismo agente en el año.	13
009	152	Fila 152: El Numero no debe repetirse para el mismo agente en el año.	13
009	153	Fila 153: El Numero no debe repetirse para el mismo agente en el año.	13
009	154	Fila 154: El Numero no debe repetirse para el mismo agente en el año.	13
009	155	Fila 155: El Numero no debe repetirse para el mismo agente en el año.	13
009	156	Fila 156: El Numero no debe repetirse para el mismo agente en el año.	13
009	157	Fila 157: El Numero no debe repetirse para el mismo agente en el año.	13
009	158	Fila 158: El Numero no debe repetirse para el mismo agente en el año.	13
009	159	Fila 159: El Numero no debe repetirse para el mismo agente en el año.	13
009	160	Fila 160: El Numero no debe repetirse para el mismo agente en el año.	13
009	161	Fila 161: El Numero no debe repetirse para el mismo agente en el año.	13
009	162	Fila 162: El Numero no debe repetirse para el mismo agente en el año.	13
009	163	Fila 163: El Numero no debe repetirse para el mismo agente en el año.	13
009	164	Fila 164: El Numero no debe repetirse para el mismo agente en el año.	13
009	165	Fila 165: El Numero no debe repetirse para el mismo agente en el año.	13
009	166	Fila 166: El Numero no debe repetirse para el mismo agente en el año.	13
009	167	Fila 167: El Numero no debe repetirse para el mismo agente en el año.	13
009	168	Fila 168: El Numero no debe repetirse para el mismo agente en el año.	13
004	0	-Es conveniente que el Número sea correlativo por año (00000001).	8
004	2	Fila 2: El Numero no debe repetirse para el mismo agente en el año.	8
004	3	Fila 3: El Numero no debe repetirse para el mismo agente en el año.	8
004	4	Fila 4: El Numero no debe repetirse para el mismo agente en el año.	8
004	5	Fila 5: El Numero no debe repetirse para el mismo agente en el año.	8
004	6	Fila 6: El Numero no debe repetirse para el mismo agente en el año.	8
004	7	Fila 7: El Numero no debe repetirse para el mismo agente en el año.	8
004	8	Fila 8: El Numero no debe repetirse para el mismo agente en el año.	8
004	9	Fila 9: El Numero no debe repetirse para el mismo agente en el año.	8
004	10	Fila 10: El Numero no debe repetirse para el mismo agente en el año.	8
004	11	Fila 11: El Numero no debe repetirse para el mismo agente en el año.	8
004	12	Fila 12: El Numero no debe repetirse para el mismo agente en el año.	8
004	13	Fila 13: El Numero no debe repetirse para el mismo agente en el año.	8
004	14	Fila 14: El Numero no debe repetirse para el mismo agente en el año.	8
004	15	Fila 15: El Numero no debe repetirse para el mismo agente en el año.	8
004	16	Fila 16: El Numero no debe repetirse para el mismo agente en el año.	8
004	17	Fila 17: El Numero no debe repetirse para el mismo agente en el año.	8
004	18	Fila 18: El Numero no debe repetirse para el mismo agente en el año.	8
004	19	Fila 19: El Numero no debe repetirse para el mismo agente en el año.	8
004	20	Fila 20: El Numero no debe repetirse para el mismo agente en el año.	8
004	21	Fila 21: El Numero no debe repetirse para el mismo agente en el año.	8
004	22	Fila 22: El Numero no debe repetirse para el mismo agente en el año.	8
004	23	Fila 23: El Numero no debe repetirse para el mismo agente en el año.	8
004	24	Fila 24: El Numero no debe repetirse para el mismo agente en el año.	8
004	25	Fila 25: El Numero no debe repetirse para el mismo agente en el año.	8
004	26	Fila 26: El Numero no debe repetirse para el mismo agente en el año.	8
004	27	Fila 27: El Numero no debe repetirse para el mismo agente en el año.	8
004	28	Fila 28: El Numero no debe repetirse para el mismo agente en el año.	8
004	29	Fila 29: El Numero no debe repetirse para el mismo agente en el año.	8
004	30	Fila 30: El Numero no debe repetirse para el mismo agente en el año.	8
004	31	Fila 31: El Numero no debe repetirse para el mismo agente en el año.	8
004	32	Fila 32: El Numero no debe repetirse para el mismo agente en el año.	8
004	33	Fila 33: El Numero no debe repetirse para el mismo agente en el año.	8
004	34	Fila 34: El Numero no debe repetirse para el mismo agente en el año.	8
004	35	Fila 35: El Numero no debe repetirse para el mismo agente en el año.	8
004	36	Fila 36: El Numero no debe repetirse para el mismo agente en el año.	8
004	37	Fila 37: El Numero no debe repetirse para el mismo agente en el año.	8
004	38	Fila 38: El Numero no debe repetirse para el mismo agente en el año.	8
004	39	Fila 39: El Numero no debe repetirse para el mismo agente en el año.	8
004	40	Fila 40: El Numero no debe repetirse para el mismo agente en el año.	8
004	41	Fila 41: El Numero no debe repetirse para el mismo agente en el año.	8
004	42	Fila 42: El Numero no debe repetirse para el mismo agente en el año.	8
004	43	Fila 43: El Numero no debe repetirse para el mismo agente en el año.	8
004	44	Fila 44: El Numero no debe repetirse para el mismo agente en el año.	8
004	45	Fila 45: El Numero no debe repetirse para el mismo agente en el año.	8
004	46	Fila 46: El Numero no debe repetirse para el mismo agente en el año.	8
004	47	Fila 47: El Numero no debe repetirse para el mismo agente en el año.	8
004	48	Fila 48: El Numero no debe repetirse para el mismo agente en el año.	8
004	49	Fila 49: El Numero no debe repetirse para el mismo agente en el año.	8
004	50	Fila 50: El Numero no debe repetirse para el mismo agente en el año.	8
004	51	Fila 51: El Numero no debe repetirse para el mismo agente en el año.	8
004	52	Fila 52: El Numero no debe repetirse para el mismo agente en el año.	8
004	53	Fila 53: El Numero no debe repetirse para el mismo agente en el año.	8
004	54	Fila 54: El Numero no debe repetirse para el mismo agente en el año.	8
004	55	Fila 55: El Numero no debe repetirse para el mismo agente en el año.	8
004	56	Fila 56: El Numero no debe repetirse para el mismo agente en el año.	8
004	57	Fila 57: El Numero no debe repetirse para el mismo agente en el año.	8
004	58	Fila 58: El Numero no debe repetirse para el mismo agente en el año.	8
004	59	Fila 59: El Numero no debe repetirse para el mismo agente en el año.	8
004	60	Fila 60: El Numero no debe repetirse para el mismo agente en el año.	8
004	61	Fila 61: El Numero no debe repetirse para el mismo agente en el año.	8
004	62	Fila 62: El Numero no debe repetirse para el mismo agente en el año.	8
004	63	Fila 63: El Numero no debe repetirse para el mismo agente en el año.	8
004	64	Fila 64: El Numero no debe repetirse para el mismo agente en el año.	8
004	65	Fila 65: El Numero no debe repetirse para el mismo agente en el año.	8
004	66	Fila 66: El Numero no debe repetirse para el mismo agente en el año.	8
004	67	Fila 67: El Numero no debe repetirse para el mismo agente en el año.	8
004	68	Fila 68: El Numero no debe repetirse para el mismo agente en el año.	8
004	69	Fila 69: El Numero no debe repetirse para el mismo agente en el año.	8
004	70	Fila 70: El Numero no debe repetirse para el mismo agente en el año.	8
004	71	Fila 71: El Numero no debe repetirse para el mismo agente en el año.	8
004	72	Fila 72: El Numero no debe repetirse para el mismo agente en el año.	8
004	73	Fila 73: El Numero no debe repetirse para el mismo agente en el año.	8
004	74	Fila 74: El Numero no debe repetirse para el mismo agente en el año.	8
004	75	Fila 75: El Numero no debe repetirse para el mismo agente en el año.	8
004	76	Fila 76: El Numero no debe repetirse para el mismo agente en el año.	8
004	77	Fila 77: El Numero no debe repetirse para el mismo agente en el año.	8
004	78	Fila 78: El Numero no debe repetirse para el mismo agente en el año.	8
004	79	Fila 79: El Numero no debe repetirse para el mismo agente en el año.	8
004	80	Fila 80: El Numero no debe repetirse para el mismo agente en el año.	8
004	81	Fila 81: El Numero no debe repetirse para el mismo agente en el año.	8
004	82	Fila 82: El Numero no debe repetirse para el mismo agente en el año.	8
004	83	Fila 83: El Numero no debe repetirse para el mismo agente en el año.	8
004	84	Fila 84: El Numero no debe repetirse para el mismo agente en el año.	8
004	85	Fila 85: El Numero no debe repetirse para el mismo agente en el año.	8
004	86	Fila 86: El Numero no debe repetirse para el mismo agente en el año.	8
004	87	Fila 87: El Numero no debe repetirse para el mismo agente en el año.	8
004	88	Fila 88: El Numero no debe repetirse para el mismo agente en el año.	8
004	89	Fila 89: El Numero no debe repetirse para el mismo agente en el año.	8
004	90	Fila 90: El Numero no debe repetirse para el mismo agente en el año.	8
004	91	Fila 91: El Numero no debe repetirse para el mismo agente en el año.	8
004	92	Fila 92: El Numero no debe repetirse para el mismo agente en el año.	8
004	93	Fila 93: El Numero no debe repetirse para el mismo agente en el año.	8
004	94	Fila 94: El Numero no debe repetirse para el mismo agente en el año.	8
004	95	Fila 95: El Numero no debe repetirse para el mismo agente en el año.	8
004	96	Fila 96: El Numero no debe repetirse para el mismo agente en el año.	8
004	97	Fila 97: El Numero no debe repetirse para el mismo agente en el año.	8
004	98	Fila 98: El Numero no debe repetirse para el mismo agente en el año.	8
004	99	Fila 99: El Numero no debe repetirse para el mismo agente en el año.	8
004	100	Fila 100: El Numero no debe repetirse para el mismo agente en el año.	8
004	101	Fila 101: El Numero no debe repetirse para el mismo agente en el año.	8
\.


--
-- TOC entry 6025 (class 0 OID 5287322)
-- Dependencies: 655
-- Data for Name: ag_rete_prev; Type: TABLE DATA; Schema: temp; Owner: postgres
--

COPY temp.ag_rete_prev (dato) FROM stdin;
CAB0000000430709031216201711
201498335102017110400000001FA000400085579000000176560000000020000000000353
272192734312017110400000001FA000400085580000000653850000000020000000001308
272227131402017110400000001FA000400026566000000205600000000020000000000411
202736449122017110400000001FA000400085581000000147500000000020000000000295
272370919872017110400000001FA000400085582000001123130000000020000000002246
273020909772017110400000001FA000400026567000001023000000000020000000002046
203133407922017110400000001FA000400085583000000264090000000020000000000528
273675711232017110400000001FA000400026568000000350530000000020000000000701
306703646032017110400000001FA000400026569000002836620000000020000000005673
271882064632017110400000001FA000400026570000000164970000000020000000000330
272166095282017110400000001FA000400085584000000207190000000020000000000414
203880758062017110400000001FA000400085585000001029520000000020000000002059
203880758062017110400000001FA000400085586000001585200000000020000000003170
203880758062017110400000001FA000400085587000000257900000000020000000000516
306703646032017110400000001FA000400026572000002631190000000020000000005262
273057820042017110600000001FA000100054267000000400660000000020000000000801
202252504732017110900000001FA000400085641000000439120000000020000000000878
272458448112017111000000001FA000400026618000000365470000000020000000000731
201259445402017111000000001FA000400026625000000657520000000020000000001315
202830758782017111100000001FA000400085673000000952550000000020000000001905
272227131402017111100000001FA000400026626000000360720000000020000000000721
272227131402017111100000001FA000400026627000000486520000000020000000000973
203133407922017111100000001FA000400085674000000170570000000020000000000341
273613027592017111100000001FA000400085675000000487590000000020000000000975
271739554512017111100000001FA000400026628000000474270000000020000000000949
273020909772017111100000001FA000400026629000000916000000000020000000001832
273632066412017111100000001FA000400085676000000290840000000020000000000582
271444811222017111100000001FA000400085677000000516510000000020000000001033
272166095282017111100000001FA000400085678000001215450000000020000000002431
272166095282017111100000001FA000400085679000000044910000000020000000000090
272192734312017111100000001FA000400085680000000786890000000020000000001574
202736449122017111100000001FA000400085681000000362920000000020000000000726
201600601832017111100000001FA000400085682000000156700000000020000000000313
231631738242017111100000001FA000400026630000000717530000000020000000001435
203880758062017111100000001FA000400085683000000337540000000020000000000675
306703646032017111100000001FA000400026631000001150670000000020000000002301
272370919872017111100000001FA000400085684000000509100000000020000000001018
202850800892017111100000001FA000400085685000000223430000000020000000000447
202736449122017111400000001FA000400085688000000473240000000020000000000946
272481766072017111400000001FA000400085692000002248410000000020000000004497
306703646032017111400000001FA000400026638000002504360000000020000000005009
306703646032017111400000001FA000400026639000002189270000000020000000004379
306703646032017111400000001FA000400026640000000401490000000020000000000803
271014021562017111400000001FA000400085693000001427920000000020000000002856
233319761992017111400000001FA000400085694000000110520000000020000000000221
272227131402017111400000001FA000400026641000000871220000000020000000001742
306703646032017111600000001FA000400026656000000095150000000020000000000190
273020909772017111800000001FA000400026681000000816940000000020000000001634
202736449122017111800000001FA000400085748000000309600000000020000000000619
272227131402017111800000001FA000400026682000000351220000000020000000000702
272370919872017111800000001FA000400085749000000277880000000020000000000556
273632066412017111800000001FA000400085750000000083570000000020000000000167
200781921442017111800000001FA000400085751000000066450000000020000000000133
231631738242017111800000001FA000400026683000000798260000000020000000001597
203133407922017111800000001FA000400085752000000123060000000020000000000246
272192734312017111800000001FA000400085753000000708610000000020000000001417
272227131402017111800000001FA000400026684000000569930000000020000000001140
202646304532017111800000001FA000400085754000000337830000000020000000000676
271739554512017111800000001FA000400026685000000336780000000020000000000674
306703646032017111800000001FA000400026687000001520690000000020000000003041
203880758062017111800000001FA000400085755000001278350000000020000000002557
203880758062017111800000001FA000400085756000001142800000000020000000002286
203880758062017111800000001FA000400085757000001026350000000020000000002053
271739554512017111800000001FA000400026688000000136290000000020000000000273
203880758062017111800000001FA000400085758000000049550000000020000000000099
271644617642017111800000001FA000400085759000000315520000000020000000000631
273133403392017112100000001FA000400085773000000969590000000020000000001939
272425089182017112100000001FA000400085774000000445460000000020000000000891
272227131402017112100000001FA000400026694000000732710000000020000000001465
271014021562017112100000001FA000400085775000000813860000000020000000001628
272227131402017112100000001FA000400026695000000741740000000020000000001483
202252504732017112100000001FA000400085777000000632430000000020000000001265
273675711232017112500000001FA000400026740000000046720000000020000000000093
271739554512017112500000001FA000400026741000001437500000000020000000002875
272268032332017112500000001FA000400085828000000623610000000020000000001247
272370919872017112500000001FA000400085829000000400140000000020000000000800
306703646032017112500000001FA000400026742000002086350000000020000000004173
202736449122017112500000001FA000400085830000000412060000000020000000000824
272192734312017112500000001FA000400085831000001041760000000020000000002084
272227131402017112500000001FA000400026743000000092100000000020000000000184
273613027592017112500000001FA000400085832000002207030000000020000000004414
273613027592017112500000001FA000400085833000001381710000000020000000002763
203880758062017112500000001FA000400085834000001223410000000020000000002447
273133403392017112800000001FA000400085858000001749350000000020000000003499
273133403392017112800000001FA000400085859000000244960000000020000000000490
272370919872017112800000001FA000400085860000001591870000000020000000003184
272370919872017112800000001FA000400085861000001637140000000020000000003274
272481766072017112800000001FA000400085862000002010720000000020000000004021
272481766072017112800000001FA000400085863000000949590000000020000000001899
306703646032017112800000001FA000400026759000001348560000000020000000002697
272227131402017112800000001FA000400026760000000478660000000020000000000957
273466535022017112800000001FA000400085864000001298710000000020000000002597
271123782572017112800000001FA000400085865000000531830000000020000000001064
233319761992017112800000001FA000400085866000000257640000000020000000000515
202736449122017112800000001FA000400085867000000448250000000020000000000897
271739554512017112800000001FA000400026761000000377710000000020000000000755
202252504732017112800000001FA000400085868000000421030000000020000000000842
273133403392017112800000001FA000400085869000000205760000000020000000000412
273613027592017112800000001FA000400085870000001485600000000020000000002971
273613027592017112800000001FA000400085872000001485320000000020000000002971
PIE00010000000154122
\.


--
-- TOC entry 6026 (class 0 OID 5287356)
-- Dependencies: 656
-- Data for Name: ctacte_est_deuda; Type: TABLE DATA; Schema: temp; Owner: postgres
--

COPY temp.ctacte_est_deuda (trib_id, obj_id, subcta, anio, detalle, monto, accesor, multa, total, fchalta) FROM stdin;
\.


--
-- TOC entry 6027 (class 0 OID 5287360)
-- Dependencies: 657
-- Data for Name: ctacte_liq_manual; Type: TABLE DATA; Schema: temp; Owner: postgres
--

COPY temp.ctacte_liq_manual (liq_id, orden, item_id, param1, param2, param3, param4, monto) FROM stdin;
\.


--
-- TOC entry 6028 (class 0 OID 5287363)
-- Dependencies: 658
-- Data for Name: ctacte_liq_rec; Type: TABLE DATA; Schema: temp; Owner: postgres
--

COPY temp.ctacte_liq_rec (liq_id, recibo, fecha, acta, item_id, area, monto) FROM stdin;
\.


--
-- TOC entry 6029 (class 0 OID 5287369)
-- Dependencies: 659
-- Data for Name: cuenta; Type: TABLE DATA; Schema: temp; Owner: postgres
--

COPY temp.cuenta (cta_id, nombre, nombre_redu, tcta, part_id, cta_id_atras, est, fchmod, usrmod) FROM stdin;
1	Impuesto Inmobiliario Urbano	Inmobiliario Urbano	1	22	1	A	2016-06-27 15:27:11.658546	100
29	Recargo Inmobiliario Urbano	Inmobiliario Urbano Rec	3	22	29	A	2016-06-27 15:27:11.690657	100
2	Impuesto Inmobiliario Rural	Inmobiliario Rural	1	23	2	A	2016-06-27 15:27:11.734884	100
30	Recargo Inmobiliario Rural	Inmobiliario Rural Rec	3	23	30	A	2016-06-27 15:27:11.77064	100
3	Patente Automotor	Patente Automotor	1	25	3	A	2016-06-27 15:27:11.806089	100
31	Recargo Patente Automotor	Automotor - Recargo	3	25	31	A	2016-06-27 15:27:11.842327	100
4	Impuesto sobre Ingresos Brutos	Ingresos Brutos	1	26	4	A	2016-06-27 15:27:11.877705	100
5	Tasa de Habilitación Comercial	Habilitación Comercial	1	102	5	A	2016-06-27 15:27:11.949242	100
6	Tasa de Inspección Seg. e Higiene	TISH	1	27	6	A	2016-06-27 15:27:12.022208	100
35	Recargo 	Recargo	3	7	35	A	2016-06-27 15:27:12.130539	100
38	Financiación de Deuda	Financia	1	7	38	A	2016-06-27 15:27:12.166928	100
8	Tasa ambiental y RSU	Tasa Ambiental y RSU	1	119	8	A	2016-06-27 15:27:12.203983	100
9	Contribución uso Espacio Pco. Y Priv.	Uso Espacio Pco. y Priv.	1	103	9	A	2016-06-27 15:27:12.240099	100
10	Derechos de Construcción	Derechos de Construcción	1	105	10	A	2016-06-27 15:27:12.276241	100
36	Recargo Construcción	Construcción - Recargo	3	131	36	A	2016-06-27 15:27:12.312075	100
12	Derecho de Oficina	Derecho de Oficina	1	8	12	A	2016-06-27 15:27:12.383817	100
13	Multas	Multas	1	120	13	A	2016-06-27 15:27:12.420908	100
14	Contribución de Cementerio	Cementerio	1	10	14	A	2016-06-27 15:27:12.457944	100
15	Aportes desarrollo turístico	Desarrollo turístico	1	121	15	A	2016-06-27 15:27:12.493875	100
17	Publicidad FM del Valle	Publicidad FM del Valle	1	41	17	A	2016-06-27 15:27:12.565865	100
18	Complejo deportivo municipal	Complejo deportivo	1	75	18	A	2016-06-27 15:27:12.601201	100
19	Cultura (cine y otros)	Cultura (cine y otros)	1	9	19	A	2016-06-27 15:27:12.638045	100
20	Museo	Museo	1	123	20	A	2016-06-27 15:27:12.673936	100
21	Sala de Faena Porcinos	Sala de Faena Porcinos	1	124	21	A	2016-06-27 15:27:12.710477	100
22	Limpieza de Terrenos	Limpieza de Terrenos	1	125	22	A	2016-06-27 15:27:12.746883	100
23	Alquiler de Equipos	Alquiler de Equipos	1	126	23	A	2016-06-27 15:27:12.782665	100
24	Otros ingresos de Operación	Otros ingresos Operación	1	127	24	A	2016-06-27 15:27:12.819126	100
28	Coparticip. Ingresos Brutos	Copart. Ingresos Brutos	1	99	28	A	2016-06-27 15:27:12.963996	100
40	Limpieza y Barrido	Limpieza y Barrido	1	11	40	A	2016-07-01 09:51:44.932645	100
39	Tasa Vial Rural	Tasa Vial Rural	1	23	39	A	2016-07-01 09:51:44.932645	100
41	Bono Compensación	Bono Compensación	1	128	41	A	2016-07-01 10:05:23.072823	100
42	Desarrollo Cultural	Desarrollo Cultural	1	112	42	A	2016-07-01 10:05:23.072823	100
11	Derechos por Loteos y Subdivisiones	Loteos y Subdivisiones	1	106	11	A	2016-06-27 15:27:12.3481	100
27	Coparticip. Regalías Hidroeléctricas	Regalias Hidroelectricas	1	15	27	A	2016-06-27 15:27:12.927852	100
26	Coparticip. Regalías Petroleras	Regalias Petroleras	1	14	26	A	2016-06-27 15:27:12.891831	100
25	Coparticip. Regalías Gasíferas	Regalias Gasíferas	1	46	25	A	2016-06-27 15:27:12.855905	100
43	Desarrollo Deportivo	Desarrollo Deportivo	1	111	43	A	2016-07-01 10:05:23.072823	100
44	Desarrollo Turístico	Desarrollo Turístico	1	113	44	A	2016-07-01 10:05:23.072823	100
45	Desarrollo Productivo	Desarrollo Productivo	1	129	45	A	2016-07-01 10:05:23.072823	100
46	Desarrollo Social	Desarrollo Social	1	163	46	A	2016-07-01 10:05:23.072823	100
47	AVP Convenio Servicios	AVP Convenio Servicios	1	131	47	A	2016-07-01 10:05:23.072823	100
48	Convenio con Min. Educación	Convenio Min. Educación	1	132	48	A	2016-07-01 10:05:23.072823	100
49	Agua parajes	Agua parajes	1	161	49	A	2016-07-01 10:05:23.072823	100
50	Serv. Protección de derechos	Serv. Protec. derechos	1	53	50	A	2016-07-01 10:05:23.072823	100
51	Plan Calor y garrafas	Plan Calor y garrafas	1	70	51	A	2016-07-01 10:05:23.072823	100
52	Aportes del tesoro Provincial	Aportes tesoro Provincial	1	54	52	A	2016-07-01 10:05:23.072823	100
53	Otras Transferencias Provinciales	Otras Transferencias Prov	1	38	53	A	2016-07-01 10:05:23.072823	100
54	Club de día PAMI	Club de día PAMI	1	28	54	A	2016-07-01 10:05:23.072823	100
55	Unidad de empleo	Unidad de empleo	1	109	55	A	2016-07-01 10:05:23.072823	100
56	Municipio Saludable	Municipio Saludable	1	135	56	A	2016-07-01 10:05:23.072823	100
57	Otros Ingresos Nacionales Corrientes	Otros Ingresos Nacionales	1	136	57	A	2016-07-01 10:05:23.072823	100
58	Agua Los Cipreses	Agua Los Cipreses	1	162	58	A	2016-07-01 10:05:23.072823	100
59	Red Baja tensión Lago Rosario	Red Baja Lago Rosario	1	164	59	A	2016-07-01 10:05:23.072823	100
60	Cementerio	Cementerio	1	165	60	A	2016-07-01 10:05:23.072823	100
61	Mejoramiento habitacional 56 viviendas	Mejora 56 viviendas	1	166	61	A	2016-07-01 10:05:23.072823	100
62	Perforaciones	Perforaciones	1	167	62	A	2016-07-01 10:05:23.072823	100
63	Cisterna 1000m3	Cisterna 1000m3	1	168	63	A	2016-07-01 10:05:23.072823	100
64	Acueducto p/Cisterna	Acueducto p/Cisterna	1	169	64	A	2016-07-01 10:05:23.072823	100
65	Agua Callejon Williams	Agua Callejon Williams	1	170	65	A	2016-07-01 10:05:23.072823	100
66	Más Cerca: veredas	Más Cerca: veredas	1	118	66	A	2016-07-01 10:05:23.072823	100
67	Más Cerca: cordones	Más Cerca: cordones	1	137	67	A	2016-07-01 10:05:23.072823	100
68	Más Cerca: Pavimento Intertrabado 	Más Cerca Pav. Intertraba	1	171	68	A	2016-07-01 10:05:23.072823	100
69	Nexos Cloacales	Nexos Cloacales	1	172	69	A	2016-07-01 10:05:23.072823	100
70	Cisterna y Abastecimiento Sa. Colorada	Cisterna y Abast.Colorada	1	173	70	A	2016-07-01 10:05:23.072823	100
71	Niveles y desagües urbanos	Niveles/desagües urbanos	1	174	71	A	2016-07-01 10:05:23.072823	100
72	Barandas Puente canal norte	Barandas Pte canal norte	1	175	72	A	2016-07-01 10:05:23.072823	100
73	Asfalto Ap-Iwan	Asfalto Ap-Iwan	1	176	73	A	2016-07-01 10:05:23.072823	100
74	Instalacion electrica	Instalacion electrica	1	177	74	A	2016-07-01 10:05:23.072823	100
75	Conexiones gas (18)	Conexiones gas (18)	1	178	75	A	2016-07-01 10:05:23.072823	100
76	AVP Pavimento articulado	AVP Pavimento articulado	1	101	76	A	2016-07-01 10:05:23.072823	100
77	Viviendas Lago Rosario	Viviendas Lago Rosario	1	154	77	A	2016-07-01 10:05:23.072823	100
78	Viviendas BioClimáticas Sierra Colorada	Viv BioClimát Sa Colorada	1	139	78	A	2016-07-01 10:05:23.072823	100
79	Margenes Percy	Margenes Percy	1	179	79	A	2016-07-01 10:05:23.072823	100
16	INGRESOS VARIOS	INGRESOS VARIOS	1	11	16	A	2017-01-24 10:29:54.488244	11
32	RECARGO INGRESOS BRUTOS	IIBB - RECARGO	3	26	32	A	2017-01-20 09:18:53.036934	11
7	TASA POR SERVICIOS VARIOS (ADMIN)	ACTUACIóN ADMINISTRATIVA	1	7	7	A	2017-01-30 11:38:04.068036	11
80	Infraest. asistencia a Productores	Asistencia Proveedores	1	140	80	A	2016-07-01 10:05:23.072823	100
81	Desarrollo proyectos turísticos	Desarrollo proy turístico	1	141	81	A	2016-07-01 10:05:23.072823	100
82	Infraestructura parque industrial	Infra. parque industrial	1	142	82	A	2016-07-01 10:05:23.072823	100
83	Otros ingresos Provinciales de Capital	Otros ing Prov de Capital	1	145	83	A	2016-07-01 10:05:23.072823	100
84	Infraestructura Urbana	Infraestructura Urbana	1	148	84	A	2016-07-01 10:05:23.072823	100
85	Club Fontana - Aporte Provincial	Club Fontana - Ap Prov	1	160	85	A	2016-07-01 10:05:23.072823	100
86	Construir Empleo SUM Sa. Y Expo	Construir Empleo SUM Sa.	1	147	86	A	2016-07-01 10:05:23.072823	100
87	Otros Ingresos Nacionales de Capital	Otros Ing.Nac. de Capital	1	149	87	A	2016-07-01 10:05:23.072823	100
88	Sala Faena Movil	Sala Faena Movil	1	180	88	A	2016-07-01 10:05:23.072823	100
89	Venta 120 lotes	Venta 120 lotes	1	96	89	A	2016-07-01 10:05:23.072823	100
90	Venta 100 lotes	Venta 100 lotes	1	156	90	A	2016-07-01 10:05:23.072823	100
91	Recupero de adoquines	Recupero de adoquines	1	80	91	A	2016-07-01 10:05:23.072823	100
92	Venta de terrenos municipales	Venta terrenos municipale	1	157	92	A	2016-07-01 10:05:23.072823	100
93	Venta de tierras fiscales	Venta de tierras fiscales	1	89	93	A	2016-07-01 10:05:23.072823	100
94	Venta de Rodados	Venta de Rodados	1	150	94	A	2016-07-01 10:05:23.072823	100
95	Recupero de veredas	Recupero de veredas	1	151	95	A	2016-07-01 10:05:23.072823	100
96	Dividendos acciones	Dividendos acciones	1	24	96	A	2016-07-01 10:05:23.072823	100
97	Otras rentas	Otras rentas	1	152	97	A	2016-07-01 10:05:23.072823	100
98	Recupero IDEAS	Recupero IDEAS	1	62	98	A	2016-07-01 10:05:23.072823	100
99	Recupero Prog Prod Municipal	Recupero Prog Prod Mncpal	1	66	99	A	2016-07-01 10:05:23.072823	100
100	Convenio de materiales/servicios	Convenio mat/servicios	1	60	100	A	2016-07-01 10:05:23.072823	100
101	Red de gas Ruta 71	Red de gas Ruta 71	1	74	101	A	2016-07-01 10:05:23.072823	100
102	Red cloacal	Red cloacal	1	35	102	A	2016-07-01 10:05:23.072823	100
103	Red de gas Callejón Wilson	Red gas Callejón Wilson	1	87	103	A	2016-07-01 10:05:23.072823	100
104	UEP - PMGM	UEP - PMGM	1	88	104	A	2016-07-01 10:05:23.072823	100
105	Habilitación para conducir	Carnet Conductor	1	8	105	A	2016-07-04 10:31:01.627427	100
106	Patente Automotor - Bonificación	Pat.Automt-Bonif	2	25	106	A	2016-07-05 08:59:28.740177	100
107	Impuesto Inmobiliario - Bonificación	Imp.Inmob-Bonif	2	22	107	A	2016-07-05 09:23:24.113661	100
110	Abasto y veterinaria	Abasto y veterinaria	1	124	21	A	2016-07-05 15:40:11.397957	100
111	Patente de animales	Patente de animales	1	124	21	A	2016-07-05 15:40:14.110388	100
112	Circo y Parque de diversiones	Circo/Parque Div	1	103	9	A	2016-07-05 15:40:16.026972	100
113	Comercio en via publica	Comercio en via publica	1	103	9	A	2016-07-05 15:40:17.812121	100
114	Rifas y juegos de azar	Rifas y juegos de azar	1	103	9	A	2016-07-05 15:40:19.654295	100
115	Animales invasores	Animales invasores	1	124	21	A	2016-07-05 15:40:21.42179	100
37	Ingresos Brutos - Bonificación	IIBB-Bonif	2	26	37	A	2016-07-25 09:26:56.296012	100
117	COPARTICIPACION FEDERAL DE IMPUESTOS	COPARTICIPACION FEDERAL	1	181	117	A	2016-09-26 07:57:56.464358	10
118	FONDO FEDERAL SOLIDARIO	FONDO FEDERAL SOLIDARIO	1	182	118	A	2016-09-27 09:11:29.02406	10
119	RECUPERO ASFALTO AP IWAN	RECUPERO ASFALTO AP IWAN	1	183	119	A	2016-10-04 07:31:29.066015	10
120	REPARACION MARGEN IZQ 130M TRAMO POLIDEP	REPARACION MARGEN IZQ	1	184	120	A	2017-01-04 10:15:32.528639	10
121	PARQUIZACION Y RIEGO AUTOMATICO CTRO CIV	PARQIZACION CTRO CIVICO	1	185	121	A	2017-01-04 10:37:12.671745	10
122	MEJORAMIENTO SOLADO EN PLAZA CREL FONTAN	MEJORAMIENTO SOLADO PLAZA	1	186	122	A	2017-01-04 10:58:10.055271	10
123	REFUGIO DE EXCOMBATIENTES DE MALVINAS	REFUGIO EXCOMB. MALVINAS	1	188	123	A	2017-01-05 09:06:01.028714	14
124	VIVIENDA MANUEL E. DIAZ EXPTE. 1392/16	VIV. MANUEL. DIAZ IPV	1	190	124	A	2017-01-06 09:08:30.707286	14
34	TASA INSPECC. SEG. E HIGIENE-BONIF.	TISH - BONIFICACION	2	27	34	A	2017-01-18 09:26:29.039245	11
125	VIVIENDA RICARDO TARDON EXPTE. 1392/16	VIV. RICARDO TARDON IPV	1	192	125	A	2017-01-20 11:00:04.211447	14
126	VIVIENDA DANIEL E. GALARZA EXPTE. 1392/1	VIV. DANIEL GALARZA IPV	1	191	126	A	2017-01-20 11:01:05.366945	14
127	CREDITOS FORTALECER	CREDITOS FORTALECER	1	193	127	A	2017-01-24 10:21:20.03927	10
128	CREDITOS FORTALECER RECARGO	CRED FORTALECER RECARGO	3	193	128	A	2017-01-24 10:22:04.98825	10
129	INGRESOS VARIOS RECARGO	INGRESOS VARIOS RECARGO	3	11	129	A	2017-01-24 10:51:07.243727	11
130	RECARGO TASA SEG. E HIGIENE	RECARGO TSH	3	27	130	A	2017-02-17 12:56:32.535096	109
131	CORDON CUNETA 14 CUADRAS	CORDON CUNETA 14 CUADRAS	1	194	131	A	2017-03-08 11:10:25.046847	14
132	RED AGUA POTABLE ALDEA ESCOLAR	RED DE AGUA POTABLE ALDEA	1	195	132	A	2017-03-09 09:52:28.151819	14
133	CONST. CALLE ACCESO Y ESTACIO. CC	CONST. CALLE ACC. Y ESTA	1	197	133	A	2017-03-16 08:39:35.702417	14
134	ASFALTO AP-IWAN 3ERA ETAPA	ASF. AP-IWAN 3ERA ETAPA	1	198	134	A	2017-03-16 11:51:14.677913	14
135	OTRAS OBRAS	OTRAS OBRAS	1	199	135	A	2017-03-28 10:48:27.266601	14
136	MULTA DE INGRESOS BRUTOS	MULTA IIBB	4	120	136	A	2017-04-07 12:59:30.155092	11
137	HAB. COMERCIAL - RECARGO	HAB. COMERCIAL - RECARGO	3	102	137	A	2017-05-10 09:29:25.273884	11
33	Recargo Hab. Comercial	Hab. Comercial - Recargo	3	113	33	B	2016-06-27 15:27:11.985399	100
138	RED DE GAS 30 VIVIENDASIPV	RED DE GAS 30 VIV. IPV	1	200	138	A	2017-05-12 08:36:34.816875	14
139	TR DESARROLLOS DE CAPITAL DE PRODUCCION	TR DES. CAPITAL PROD.	1	201	139	A	2017-07-13 09:41:39.372534	14
\.


--
-- TOC entry 6030 (class 0 OID 5287375)
-- Dependencies: 660
-- Data for Name: ddjj_rubros; Type: TABLE DATA; Schema: temp; Owner: postgres
--

COPY temp.ddjj_rubros (djrub_id, rubro_id, base, cant, minimo, alicuota, monto) FROM stdin;
\.


--
-- TOC entry 5982 (class 0 OID 5285993)
-- Dependencies: 562
-- Data for Name: emi; Type: TABLE DATA; Schema: temp; Owner: postgres
--

COPY temp.emi (fila, ctacte_id, trib_id, obj_id, subcta, num_nom, anio, cuota, monto, montoanual, venc, domi, codpos, est_nom, usr_id, barr_parc, barr_pos, obj_dato, codigolink) FROM stdin;
\.


--
-- TOC entry 6031 (class 0 OID 5287381)
-- Dependencies: 661
-- Data for Name: fin_mov; Type: TABLE DATA; Schema: temp; Owner: postgres
--

COPY temp.fin_mov (mov_id, prov_id, tipo, torigen, fecha, total, retenido, monto, bcocta_id, chequenro, chequeserie, cbu, titularcta, expe, fchcobro, fchconcilia, impreso, obs, est, asien_id, fchmod, usrmod) FROM stdin;
\.


--
-- TOC entry 5647 (class 0 OID 5283916)
-- Dependencies: 186
-- Data for Name: fin_part_anio_dinamico; Type: TABLE DATA; Schema: temp; Owner: postgres
--

COPY temp.fin_part_anio_dinamico (anio, usr_id, fchhasta, part_id, caracter, fin, fun, obj, ins, ubi, asignado, preventivo, comprometido, saldo, saldoporc, devengado, pagado, cobrado, deuda, arecaudar) FROM stdin;
\.


--
-- TOC entry 5648 (class 0 OID 5283924)
-- Dependencies: 187
-- Data for Name: fin_part_anio_mes; Type: TABLE DATA; Schema: temp; Owner: postgres
--

COPY temp.fin_part_anio_mes (anio, usr_id, fchhasta, part_id, caracter, fin, fun, obj, ins, ubi, mes, asignado, ejecutado) FROM stdin;
\.


--
-- TOC entry 6048 (class 0 OID 0)
-- Dependencies: 335
-- Name: his_plan_his_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.his_plan_his_id_seq', 1, false);


--
-- TOC entry 6049 (class 0 OID 0)
-- Dependencies: 443
-- Name: ret_det_ret_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ret_det_ret_id_seq', 1, true);


--
-- TOC entry 6050 (class 0 OID 0)
-- Dependencies: 466
-- Name: seq_banco_cuenta; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_banco_cuenta', 1, false);


--
-- TOC entry 6051 (class 0 OID 0)
-- Dependencies: 467
-- Name: seq_caja_cheque_cartera; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_caja_cheque_cartera', 1, false);


--
-- TOC entry 6052 (class 0 OID 0)
-- Dependencies: 468
-- Name: seq_caja_opera; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_caja_opera', 1, false);


--
-- TOC entry 6053 (class 0 OID 0)
-- Dependencies: 469
-- Name: seq_caja_opera_mdp; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_caja_opera_mdp', 1, false);


--
-- TOC entry 6054 (class 0 OID 0)
-- Dependencies: 470
-- Name: seq_caja_pagoold; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_caja_pagoold', 1, false);


--
-- TOC entry 6055 (class 0 OID 0)
-- Dependencies: 179
-- Name: seq_caja_ticket; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_caja_ticket', 1, false);


--
-- TOC entry 6056 (class 0 OID 0)
-- Dependencies: 471
-- Name: seq_calc_desc; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_calc_desc', 1, true);


--
-- TOC entry 6057 (class 0 OID 0)
-- Dependencies: 472
-- Name: seq_cem_alq_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_cem_alq_id', 1, false);


--
-- TOC entry 6058 (class 0 OID 0)
-- Dependencies: 473
-- Name: seq_cem_fall_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_cem_fall_id', 1, true);


--
-- TOC entry 6059 (class 0 OID 0)
-- Dependencies: 244
-- Name: seq_comp_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_comp_id', 1, false);


--
-- TOC entry 6060 (class 0 OID 0)
-- Dependencies: 474
-- Name: seq_ctacte_aju_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_ctacte_aju_id', 1, false);


--
-- TOC entry 6061 (class 0 OID 0)
-- Dependencies: 475
-- Name: seq_ctacte_cambio_est; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_ctacte_cambio_est', 1, false);


--
-- TOC entry 6062 (class 0 OID 0)
-- Dependencies: 476
-- Name: seq_ctacte_excep; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_ctacte_excep', 1, false);


--
-- TOC entry 6063 (class 0 OID 0)
-- Dependencies: 477
-- Name: seq_ctacte_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_ctacte_id', 1, true);


--
-- TOC entry 6064 (class 0 OID 0)
-- Dependencies: 478
-- Name: seq_ctacte_libredeuda; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_ctacte_libredeuda', 1, false);


--
-- TOC entry 6065 (class 0 OID 0)
-- Dependencies: 479
-- Name: seq_ctacte_pagocta; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_ctacte_pagocta', 1, false);


--
-- TOC entry 6066 (class 0 OID 0)
-- Dependencies: 480
-- Name: seq_ctacte_planvenc_corr_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_ctacte_planvenc_corr_id', 1, false);


--
-- TOC entry 6067 (class 0 OID 0)
-- Dependencies: 481
-- Name: seq_cuenta; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_cuenta', 1, false);


--
-- TOC entry 6068 (class 0 OID 0)
-- Dependencies: 482
-- Name: seq_debito_adh; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_debito_adh', 1, false);


--
-- TOC entry 6069 (class 0 OID 0)
-- Dependencies: 282
-- Name: seq_debito_periodo; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_debito_periodo', 1, false);


--
-- TOC entry 6070 (class 0 OID 0)
-- Dependencies: 270
-- Name: seq_dj_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_dj_id', 1, true);


--
-- TOC entry 6071 (class 0 OID 0)
-- Dependencies: 483
-- Name: seq_domi_barr_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_domi_barr_id', 1, false);


--
-- TOC entry 6072 (class 0 OID 0)
-- Dependencies: 484
-- Name: seq_emision_margen_cod; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_emision_margen_cod', 1, false);



--
-- TOC entry 6075 (class 0 OID 0)
-- Dependencies: 487
-- Name: seq_facilida_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_facilida_id', 1, false);


--
-- TOC entry 6076 (class 0 OID 0)
-- Dependencies: 488
-- Name: seq_firma_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_firma_id', 1, false);


--
-- TOC entry 6077 (class 0 OID 0)
-- Dependencies: 489
-- Name: seq_fisca_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_fisca_id', 1, false);


--
-- TOC entry 6078 (class 0 OID 0)
-- Dependencies: 490
-- Name: seq_hab_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_hab_id', 1, false);


--
-- TOC entry 6079 (class 0 OID 0)
-- Dependencies: 491
-- Name: seq_his_cem; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_his_cem', 1, true);


--
-- TOC entry 6080 (class 0 OID 0)
-- Dependencies: 492
-- Name: seq_his_comer; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_his_comer', 1, true);


--
-- TOC entry 6081 (class 0 OID 0)
-- Dependencies: 318
-- Name: seq_his_ctacte_ajuste; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_his_ctacte_ajuste', 1, false);


--
-- TOC entry 6082 (class 0 OID 0)
-- Dependencies: 320
-- Name: seq_his_domi; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_his_domi', 1, false);


--
-- TOC entry 6083 (class 0 OID 0)
-- Dependencies: 493
-- Name: seq_his_inm; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_his_inm', 1, false);


--
-- TOC entry 6084 (class 0 OID 0)
-- Dependencies: 494
-- Name: seq_his_inm_cambio; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_his_inm_cambio', 1, false);


--
-- TOC entry 6085 (class 0 OID 0)
-- Dependencies: 324
-- Name: seq_his_inm_mej; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_his_inm_mej', 1, false);


--
-- TOC entry 6086 (class 0 OID 0)
-- Dependencies: 495
-- Name: seq_his_objeto; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_his_objeto', 1, false);


--
-- TOC entry 6087 (class 0 OID 0)
-- Dependencies: 496
-- Name: seq_his_objeto_excep_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_his_objeto_excep_id', 1, false);


--
-- TOC entry 6088 (class 0 OID 0)
-- Dependencies: 497
-- Name: seq_his_objeto_item; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_his_objeto_item', 1, false);


--
-- TOC entry 6089 (class 0 OID 0)
-- Dependencies: 498
-- Name: seq_his_persona; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_his_persona', 1, false);


--
-- TOC entry 6090 (class 0 OID 0)
-- Dependencies: 499
-- Name: seq_his_plan; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_his_plan', 1, false);


--
-- TOC entry 6091 (class 0 OID 0)
-- Dependencies: 500
-- Name: seq_his_rodado; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_his_rodado', 1, false);


--
-- TOC entry 6092 (class 0 OID 0)
-- Dependencies: 501
-- Name: seq_inm_cif; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_inm_cif', 1, false);


--
-- TOC entry 6093 (class 0 OID 0)
-- Dependencies: 502
-- Name: seq_inm_vta; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_inm_vta', 1, true);


--
-- TOC entry 6094 (class 0 OID 0)
-- Dependencies: 503
-- Name: seq_item; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_item', 1, false);


--
-- TOC entry 6095 (class 0 OID 0)
-- Dependencies: 375
-- Name: seq_judi; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_judi', 1, false);


--
-- TOC entry 6096 (class 0 OID 0)
-- Dependencies: 504
-- Name: seq_objeto_ttabla; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_objeto_ttabla', 1, false);


--
-- TOC entry 6097 (class 0 OID 0)
-- Dependencies: 408
-- Name: seq_persona_ajuste; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_persona_ajuste', 1, false);


--
-- TOC entry 6098 (class 0 OID 0)
-- Dependencies: 505
-- Name: seq_persona_ib; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_persona_ib', 1, true);


--
-- TOC entry 6099 (class 0 OID 0)
-- Dependencies: 506
-- Name: seq_plan; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_plan', 1, false);


--
-- TOC entry 6100 (class 0 OID 0)
-- Dependencies: 507
-- Name: seq_plan_config; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_plan_config', 1, false);


--
-- TOC entry 6101 (class 0 OID 0)
-- Dependencies: 508
-- Name: seq_ret_det_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_ret_det_id', 1, true);


--
-- TOC entry 6102 (class 0 OID 0)
-- Dependencies: 440
-- Name: seq_ret_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_ret_id', 1, true);


--
-- TOC entry 6103 (class 0 OID 0)
-- Dependencies: 509
-- Name: seq_texto_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_texto_id', 1, false);


--
-- TOC entry 6104 (class 0 OID 0)
-- Dependencies: 510
-- Name: seq_trib; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_trib', 1, false);


--
-- TOC entry 6105 (class 0 OID 0)
-- Dependencies: 176
-- Name: seq_muni_ofi_id; Type: SEQUENCE SET; Schema: sam; Owner: postgres
--

SELECT pg_catalog.setval('sam.seq_muni_ofi_id', 1, false);


--
-- TOC entry 6106 (class 0 OID 0)
-- Dependencies: 580
-- Name: seq_usuarioweb_usr_id; Type: SEQUENCE SET; Schema: sam; Owner: postgres
--

SELECT pg_catalog.setval('sam.seq_usuarioweb_usr_id', 1, true);


--
-- TOC entry 6107 (class 0 OID 0)
-- Dependencies: 632
-- Name: sis_grupo_gru_id_seq; Type: SEQUENCE SET; Schema: sam; Owner: postgres
--

SELECT pg_catalog.setval('sam.sis_grupo_gru_id_seq', 2, true);


--
-- TOC entry 6108 (class 0 OID 0)
-- Dependencies: 640
-- Name: sis_usuario_acc_acc_id_seq; Type: SEQUENCE SET; Schema: sam; Owner: postgres
--

SELECT pg_catalog.setval('sam.sis_usuario_acc_acc_id_seq', 1, true);


--
-- TOC entry 6109 (class 0 OID 0)
-- Dependencies: 642
-- Name: sis_usuario_acc_err_acc_id_seq; Type: SEQUENCE SET; Schema: sam; Owner: postgres
--

SELECT pg_catalog.setval('sam.sis_usuario_acc_err_acc_id_seq', 2, true);


--
-- TOC entry 6110 (class 0 OID 0)
-- Dependencies: 647
-- Name: sis_usuario_usr_id_seq; Type: SEQUENCE SET; Schema: sam; Owner: postgres
--

SELECT pg_catalog.setval('sam.sis_usuario_usr_id_seq', 1, false);


--
-- TOC entry 6111 (class 0 OID 0)
-- Dependencies: 667
-- Name: seq_ctacte_liq_manual; Type: SEQUENCE SET; Schema: temp; Owner: postgres
--

SELECT pg_catalog.setval('temp.seq_ctacte_liq_manual', 1, true);


--
-- TOC entry 6112 (class 0 OID 0)
-- Dependencies: 668
-- Name: seq_ddjj_rubros; Type: SEQUENCE SET; Schema: temp; Owner: postgres
--

SELECT pg_catalog.setval('temp.seq_ddjj_rubros', 1, true);
--
-- PostgreSQL database dump complete
--

