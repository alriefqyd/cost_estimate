update man_powers set skill_level = 'skilled' where skill_level = 'Skilled';
update man_powers set skill_level = 'semi_skilled' where skill_level = 'Semi Skilled';
update man_powers set skill_level = 'unskilled' where skill_level = 'Un Skilled';

insert into settings (setting_type,setting_name,setting_value,setting_code) values ('MAN_POWER','MAN_POWER_SAFETY_RATE',590.9,'MPSAFETY');
