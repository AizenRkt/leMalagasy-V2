--
-- PostgreSQL database dump
--

\restrict C7p4ULtRRt7ylF6YXtNAgh3cmTmTPByoiTYoggyajfHMOxadNfCDyVyBeIPD6IG

-- Dumped from database version 17.7 (Debian 17.7-3.pgdg13+1)
-- Dumped by pg_dump version 17.7 (Debian 17.7-3.pgdg13+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: feed_slot; Type: TYPE; Schema: public; Owner: lemalagasy_user
--

CREATE TYPE public.feed_slot AS ENUM (
    'FEATURED',
    'LATEST',
    'SPOTLIGHT'
);


ALTER TYPE public.feed_slot OWNER TO lemalagasy_user;

--
-- Name: statut_article; Type: TYPE; Schema: public; Owner: lemalagasy_user
--

CREATE TYPE public.statut_article AS ENUM (
    'BROUILLON',
    'PUBLIE',
    'ARCHIVE'
);


ALTER TYPE public.statut_article OWNER TO lemalagasy_user;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: article; Type: TABLE; Schema: public; Owner: lemalagasy_user
--

CREATE TABLE public.article (
    id integer NOT NULL,
    title text,
    summary text,
    mongodb_id character varying(50),
    published_at timestamp without time zone,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.article OWNER TO lemalagasy_user;

--
-- Name: article_authors; Type: TABLE; Schema: public; Owner: lemalagasy_user
--

CREATE TABLE public.article_authors (
    id integer NOT NULL,
    id_article integer,
    id_utilisateur integer
);


ALTER TABLE public.article_authors OWNER TO lemalagasy_user;

--
-- Name: article_authors_id_seq; Type: SEQUENCE; Schema: public; Owner: lemalagasy_user
--

CREATE SEQUENCE public.article_authors_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.article_authors_id_seq OWNER TO lemalagasy_user;

--
-- Name: article_authors_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: lemalagasy_user
--

ALTER SEQUENCE public.article_authors_id_seq OWNED BY public.article_authors.id;


--
-- Name: article_categories; Type: TABLE; Schema: public; Owner: lemalagasy_user
--

CREATE TABLE public.article_categories (
    id integer NOT NULL,
    id_article integer,
    id_category integer
);


ALTER TABLE public.article_categories OWNER TO lemalagasy_user;

--
-- Name: article_categories_id_seq; Type: SEQUENCE; Schema: public; Owner: lemalagasy_user
--

CREATE SEQUENCE public.article_categories_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.article_categories_id_seq OWNER TO lemalagasy_user;

--
-- Name: article_categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: lemalagasy_user
--

ALTER SEQUENCE public.article_categories_id_seq OWNED BY public.article_categories.id;


--
-- Name: article_id_seq; Type: SEQUENCE; Schema: public; Owner: lemalagasy_user
--

CREATE SEQUENCE public.article_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.article_id_seq OWNER TO lemalagasy_user;

--
-- Name: article_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: lemalagasy_user
--

ALTER SEQUENCE public.article_id_seq OWNED BY public.article.id;


--
-- Name: article_images; Type: TABLE; Schema: public; Owner: lemalagasy_user
--

CREATE TABLE public.article_images (
    id integer NOT NULL,
    id_article integer,
    path text NOT NULL
);


ALTER TABLE public.article_images OWNER TO lemalagasy_user;

--
-- Name: article_images_id_seq; Type: SEQUENCE; Schema: public; Owner: lemalagasy_user
--

CREATE SEQUENCE public.article_images_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.article_images_id_seq OWNER TO lemalagasy_user;

--
-- Name: article_images_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: lemalagasy_user
--

ALTER SEQUENCE public.article_images_id_seq OWNED BY public.article_images.id;


--
-- Name: article_status; Type: TABLE; Schema: public; Owner: lemalagasy_user
--

CREATE TABLE public.article_status (
    id integer NOT NULL,
    statut public.statut_article DEFAULT 'BROUILLON'::public.statut_article NOT NULL,
    id_article integer
);


ALTER TABLE public.article_status OWNER TO lemalagasy_user;

--
-- Name: article_status_id_seq; Type: SEQUENCE; Schema: public; Owner: lemalagasy_user
--

CREATE SEQUENCE public.article_status_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.article_status_id_seq OWNER TO lemalagasy_user;

--
-- Name: article_status_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: lemalagasy_user
--

ALTER SEQUENCE public.article_status_id_seq OWNED BY public.article_status.id;


--
-- Name: article_tags; Type: TABLE; Schema: public; Owner: lemalagasy_user
--

CREATE TABLE public.article_tags (
    id integer NOT NULL,
    id_article integer,
    id_tag integer
);


ALTER TABLE public.article_tags OWNER TO lemalagasy_user;

--
-- Name: article_tags_id_seq; Type: SEQUENCE; Schema: public; Owner: lemalagasy_user
--

CREATE SEQUENCE public.article_tags_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.article_tags_id_seq OWNER TO lemalagasy_user;

--
-- Name: article_tags_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: lemalagasy_user
--

ALTER SEQUENCE public.article_tags_id_seq OWNED BY public.article_tags.id;


--
-- Name: categorie; Type: TABLE; Schema: public; Owner: lemalagasy_user
--

CREATE TABLE public.categorie (
    id integer NOT NULL,
    name character varying(100) NOT NULL
);


ALTER TABLE public.categorie OWNER TO lemalagasy_user;

--
-- Name: categorie_id_seq; Type: SEQUENCE; Schema: public; Owner: lemalagasy_user
--

CREATE SEQUENCE public.categorie_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.categorie_id_seq OWNER TO lemalagasy_user;

--
-- Name: categorie_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: lemalagasy_user
--

ALTER SEQUENCE public.categorie_id_seq OWNED BY public.categorie.id;


--
-- Name: category_featured_articles; Type: TABLE; Schema: public; Owner: lemalagasy_user
--

CREATE TABLE public.category_featured_articles (
    id integer NOT NULL,
    id_category integer NOT NULL,
    id_article integer NOT NULL,
    display_order integer DEFAULT 1 NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.category_featured_articles OWNER TO lemalagasy_user;

--
-- Name: category_featured_articles_id_seq; Type: SEQUENCE; Schema: public; Owner: lemalagasy_user
--

CREATE SEQUENCE public.category_featured_articles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.category_featured_articles_id_seq OWNER TO lemalagasy_user;

--
-- Name: category_featured_articles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: lemalagasy_user
--

ALTER SEQUENCE public.category_featured_articles_id_seq OWNED BY public.category_featured_articles.id;


--
-- Name: home_feed; Type: TABLE; Schema: public; Owner: lemalagasy_user
--

CREATE TABLE public.home_feed (
    id integer NOT NULL,
    id_article integer,
    slot public.feed_slot NOT NULL,
    display_order integer DEFAULT 1 NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    starts_at timestamp without time zone,
    ends_at timestamp without time zone,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.home_feed OWNER TO lemalagasy_user;

--
-- Name: home_feed_id_seq; Type: SEQUENCE; Schema: public; Owner: lemalagasy_user
--

CREATE SEQUENCE public.home_feed_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.home_feed_id_seq OWNER TO lemalagasy_user;

--
-- Name: home_feed_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: lemalagasy_user
--

ALTER SEQUENCE public.home_feed_id_seq OWNED BY public.home_feed.id;


--
-- Name: role; Type: TABLE; Schema: public; Owner: lemalagasy_user
--

CREATE TABLE public.role (
    id integer NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE public.role OWNER TO lemalagasy_user;

--
-- Name: role_id_seq; Type: SEQUENCE; Schema: public; Owner: lemalagasy_user
--

CREATE SEQUENCE public.role_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.role_id_seq OWNER TO lemalagasy_user;

--
-- Name: role_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: lemalagasy_user
--

ALTER SEQUENCE public.role_id_seq OWNED BY public.role.id;


--
-- Name: tag; Type: TABLE; Schema: public; Owner: lemalagasy_user
--

CREATE TABLE public.tag (
    id integer NOT NULL,
    name character varying(100) NOT NULL
);


ALTER TABLE public.tag OWNER TO lemalagasy_user;

--
-- Name: tag_id_seq; Type: SEQUENCE; Schema: public; Owner: lemalagasy_user
--

CREATE SEQUENCE public.tag_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tag_id_seq OWNER TO lemalagasy_user;

--
-- Name: tag_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: lemalagasy_user
--

ALTER SEQUENCE public.tag_id_seq OWNED BY public.tag.id;


--
-- Name: utilisateur; Type: TABLE; Schema: public; Owner: lemalagasy_user
--

CREATE TABLE public.utilisateur (
    id integer NOT NULL,
    id_role integer,
    name character varying(100) NOT NULL,
    email character varying(150) NOT NULL,
    date_creation timestamp without time zone DEFAULT now()
);


ALTER TABLE public.utilisateur OWNER TO lemalagasy_user;

--
-- Name: utilisateur_id_seq; Type: SEQUENCE; Schema: public; Owner: lemalagasy_user
--

CREATE SEQUENCE public.utilisateur_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.utilisateur_id_seq OWNER TO lemalagasy_user;

--
-- Name: utilisateur_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: lemalagasy_user
--

ALTER SEQUENCE public.utilisateur_id_seq OWNED BY public.utilisateur.id;


--
-- Name: article id; Type: DEFAULT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.article ALTER COLUMN id SET DEFAULT nextval('public.article_id_seq'::regclass);


--
-- Name: article_authors id; Type: DEFAULT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.article_authors ALTER COLUMN id SET DEFAULT nextval('public.article_authors_id_seq'::regclass);


--
-- Name: article_categories id; Type: DEFAULT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.article_categories ALTER COLUMN id SET DEFAULT nextval('public.article_categories_id_seq'::regclass);


--
-- Name: article_images id; Type: DEFAULT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.article_images ALTER COLUMN id SET DEFAULT nextval('public.article_images_id_seq'::regclass);


--
-- Name: article_status id; Type: DEFAULT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.article_status ALTER COLUMN id SET DEFAULT nextval('public.article_status_id_seq'::regclass);


--
-- Name: article_tags id; Type: DEFAULT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.article_tags ALTER COLUMN id SET DEFAULT nextval('public.article_tags_id_seq'::regclass);


--
-- Name: categorie id; Type: DEFAULT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.categorie ALTER COLUMN id SET DEFAULT nextval('public.categorie_id_seq'::regclass);


--
-- Name: category_featured_articles id; Type: DEFAULT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.category_featured_articles ALTER COLUMN id SET DEFAULT nextval('public.category_featured_articles_id_seq'::regclass);


--
-- Name: home_feed id; Type: DEFAULT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.home_feed ALTER COLUMN id SET DEFAULT nextval('public.home_feed_id_seq'::regclass);


--
-- Name: role id; Type: DEFAULT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.role ALTER COLUMN id SET DEFAULT nextval('public.role_id_seq'::regclass);


--
-- Name: tag id; Type: DEFAULT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.tag ALTER COLUMN id SET DEFAULT nextval('public.tag_id_seq'::regclass);


--
-- Name: utilisateur id; Type: DEFAULT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.utilisateur ALTER COLUMN id SET DEFAULT nextval('public.utilisateur_id_seq'::regclass);


--
-- Data for Name: article; Type: TABLE DATA; Schema: public; Owner: lemalagasy_user
--

COPY public.article (id, title, summary, mongodb_id, published_at, created_at) FROM stdin;
1	Guerre en Iran, jour 31 : Trump menace l’île de Kharg ; des frappes à Téhéran… Le bilan du lundi 30 mars	Le président américain menace d’anéantir l’île de Kharg, site pétrolier vital pour l’Iran, si le détroit d’Ormuz n’est pas rouvert. Un Casque bleu indonésien a été tué au Liban, un autre a été grièvement blessé. Retrouvez les dernières actualités de ce lundi 30 mars sur la guerre au Moyen-Orient.	69cb6b9e0453d8d22f023bb1	\N	2026-03-31 06:37:18.548352
2	Israël : le Parlement vote la loi instaurant la « peine de mort pour les terroristes »	Le Parlement israélien a adopté lundi 30 mars une loi instaurant « la peine de mort pour les terroristes », taillée sur mesure pour ne s’appliquer qu’aux Palestiniens reconnus coupables d’attaques anti-israéliennes meurtrières, l’Autorité palestinienne dénonçant une tentative de « légitimer des exécutions extrajudiciaires ».	69cb7655a07fde86f3039b22	\N	2026-03-31 07:23:01.821146
3	Guerre en Iran : Invasion ou commandos… Trump peut-il vraiment envoyer des troupes au sol ?	Malgré des menaces de plus en plus explicites de Donald Trump, l’hypothèse d’une intervention terrestre américaine en Iran reste improbable	69cb7770940921e309050aa2	\N	2026-03-31 07:27:44.689101
4	Pétrole : après un mois de conflit, une production en chute libre	Un quart du commerce mondial d’or noir par voie maritime a transité par le détroit d’Ormuz en 2025. Depuis le début de la guerre au Moyen-Orient, faute de pouvoir exporter, les pays pétroliers du Golfe ont réduit leur production de 25 % à 80 %, selon les cas.	69cb7875a07fde86f3039b23	\N	2026-03-31 07:32:05.22458
5	Guerre en Iran : pourquoi aucun belligérant n’a intérêt à ce que la guerre s’arrête	Moyen-Orient. Ni les États-Unis, ni Israël, ni l’Iran ne semblent vouloir mettre fin au conflit rapidement. Chacun a sa raison stratégique.	69cb7924940921e309050aa3	\N	2026-03-31 07:35:00.567998
6	Guerre au Moyen-Orient : l’Iran poursuit ses frappes malgré les menaces de Trump, un pétrolier du Koweït attaqué près du port de Dubaï	Plus d’un mois après le début des opérations, le conflit entre les États-Unis, Israël et l’Iran continue d’embraser le Moyen-Orient. Les médias iraniens ont fait état d’explosions et de coupures de courant à Téhéran mardi matin, après plus d’un mois de guerre au Moyen-Orient déclenchée par l’offensive américano-israélienne contre la République islamique.	69cb79c29118a45d650b9e42	\N	2026-03-31 07:37:38.144731
7	L’économie a ralenti fin 2025, avant même la guerre en Iran	LONDRES, 31 mars (Reuters) - L’économie britannique a à peine progressé à la fin de l’année 2025, montrent mardi les données officielles, ce qui pourrait compliquer la tâche du gouvernement pour préserver la croissance économique alors que la guerre en Iran risque de peser sur la demande et de faire grimper l’inflation.	69cb7b57ba2d392a63001152	\N	2026-03-31 07:44:23.456388
8	L’Iran méfiant à l’égard de la diplomatie avec les USA face aux informations faisant état d’une offensive terrestre planifiée	Selon Téhéran, les États-Unis mènent des efforts diplomatiques tout en "planifiant secrètement une attaque terrestre". Ses propos interviennent peu après la parution d’articles dans les médias américains selon lesquelles Donald Trump envisagerait une offensive terrestre de plusieurs semaines.	69cb7bec9118a45d650b9e43	\N	2026-03-31 07:46:52.262714
9	Conflit avec l’Iran : la diplomatie américaine « n’a pas les capacités nécessaires pour trouver une solution »	La volatilité des positions américaines et le manque de confiance mutuelle rendent improbable un cessez-le-feu entre l’Iran, les États-Unis et Israël, selon l’ex-ambassadeur du Canada aux Nations Unies.	69cb7ca749d71e055d0aa2e2	\N	2026-03-31 07:49:59.183784
10	Moyen-Orient : Netanyahou estime qu’Israël a rempli plus de la moitié de ses objectifs de guerre contre l’Iran	« La moitié du chemin est clairement dépassée. Mais je ne veux pas fixer de calendrier », a déclaré le premier ministre israélien, au 31e jour de la guerre contre l’Iran.	69cb7e32551bea00ce094a22	\N	2026-03-31 07:56:34.270098
\.


--
-- Data for Name: article_authors; Type: TABLE DATA; Schema: public; Owner: lemalagasy_user
--

COPY public.article_authors (id, id_article, id_utilisateur) FROM stdin;
1	1	1
3	2	2
4	3	1
5	4	3
6	5	2
7	6	2
8	7	4
9	8	3
10	9	5
11	10	1
\.


--
-- Data for Name: article_categories; Type: TABLE DATA; Schema: public; Owner: lemalagasy_user
--

COPY public.article_categories (id, id_article, id_category) FROM stdin;
1	1	2
3	2	3
4	3	2
5	4	1
6	5	5
7	6	2
8	7	4
9	8	6
10	9	6
11	10	7
\.


--
-- Data for Name: article_images; Type: TABLE DATA; Schema: public; Owner: lemalagasy_user
--

COPY public.article_images (id, id_article, path) FROM stdin;
\.


--
-- Data for Name: article_status; Type: TABLE DATA; Schema: public; Owner: lemalagasy_user
--

COPY public.article_status (id, statut, id_article) FROM stdin;
1	PUBLIE	1
2	PUBLIE	2
3	PUBLIE	3
4	PUBLIE	4
5	PUBLIE	5
6	PUBLIE	6
7	PUBLIE	7
8	PUBLIE	8
9	PUBLIE	9
10	PUBLIE	10
\.


--
-- Data for Name: article_tags; Type: TABLE DATA; Schema: public; Owner: lemalagasy_user
--

COPY public.article_tags (id, id_article, id_tag) FROM stdin;
1	1	1
2	1	2
3	1	5
4	1	8
5	1	9
8	2	4
9	2	6
10	3	1
11	3	2
12	3	4
13	3	5
14	3	8
15	3	9
16	4	3
17	4	10
18	4	12
19	5	1
20	5	2
21	5	3
22	5	4
23	5	5
24	5	8
25	5	9
26	6	1
27	6	3
28	6	9
29	6	12
30	7	2
31	9	1
32	9	5
33	9	10
34	10	2
35	10	4
36	10	8
\.


--
-- Data for Name: categorie; Type: TABLE DATA; Schema: public; Owner: lemalagasy_user
--

COPY public.categorie (id, name) FROM stdin;
1	International
2	Guerre
3	Politique
4	Economie
5	Analyse
6	Diplomatie
7	Sécurité
\.


--
-- Data for Name: category_featured_articles; Type: TABLE DATA; Schema: public; Owner: lemalagasy_user
--

COPY public.category_featured_articles (id, id_category, id_article, display_order, is_active, created_at) FROM stdin;
1	5	5	1	t	2026-03-31 07:39:41.126321
\.


--
-- Data for Name: home_feed; Type: TABLE DATA; Schema: public; Owner: lemalagasy_user
--

COPY public.home_feed (id, id_article, slot, display_order, is_active, starts_at, ends_at, created_at) FROM stdin;
10	1	FEATURED	1	t	\N	\N	2026-03-31 07:47:14.150842
11	4	LATEST	1	t	\N	\N	2026-03-31 07:47:14.150842
12	5	LATEST	2	t	\N	\N	2026-03-31 07:47:14.150842
13	6	LATEST	3	t	\N	\N	2026-03-31 07:47:14.150842
14	8	SPOTLIGHT	1	t	\N	\N	2026-03-31 07:47:14.150842
15	7	SPOTLIGHT	2	t	\N	\N	2026-03-31 07:47:14.150842
\.


--
-- Data for Name: role; Type: TABLE DATA; Schema: public; Owner: lemalagasy_user
--

COPY public.role (id, name) FROM stdin;
1	admin
2	journalist
3	editor
4	superjournalist
\.


--
-- Data for Name: tag; Type: TABLE DATA; Schema: public; Owner: lemalagasy_user
--

COPY public.tag (id, name) FROM stdin;
1	Iran
2	Conflit
3	Moyen-Orient
4	Israel
5	états-Unis
6	Sanctions
7	Nuclèaire
8	Tensions
9	Armée
10	Diplomatie
11	ONU
12	Pétrole
\.


--
-- Data for Name: utilisateur; Type: TABLE DATA; Schema: public; Owner: lemalagasy_user
--

COPY public.utilisateur (id, id_role, name, email, date_creation) FROM stdin;
1	1	Rado Mihaja	rado.mihaja@lemalagasy.com	2026-03-31 06:31:56.090531
2	2	Patrick Randria	patrick.randria@lemalagasy.com	2026-03-31 06:31:56.090531
3	2	Sanda Rakoto	sanda.rakoto@lemalagasy.com	2026-03-31 06:31:56.090531
4	3	Paul Rabary	paul.rabary@lemalagasy.com	2026-03-31 06:31:56.090531
5	4	David Andrianina	david.andrianina@lemalagasy.com	2026-03-31 06:31:56.090531
\.


--
-- Name: article_authors_id_seq; Type: SEQUENCE SET; Schema: public; Owner: lemalagasy_user
--

SELECT pg_catalog.setval('public.article_authors_id_seq', 11, true);


--
-- Name: article_categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: lemalagasy_user
--

SELECT pg_catalog.setval('public.article_categories_id_seq', 11, true);


--
-- Name: article_id_seq; Type: SEQUENCE SET; Schema: public; Owner: lemalagasy_user
--

SELECT pg_catalog.setval('public.article_id_seq', 10, true);


--
-- Name: article_images_id_seq; Type: SEQUENCE SET; Schema: public; Owner: lemalagasy_user
--

SELECT pg_catalog.setval('public.article_images_id_seq', 1, false);


--
-- Name: article_status_id_seq; Type: SEQUENCE SET; Schema: public; Owner: lemalagasy_user
--

SELECT pg_catalog.setval('public.article_status_id_seq', 10, true);


--
-- Name: article_tags_id_seq; Type: SEQUENCE SET; Schema: public; Owner: lemalagasy_user
--

SELECT pg_catalog.setval('public.article_tags_id_seq', 36, true);


--
-- Name: categorie_id_seq; Type: SEQUENCE SET; Schema: public; Owner: lemalagasy_user
--

SELECT pg_catalog.setval('public.categorie_id_seq', 7, true);


--
-- Name: category_featured_articles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: lemalagasy_user
--

SELECT pg_catalog.setval('public.category_featured_articles_id_seq', 1, true);


--
-- Name: home_feed_id_seq; Type: SEQUENCE SET; Schema: public; Owner: lemalagasy_user
--

SELECT pg_catalog.setval('public.home_feed_id_seq', 15, true);


--
-- Name: role_id_seq; Type: SEQUENCE SET; Schema: public; Owner: lemalagasy_user
--

SELECT pg_catalog.setval('public.role_id_seq', 4, true);


--
-- Name: tag_id_seq; Type: SEQUENCE SET; Schema: public; Owner: lemalagasy_user
--

SELECT pg_catalog.setval('public.tag_id_seq', 12, true);


--
-- Name: utilisateur_id_seq; Type: SEQUENCE SET; Schema: public; Owner: lemalagasy_user
--

SELECT pg_catalog.setval('public.utilisateur_id_seq', 5, true);


--
-- Name: article_authors article_authors_pkey; Type: CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.article_authors
    ADD CONSTRAINT article_authors_pkey PRIMARY KEY (id);


--
-- Name: article_categories article_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.article_categories
    ADD CONSTRAINT article_categories_pkey PRIMARY KEY (id);


--
-- Name: article_images article_images_pkey; Type: CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.article_images
    ADD CONSTRAINT article_images_pkey PRIMARY KEY (id);


--
-- Name: article article_pkey; Type: CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.article
    ADD CONSTRAINT article_pkey PRIMARY KEY (id);


--
-- Name: article_status article_status_pkey; Type: CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.article_status
    ADD CONSTRAINT article_status_pkey PRIMARY KEY (id);


--
-- Name: article_tags article_tags_pkey; Type: CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.article_tags
    ADD CONSTRAINT article_tags_pkey PRIMARY KEY (id);


--
-- Name: categorie categorie_name_key; Type: CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.categorie
    ADD CONSTRAINT categorie_name_key UNIQUE (name);


--
-- Name: categorie categorie_pkey; Type: CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.categorie
    ADD CONSTRAINT categorie_pkey PRIMARY KEY (id);


--
-- Name: category_featured_articles category_featured_articles_pkey; Type: CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.category_featured_articles
    ADD CONSTRAINT category_featured_articles_pkey PRIMARY KEY (id);


--
-- Name: home_feed home_feed_pkey; Type: CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.home_feed
    ADD CONSTRAINT home_feed_pkey PRIMARY KEY (id);


--
-- Name: role role_name_key; Type: CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.role
    ADD CONSTRAINT role_name_key UNIQUE (name);


--
-- Name: role role_pkey; Type: CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.role
    ADD CONSTRAINT role_pkey PRIMARY KEY (id);


--
-- Name: tag tag_name_key; Type: CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.tag
    ADD CONSTRAINT tag_name_key UNIQUE (name);


--
-- Name: tag tag_pkey; Type: CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.tag
    ADD CONSTRAINT tag_pkey PRIMARY KEY (id);


--
-- Name: category_featured_articles uq_category_featured_article; Type: CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.category_featured_articles
    ADD CONSTRAINT uq_category_featured_article UNIQUE (id_category, id_article);


--
-- Name: category_featured_articles uq_category_featured_order; Type: CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.category_featured_articles
    ADD CONSTRAINT uq_category_featured_order UNIQUE (id_category, display_order);


--
-- Name: home_feed uq_home_feed_slot_article; Type: CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.home_feed
    ADD CONSTRAINT uq_home_feed_slot_article UNIQUE (slot, id_article);


--
-- Name: home_feed uq_home_feed_slot_order; Type: CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.home_feed
    ADD CONSTRAINT uq_home_feed_slot_order UNIQUE (slot, display_order);


--
-- Name: utilisateur utilisateur_email_key; Type: CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.utilisateur
    ADD CONSTRAINT utilisateur_email_key UNIQUE (email);


--
-- Name: utilisateur utilisateur_pkey; Type: CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.utilisateur
    ADD CONSTRAINT utilisateur_pkey PRIMARY KEY (id);


--
-- Name: idx_category_featured_active_order; Type: INDEX; Schema: public; Owner: lemalagasy_user
--

CREATE INDEX idx_category_featured_active_order ON public.category_featured_articles USING btree (id_category, is_active, display_order);


--
-- Name: idx_home_feed_active_slot_order; Type: INDEX; Schema: public; Owner: lemalagasy_user
--

CREATE INDEX idx_home_feed_active_slot_order ON public.home_feed USING btree (is_active, slot, display_order);


--
-- Name: article_authors article_authors_id_article_fkey; Type: FK CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.article_authors
    ADD CONSTRAINT article_authors_id_article_fkey FOREIGN KEY (id_article) REFERENCES public.article(id);


--
-- Name: article_authors article_authors_id_utilisateur_fkey; Type: FK CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.article_authors
    ADD CONSTRAINT article_authors_id_utilisateur_fkey FOREIGN KEY (id_utilisateur) REFERENCES public.utilisateur(id);


--
-- Name: article_categories article_categories_id_article_fkey; Type: FK CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.article_categories
    ADD CONSTRAINT article_categories_id_article_fkey FOREIGN KEY (id_article) REFERENCES public.article(id);


--
-- Name: article_categories article_categories_id_category_fkey; Type: FK CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.article_categories
    ADD CONSTRAINT article_categories_id_category_fkey FOREIGN KEY (id_category) REFERENCES public.categorie(id);


--
-- Name: article_images article_images_id_article_fkey; Type: FK CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.article_images
    ADD CONSTRAINT article_images_id_article_fkey FOREIGN KEY (id_article) REFERENCES public.article(id);


--
-- Name: article_status article_status_id_article_fkey; Type: FK CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.article_status
    ADD CONSTRAINT article_status_id_article_fkey FOREIGN KEY (id_article) REFERENCES public.article(id);


--
-- Name: article_tags article_tags_id_article_fkey; Type: FK CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.article_tags
    ADD CONSTRAINT article_tags_id_article_fkey FOREIGN KEY (id_article) REFERENCES public.article(id);


--
-- Name: article_tags article_tags_id_tag_fkey; Type: FK CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.article_tags
    ADD CONSTRAINT article_tags_id_tag_fkey FOREIGN KEY (id_tag) REFERENCES public.tag(id);


--
-- Name: category_featured_articles category_featured_articles_id_article_fkey; Type: FK CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.category_featured_articles
    ADD CONSTRAINT category_featured_articles_id_article_fkey FOREIGN KEY (id_article) REFERENCES public.article(id) ON DELETE CASCADE;


--
-- Name: category_featured_articles category_featured_articles_id_category_fkey; Type: FK CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.category_featured_articles
    ADD CONSTRAINT category_featured_articles_id_category_fkey FOREIGN KEY (id_category) REFERENCES public.categorie(id) ON DELETE CASCADE;


--
-- Name: home_feed home_feed_id_article_fkey; Type: FK CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.home_feed
    ADD CONSTRAINT home_feed_id_article_fkey FOREIGN KEY (id_article) REFERENCES public.article(id) ON DELETE CASCADE;


--
-- Name: utilisateur utilisateur_id_role_fkey; Type: FK CONSTRAINT; Schema: public; Owner: lemalagasy_user
--

ALTER TABLE ONLY public.utilisateur
    ADD CONSTRAINT utilisateur_id_role_fkey FOREIGN KEY (id_role) REFERENCES public.role(id);


--
-- PostgreSQL database dump complete
--

\unrestrict C7p4ULtRRt7ylF6YXtNAgh3cmTmTPByoiTYoggyajfHMOxadNfCDyVyBeIPD6IG

