--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.2
-- Dumped by pg_dump version 9.5.2

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

SET search_path = public, pg_catalog;

--
-- Data for Name: api_author; Type: TABLE DATA; Schema: public; Owner: rlibuser
--

COPY api_author (id, firstname, lastname, biography, email) FROM stdin;
3	testAuthorName2	testAuthorSName2	testbio....	testemail1@mail.com
4	testAuthorName3	testAuthorSName3		testemail1@mail.com
2	testAuthorName1	testAuthorSName1		
\.


--
-- Name: api_author_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlibuser
--

SELECT pg_catalog.setval('api_author_id_seq', 4, true);


--
-- Data for Name: api_category; Type: TABLE DATA; Schema: public; Owner: rlibuser
--

COPY api_category (id, name) FROM stdin;
1	testCategory1
2	testCategory2
3	testCategory3
4	testCategory4
5	testCategory5
\.


--
-- Name: api_category_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlibuser
--

SELECT pg_catalog.setval('api_category_id_seq', 5, true);


--
-- Data for Name: api_editor; Type: TABLE DATA; Schema: public; Owner: rlibuser
--

COPY api_editor (id, name) FROM stdin;
1	testEditor1
2	testEditor2
3	testEditor3
4	testEditor4
5	testEditor5
6	testEditor6
7	testEditor7
\.


--
-- Name: api_editor_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlibuser
--

SELECT pg_catalog.setval('api_editor_id_seq', 7, true);


--
-- Data for Name: api_publisher; Type: TABLE DATA; Schema: public; Owner: rlibuser
--

COPY api_publisher (id, adress, city, state_providence, country, website, name) FROM stdin;
2	testAdress1	testCity1				testPublisher1
3	testAdress2	testCity2	testState2			testPublisher2
4		testCity3		CANADA	http://www.testwebsite1.com	testPublisher3
\.


--
-- Name: api_publisher_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlibuser
--

SELECT pg_catalog.setval('api_publisher_id_seq', 4, true);


--
-- Data for Name: api_resource; Type: TABLE DATA; Schema: public; Owner: rlibuser
--

COPY api_resource (id, title, date, resource_file, url, subtitle, abstract, journal, volume, number, startpage, endpage, series, edition, sourcetype, publisher_id) FROM stdin;
2	testTitle1	2010-10-10						\N	\N	\N	\N			OTHER	\N
3	testTitle2	2015-10-10						\N	\N	1	1			OTHER	\N
4	testTitle3	1994-10-10						\N	\N	\N	\N			BOOK	3
5	testTitle4	1994-10-10					testjournal1	5	12	\N	\N			NOVEL	2
7	testTitle6	2012-05-05		http://testurl.com				\N	4	\N	\N			OTHER	3
8	testTitle7	2003-03-03			testsubtitle2			\N	\N	\N	\N			OTHER	\N
9	testTitle8	2000-10-14						\N	5	20	25			JOURNAL	2
10	testTitle9	1200-01-01						\N	\N	4	5			OTHER	3
11	testTitle10	1555-05-05		http://testurl.com				\N	\N	\N	\N	5		OTHER	\N
6	testTitle5	1812-12-12						\N	3	\N	\N			OTHER	4
\.


--
-- Data for Name: api_resource_authors; Type: TABLE DATA; Schema: public; Owner: rlibuser
--

COPY api_resource_authors (id, resource_id, author_id) FROM stdin;
1	2	2
2	3	2
3	4	2
4	4	4
5	5	4
6	6	2
7	7	2
8	7	3
9	7	4
10	8	3
11	9	2
12	10	4
13	11	2
14	11	3
15	11	4
\.


--
-- Name: api_resource_authors_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlibuser
--

SELECT pg_catalog.setval('api_resource_authors_id_seq', 15, true);


--
-- Data for Name: api_resource_categories; Type: TABLE DATA; Schema: public; Owner: rlibuser
--

COPY api_resource_categories (id, resource_id, category_id) FROM stdin;
1	4	2
2	4	4
3	5	2
4	6	2
5	8	2
6	8	3
7	8	4
8	9	2
9	10	5
10	11	2
11	11	5
\.


--
-- Name: api_resource_categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlibuser
--

SELECT pg_catalog.setval('api_resource_categories_id_seq', 11, true);


--
-- Data for Name: api_resource_editors; Type: TABLE DATA; Schema: public; Owner: rlibuser
--

COPY api_resource_editors (id, resource_id, editor_id) FROM stdin;
1	4	1
2	4	2
3	7	4
4	8	1
5	8	2
6	8	3
7	8	4
8	9	2
9	9	3
10	10	4
11	10	6
12	10	7
13	11	5
14	11	6
15	11	7
\.


--
-- Name: api_resource_editors_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlibuser
--

SELECT pg_catalog.setval('api_resource_editors_id_seq', 15, true);


--
-- Name: api_resource_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlibuser
--

SELECT pg_catalog.setval('api_resource_id_seq', 11, true);


--
-- PostgreSQL database dump complete
--

