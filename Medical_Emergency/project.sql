

drop table ContactsHospital;
drop table ContactsOtherEmergencyResponders;
drop table ReceivesCall;

drop table RespondsDate;


drop table StaffWorks;
drop table HospitalSends;
drop table Dispatch;
drop table OtherEmergencyResponders;
drop table Caller;
drop table MedicalEmergency;
drop table Ambulance;
drop table Staff;
drop table Civilians;
drop table Hospital;









create table Hospital
    (
        Name varchar(50),
        Address varchar(50),
        primary key (Name)
    );

create table Civilians 
    (
        CivilianID int,
        Name varchar(50),
        primary key (CivilianID)
    );


create table Staff
    (
        StaffId INTEGER,
        Specialty varchar(50),
        HospitalName varchar(50),
        primary key(StaffId),
        foreign key (HospitalName) references Hospital (Name)
    );

    -- /* NOTE THAT ON UPDATE CASCADE IS MISSING BECAUSE ORACLE DOES NOT SUPPORT NEED TO FIND ALTERNATIVE*/

create table Ambulance
    (
        VehicleID int,
        Model varchar(50),
        HospitalName varchar(50),
        primary key(VehicleID),
        foreign key(HospitalName) references Hospital(Name)
    );

create table MedicalEmergency
    (
        EmergencyDate char(8) /*Possibly replace with `date` type?*/,
        EmergencyDescription varchar(100),
        EmergencyLocation varchar(50),
        CivilianID int,
        HospitalName varchar(50),
        primary key (CivilianID, EmergencyDate, EmergencyDescription, EmergencyLocation),
        foreign key (CivilianID) references Civilians(CivilianID) on delete CASCADE,
        /* NOTE THAT ON UPDATE CASCADE IS MISSING BECAUSE ORACLE DOES NOT SUPPORT NEED TO FIND ALTERNATIVE*/
        foreign key (HospitalName) references Hospital(Name)
    );


create table Caller
    (
        CivilianID int,
        PhoneNumber int,
        PhoneSerialNumber int,
        primary key (CivilianID, PhoneNumber),
        foreign key (CivilianID) references Civilians
    );

create table OtherEmergencyResponders
    (
        Responder_Type varchar(50),
        Responder_Location varchar(100),
        primary key (Responder_Type, Responder_Location)
    );

create table Dispatch
    (
        Region varchar(50),
        primary key (Region)
    );

-- /* RELATIONSHIP TABLES */

create table HospitalSends
    (
        HospitalName varchar(50) not null,
        AmbulanceVehicleID int,
        primary key (HospitalName, AmbulanceVehicleID),
        foreign key (HospitalName) references Hospital,
        foreign key (AmbulanceVehicleID) references Ambulance
    );

create table StaffWorks
    (
        StaffID int unique,
        HospitalName varchar(50) not null,
        primary key (HospitalName, StaffID),
        foreign key (StaffID) references Staff on delete cascade,
        /* NOTE THAT ON UPDATE CASCADE IS MISSING BECAUSE ORACLE DOES NOT SUPPORT NEED TO FIND ALTERNATIVE*/
        foreign key (HospitalName) references Hospital on delete cascade
        /* NOTE THAT ON UPDATE CASCADE IS MISSING BECAUSE ORACLE DOES NOT SUPPORT NEED TO FIND ALTERNATIVE*/
    );


create table RespondsDate
    (
        EmergencyDate char(8),
        EmergencyDescription varchar(100),
        CivilianID int,
        EmergencyLocation varchar(50),
        primary key (CivilianID, EmergencyDescription),
        foreign key (CivilianID, EmergencyDate, EmergencyDescription, EmergencyLocation) references MedicalEmergency(CivilianID, EmergencyDate, EmergencyDescription, EmergencyLocation),
        foreign key (CivilianID) references Civilians
    );


create table ReceivesCall
    (
        CivilianID int,
        PhoneNumber int,
        DispatchRegion varchar(50),
        primary key (CivilianID, PhoneNumber, DispatchRegion),
        foreign key (DispatchRegion) references Dispatch(Region),
        foreign key (PhoneNumber, CivilianID) references Caller(PhoneNumber, CivilianID)
        -- foreign key (CivilianID) references Civilians
    );

create table ContactsOtherEmergencyResponders
    (
        OtherEmergencyRespondersLocation varchar(100),
        OtherEmergencyRespondersType varchar(50),
        DispatchRegion varchar(50),
        primary key (OtherEmergencyRespondersLocation, OtherEmergencyRespondersType, DispatchRegion),
        foreign key (OtherEmergencyRespondersLocation, OtherEmergencyRespondersType) references OtherEmergencyResponders(Responder_Location, Responder_Type),
        foreign key (DispatchRegion) references Dispatch
    );

create table ContactsHospital
    (
        HospitalName varchar(50),
        DispatchRegion varchar(50),
        primary key (HospitalName, DispatchRegion),
        foreign key (HospitalName) references Hospital,
        foreign key (DispatchRegion) references Dispatch
    );

insert into Hospital
values ('Lions Gate Hospital', '231 E 15th St, North Vancouver, BC');

insert into Hospital
values ('Vancouver General Hospital', '899 West 12th, Avenue Vancouver, BC');

insert into Hospital
values ('Burnaby Hospital', '3935 Kincaid St, Burnaby, BC');

insert into Hospital
values ('Our Lady of Reluctance Hospital', '3397 Pine Tree Lane, Garden City, SK');

insert into Hospital
values ('Grey Sloan Memorial Hospital', '645 Jarvisville Road, Walton Town, ON');

insert into Civilians
values (729384705, 'Alexander Vidal');

insert into Civilians
values (984562351, 'Tara Strong');

insert into Civilians
values (458657912, 'Dante Bosco');

insert into Civilians
values (936532402, 'Erika Yamamoto');

insert into Civilians
values (202118237, 'Alberto Reyes');

insert into Civilians
values (658492510, 'Markos Eddy Chapman');

insert into Civilians
values (205678493, 'Jordane Mathiasen');

insert into Civilians
values (639854207, 'Manuel Mathiasen');

insert into Caller
values (729384705, 6047332231, 123456789);

insert into Caller
values (984562351, 2507223621, 987654321);

insert into Caller
values (936532402, 2505902648, 876543212);

insert into Caller
values (658492510, 6042553911, 457812369);

insert into Caller
values (205678493, 6045909011, 154879643);

insert into MedicalEmergency
values ('05062022', 'fell off a ladder and broke his leg', '1118 W 24th Ave, North Vancouver, BC', 729384705, 'Lions Gate Hospital');

insert into MedicalEmergency
values ('08022020', 'Had a heart attack', '4469 Kingsway, Burnaby, BC', 984562351, 'Vancouver General Hospital');

insert into MedicalEmergency
values ('18012021', 'Entered labour during a walk', '4519 Piper Avenue, Burnaby, BC', 936532402, 'Burnaby Hospital');

insert into MedicalEmergency
values ('15081999', 'Shot in the leg during a mass shooting', '3397 Pine Tree Lane, Garden City, SK', 658492510, 'Our Lady of Reluctance Hospital');

insert into MedicalEmergency
values ('26052002', 'Tree branch fell on him, threatening his internal organs', '272 Columbia Mine Road, Walton Town, ON', 205678493, 'Grey Sloan Memorial Hospital');

insert into Ambulance
values (12178, 'patient transport ambulance', 'Lions Gate Hospital');

insert into Ambulance
values (17030, 'Patient transport Ambulance', 'Vancouver General Hospital');

insert into Ambulance
values (19102, 'Neonatal ambulance', 'Burnaby Hospital');

insert into Ambulance
values (2384, 'ambulance bus', 'Our Lady of Reluctance Hospital');

insert into Ambulance
values (9918, 'Patient Transport Ambulance', 'Grey Sloan Memorial Hospital');

insert into Staff
values (7132, 'General Practitioner', 'Lions Gate Hospital');

insert into Staff
values (8428, 'Cardiologist', 'Vancouver General Hospital');

insert into Staff
values (8365, 'Midwife', 'Burnaby Hospital');

insert into Staff
values (6545, 'Trauma Care', 'Our Lady of Reluctance Hospital');

insert into Staff
values (472, 'Orthopedician', 'Grey Sloan Memorial Hospital');

insert into Staff
values (291, 'Pediatric Surgeon', 'Grey Sloan Memorial Hospital');

insert into Staff
values (773, 'General Surgeon', 'Grey Sloan Memorial Hospital');

insert into Staff
values (536, 'Plastic Surgeon', 'Grey Sloan Memorial Hospital');

insert into Staff
values (513, 'Anesthesiologist', 'Grey Sloan Memorial Hospital');


insert into Dispatch
values ('BC');

insert into Dispatch
values ('SK');

insert into Dispatch
values ('ON');

insert into OtherEmergencyResponders
values ('Fire Hall', '4151 Cunningham Court, Wausau County, Manitoba');

insert into OtherEmergencyResponders
values ('Police Station', '4997 Zappia Drive, Lexington, City, Saskatchewan');

insert into OtherEmergencyResponders
values ('Fire Station', '1999 Lynn Ogden Lane, Beaumont, TownShip, Quebec');

insert into OtherEmergencyResponders
values ('Police Department', '3758 Florence Street, Garden City, SK');

insert into OtherEmergencyResponders
values ('Fire Department', '1072 Summit Park Avenue, Walton Town, ON');

insert into HospitalSends
values ('Lions Gate Hospital', 12178);

insert into HospitalSends
values ('Vancouver General Hospital', 17030);

insert into HospitalSends
values ('Burnaby Hospital', 19102);

insert into HospitalSends
values ('Our Lady of Reluctance Hospital', 2384);

insert into HospitalSends
values ('Grey Sloan Memorial Hospital', 9918);

insert into StaffWorks
values (7132, 'Lions Gate Hospital');

insert into StaffWorks
values (8428, 'Vancouver General Hospital');

insert into StaffWorks
values (8365, 'Burnaby Hospital');

insert into StaffWorks
values (6545, 'Our Lady of Reluctance Hospital');

insert into StaffWorks
values (472, 'Grey Sloan Memorial Hospital');

insert into StaffWorks
values (291, 'Grey Sloan Memorial Hospital');

insert into StaffWorks
values (773, 'Grey Sloan Memorial Hospital');

insert into StaffWorks
values (536, 'Grey Sloan Memorial Hospital');

insert into StaffWorks
values (513, 'Grey Sloan Memorial Hospital');

insert into ReceivesCall
values(729384705, 6047332231, 'BC') ;

-- insert into ReceivesCall
-- values (458657912, 2507223621, 'BC');

insert into ReceivesCall
values (984562351, 2507223621, 'BC');

insert into ReceivesCall
values (936532402, 2505902648, 'BC');

insert into ReceivesCall
values (658492510, 6042553911, 'SK');

insert into ReceivesCall
values (205678493, 6045909011, 'ON');

insert into ContactsOtherEmergencyResponders
values ('3758 Florence Street, Garden City, SK', 'Police Department', 'SK');

insert into ContactsOtherEmergencyResponders
values ('1072 Summit Park Avenue, Walton Town, ON', 'Fire Department', 'ON');

insert into ContactsOtherEmergencyResponders
values ('4151 Cunningham Court, Wausau County, Manitoba', 'Fire Hall', 'ON');

insert into ContactsOtherEmergencyResponders
values ('4997 Zappia Drive, Lexington, City, Saskatchewan', 'Police Station', 'ON');

insert into ContactsOtherEmergencyResponders
values ('1999 Lynn Ogden Lane, Beaumont, TownShip, Quebec', 'Fire Station', 'ON');

insert into ContactsOtherEmergencyResponders
values ('3758 Florence Street, Garden City, SK', 'Police Department', 'ON');

insert into ContactsHospital
values ('Lions Gate Hospital', 'BC');

insert into ContactsHospital
values ('Vancouver General Hospital', 'BC');

insert into ContactsHospital
values ('Burnaby Hospital', 'BC');

insert into ContactsHospital
values ('Our Lady of Reluctance Hospital', 'SK');

insert into ContactsHospital
values ('Grey Sloan Memorial Hospital', 'ON');
