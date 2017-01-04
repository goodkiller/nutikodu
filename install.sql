CREATE ROLE nutikodu LOGIN ENCRYPTED PASSWORD 'md518ac597c58394e51ebccff0b47e2bd6f' VALID UNTIL 'infinity';

CREATE DATABASE nutikodu WITH ENCODING='UTF8' OWNER=nutikodu CONNECTION LIMIT=-1;

-- public.dashboards
CREATE TABLE public.dashboards
(
	id serial NOT NULL,
	title character varying(100) NOT NULL,
	create_date timestamp with time zone NOT NULL DEFAULT now(),
	CONSTRAINT "PK_DSH_ID" PRIMARY KEY (id)
);
ALTER TABLE public.dashboards OWNER TO nutikodu;
INSERT INTO public.dashboards (title) VALUES('Minu kodu');

-- public.dashboard_items
CREATE TABLE public.dashboard_items
(
	id serial NOT NULL,
	dashboard_id integer NOT NULL,
	item_id integer NOT NULL,
	width integer NOT NULL DEFAULT 1,
	height integer NOT NULL DEFAULT 1,
	bg_color character(7) NOT NULL DEFAULT '#2D89EF'::bpchar,
	event_type character varying(50),
	CONSTRAINT "PK_DSH_ITM_ID" PRIMARY KEY (id),
	CONSTRAINT "FK_DSH_ITM_DSH_ID" FOREIGN KEY (dashboard_id) REFERENCES public.dashboards (id) MATCH SIMPLE ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "FK_DSH_ITM_ITM_ID" FOREIGN KEY (item_id) REFERENCES public.items (id) MATCH SIMPLE ON UPDATE CASCADE ON DELETE CASCADE
);
ALTER TABLE public.dashboard_items OWNER TO nutikodu;
CREATE INDEX "IDX_DSH_ITM_DSH_ID" ON public.dashboard_items USING btree (dashboard_id);
CREATE INDEX "IDX_DSH_ITM_ITM_ID" ON public.dashboard_items USING btrees (item_id);



-- Zway settings
INSERT INTO settings (key) VALUES('zway_controller_addr');
INSERT INTO settings (key,val) VALUES('zway_controller_port', 8083);
INSERT INTO settings (key,val) VALUES('zway_controller_user', 'admin');
INSERT INTO settings (key,val) VALUES('zway_controller_password', 'raspberry');

-- MiLight settings
INSERT INTO settings (key,val) VALUES('milight_file_path', '/opt/milight/');
INSERT INTO settings (key,val) VALUES('milight_ssh_port', 22);
INSERT INTO settings (key,val) VALUES('milight_ssh_host', 'localhost');
INSERT INTO settings (key,val) VALUES('milight_ssh_user', 'pi');
INSERT INTO settings (key,val) VALUES('milight_ssh_password', 'raspberry');

-- AccuWeather
INSERT INTO settings (key,val) VALUES('accuweather_location_id', '131136');
INSERT INTO settings (key,val) VALUES('accuweather_api_key', 'hoArfRosT1215');

-- Pioneer VSX
INSERT INTO settings (key,val) VALUES('pioneer_vsx_hostname', '84.50.238.187');



