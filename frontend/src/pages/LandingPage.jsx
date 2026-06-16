import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { motion } from 'framer-motion'; // Added Framer Motion

// Components
import { Button, Drawer, Row, Col, Typography } from 'antd';

// Icons
import { FaFacebook } from 'react-icons/fa';

// Assets
import Logo from '../../public/logo.svg';
import Hero from '../../public/images/Hero.png';
import AboutUs from '../../public/images/AboutUs.png';
import Hero1 from '../../public/images/Hero1.png';
import ATEC from '../../public/images/ATEC.png';

const { Title, Paragraph, Text } = Typography;

// --- ANIMATION VARIANTS ---
const fadeInUp = {
    hidden: { opacity: 0, y: 40 },
    visible: { opacity: 1, y: 0, transition: { duration: 0.6, ease: "easeOut" } }
};

const staggerContainer = {
    hidden: { opacity: 0 },
    visible: { opacity: 1, transition: { staggerChildren: 0.2 } }
};

export default function LandingPage() {
    const [visible, setVisible] = useState(false);
    const [scrolled, setScrolled] = useState(false);
    const navigate = useNavigate();

    const onClose = () => setVisible(false);

    // Track scroll for navbar effect
    useEffect(() => {
        const handleScroll = () => setScrolled(window.scrollY > 50);
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    // Smooth Scroll Handler
    const scrollToSection = (e, id) => {
        e.preventDefault();
        const element = document.getElementById(id);
        if (element) {
            const offset = 100;
            const elementPosition = element.getBoundingClientRect().top + window.pageYOffset;
            window.scrollTo({
                top: elementPosition - offset,
                behavior: 'smooth',
            });
        }
        onClose();
    };

    const NavLinks = ({ mobile = false }) => (
        <ul className={`${mobile ? 'flex flex-col gap-6' : 'hidden lg:flex items-center gap-10'}`}>
            <li><a href="#home" onClick={(e) => scrollToSection(e, 'home')} className="text-[#063F5C] font-semibold text-lg hover:text-[#2598FE] transition-colors">Home</a></li>
            <li><a href="#about" onClick={(e) => scrollToSection(e, 'about')} className="text-gray-400 font-semibold text-lg hover:text-[#2598FE] transition-colors">About</a></li>
            <li><a href="#academics" onClick={(e) => scrollToSection(e, 'academics')} className="text-gray-400 font-semibold text-lg hover:text-[#2598FE] transition-colors">Academics</a></li>
            <li><a href="#contact" onClick={(e) => scrollToSection(e, 'contact')} className="text-gray-400 font-semibold text-lg hover:text-[#2598FE] transition-colors">Contact Us</a></li>
        </ul>
    );

    const coreValues = [
        { icon: "bx bxs-graduation", title: "ATTITUDE", desc: "ATEC believes that the right attitude leads to righteousness, discipline, and personal growth." },
        { icon: "bx bxs-group", title: "TEAMWORK", desc: "ATEC values teamwork as a foundation for achieving its mission, vision, and shared goals." },
        { icon: "bx bxs-heart", title: "EMPOWERMENT", desc: "ATEC empowers graduates through skills, knowledge, and passion for community and economic development." },
        { icon: "bx bxs-book-alt", title: "CHRIST - CENTERED", desc: "ATEC upholds Christ-centered principles in all its actions, decisions, and institutional practices." },
    ];

    const TechVocCard = ({ id, title, icon, items }) => (
        <motion.div
            variants={fadeInUp}
            whileHover={{ y: -10 }}
            className="bg-white rounded-[50px] overflow-hidden shadow-[0_15px_60px_rgba(0,0,0,0.08)] border border-gray-100 flex flex-col transition-all duration-500"
        >
            <div className="bg-[#0f172a] p-10 flex flex-row items-center gap-6 text-left">
                <div className="w-24 h-24 flex-shrink-0 bg-white rounded-full flex items-center justify-center shadow-lg">
                    <i className={`${icon} text-5xl text-[#0f172a]`}></i>
                </div>
                <div className="flex flex-col">
                    <h4 className="font-black text-4xl text-white tracking-tight leading-none">{id}</h4>
                    <p className="text-[11px] text-gray-400 font-bold uppercase tracking-widest mt-2">{title}</p>
                </div>
            </div>
            <div className="p-10 flex-grow bg-white">
                <div className="space-y-4">
                    {items.map((item) => (
                        <div key={item} className="flex items-start gap-3">
                            <i className="bx bx-check-circle text-[#2598FE] text-xl mt-0.5"></i>
                            <span className="text-slate-700 font-semibold text-base leading-snug">{item}</span>
                        </div>
                    ))}
                </div>
            </div>
        </motion.div>
    );

    return (
        <div className="font-sora text-slate-800 bg-white overflow-x-hidden">

            {/* --- HERO & NAVIGATION --- */}
            <section className="relative w-full">
                <header className={`fixed top-0 left-0 w-full z-50 transition-all duration-500 flex justify-center items-start pointer-events-none ${scrolled ? 'py-4' : 'py-6 md:py-10'}`}>
                    <nav className={`w-full max-w-[95%] md:max-w-[85%] h-14 md:h-20 transition-all duration-500 rounded-full flex items-center justify-between px-6 md:px-10 border pointer-events-auto
                        ${scrolled ? 'bg-white/90 backdrop-blur-md shadow-2xl border-gray-100' : 'bg-white shadow-lg border-gray-100'}`}>
                        <div className="flex items-center gap-2 md:gap-3">
                            <img src={Logo} alt="Logo" className="w-8 h-8 md:w-11 md:h-11 rounded-full object-contain" />
                            <span className="text-xl md:text-2xl font-bold tracking-tight text-slate-900">ATEC</span>
                        </div>
                        <NavLinks />
                        <div className="flex items-center gap-2">
                            <Button
                                onClick={() => navigate('/login')}
                                shape="round"
                                className="hidden lg:block border-[#063F5C] text-[#063F5C] font-bold uppercase text-[14px] md:text-[15px] tracking-wider h-8 md:h-10 px-10 md:px-16 hover:!bg-[#2598FE] hover:!text-white transition-all"
                            >Log In</Button>
                            <button onClick={() => setVisible(true)} className="lg:hidden flex items-center justify-center text-3xl text-[#063F5C] p-2">
                                <i className='bx bx-menu-alt-right'></i>
                            </button>
                        </div>
                    </nav>

                    <Drawer placement="right" onClose={onClose} open={visible} width={280} closable={false}>
                        <div className="flex justify-end"><button onClick={onClose} className="text-4xl text-gray-400"><i className='bx bx-x'></i></button></div>
                        <div className="flex flex-col h-full justify-between pb-10">
                            <NavLinks mobile={true} />
                            <Button
                                onClick={() => navigate('/login')}
                                block shape="round"
                                className="border-[#063F5C] text-[#063F5C] font-bold uppercase h-12">
                                Log In
                            </Button>
                        </div>
                    </Drawer>
                </header>

                <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} transition={{ duration: 1 }} className="w-full">
                    <img src={Hero} alt="Hero Banner" className="w-full h-auto block" />
                </motion.div>
            </section>

            {/* --- HERO CONTENT --- */}
            <section className="pt-20 pb-10 bg-white text-center px-4 font-sora" id="home">
                <motion.div initial="hidden" whileInView="visible" viewport={{ once: true }} variants={staggerContainer} className="max-w-4xl mx-auto">
                    <motion.h1 variants={fadeInUp} className="text-5xl md:text-7xl font-extrabold text-slate-900 mb-2 tracking-tight">
                        Creating Competitive
                    </motion.h1>
                    <motion.h1 variants={fadeInUp} className="text-5xl md:text-7xl font-extrabold text-[#2598FE] mb-8 tracking-tight">
                        Students, Globally.
                    </motion.h1>
                    <motion.p variants={fadeInUp} className="text-[#6A6A6A] text-lg md:text-xl mb-10 max-w-3xl mx-auto leading-relaxed">
                        ATEC Technological College provides students with quality and technology-driven education that enhances knowledge,
                        skills, and professional growth. The institution aims to develop globally competitive, innovative, and morally responsible
                        individuals prepared for future success.
                    </motion.p>
                    <motion.div variants={fadeInUp}>
                        <Button type="primary" shape="round" className="bg-[#0f172a] h-14 min-w-[180px] md:min-w-[200px] text-xl font-bold shadow-xl border-none hover:!bg-[#2598FE] transition-all font-sora">Get Started</Button>
                    </motion.div>
                </motion.div>
            </section>

            {/* --- CORE VALUES --- */}
            <section className="px-6 md:px-10 py-20 bg-gray-50/30">
                <motion.div
                    initial="hidden" whileInView="visible" viewport={{ once: true, amount: 0.3 }} variants={staggerContainer}
                    className="max-w-7xl mx-auto bg-white rounded-[40px] shadow-xl border border-[#9C9C9C] flex flex-col md:flex-row items-stretch p-8 md:p-12"
                >
                    {coreValues.map((item, idx) => (
                        <motion.div key={idx} variants={fadeInUp} className={`flex-1 flex flex-col items-center text-center px-6 py-6 relative ${idx !== coreValues.length - 1 ? 'md:after:content-[""] md:after:absolute md:after:right-0 md:after:top-1/2 md:after:-translate-y-1/2 md:after:h-32 md:after:w-[1px] md:after:bg-gray-200' : ''}`}>
                            <motion.div whileHover={{ scale: 1.1, rotate: 5 }} className="w-24 h-24 bg-[#F3F4F6] rounded-full flex items-center justify-center mb-6 transition-colors">
                                <i className={`${item.icon} text-5xl text-[#2598FE]`}></i>
                            </motion.div>
                            <h3 className="font-extrabold text-2xl mb-4 text-black tracking-tight uppercase leading-none">{item.title}</h3>
                            <p className="text-normal text-[#9C9C9C] font-regular leading-relaxed">{item.desc}</p>
                        </motion.div>
                    ))}
                </motion.div>
            </section>

            {/* --- ABOUT SECTION --- */}
            <section className="py-24 px-6 md:px-10 max-w-[1400px] mx-auto font-sora" id="about">
                <motion.div initial="hidden" whileInView="visible" viewport={{ once: true }} variants={fadeInUp} className="text-center mb-20">
                    <span className="bg-[#1e293b] text-white text-xs px-6 py-2 rounded-full font-bold uppercase tracking-widest shadow-sm">About</span>
                    <h2 className="text-4xl md:text-6xl font-bold text-slate-950 mt-6 tracking-tight">Institutional Profile</h2>
                    <p className="text-gray-400 text-xl mt-4 font-regular leading-relaxed max-w-3xl mx-auto">Building Skills, Shaping Futures</p>
                </motion.div>

                <div className="grid lg:grid-cols-[1.5fr_0.5fr] gap-10 items-center">
                    <motion.div
                        initial={{ x: -100, opacity: 0 }} whileInView={{ x: 0, opacity: 1 }} transition={{ duration: 0.8 }} viewport={{ once: true }}
                        className="!bg-white p-10 md:p-28 rounded-[40px] md:rounded-[50px] border-1 border-gray-100 shadow-2xl"
                    >
                        <h3 className="text-6xl md:text-7xl font-black text-[#0277EA] mb-10 tracking-tighter">ABOUT US</h3>
                        <p className="text-slate-700 leading-[1.8] text-lg md:text-xl font-regular font-sora">
                            ATEC Technological College is a private educational institution located in Apalit, Pampanga, Philippines.
                            It is committed to providing quality and accessible education through its academic and technical-vocational
                            programs that focus on skills development, competency-based learning, and values formation. The institution aims
                            to produce globally competitive graduates who are well-prepared for employment, entrepreneurship, and further studies,
                            making it one of the promising schools in the region.
                        </p>
                    </motion.div>
                    <motion.div initial={{ x: 100, opacity: 0 }} whileInView={{ x: 0, opacity: 1 }} transition={{ duration: 0.8 }} viewport={{ once: true }} className="p-6 overflow-hidden h-fit">
                        <img src={AboutUs} alt="ATEC Building" className="w-full h-auto object-cover rounded-[30px]" />
                    </motion.div>
                </div>
            </section>

            {/* --- MISSION & VISION --- */}
            <section className="relative py-24 px-6 md:px-10 mt-20 font-sora bg-white overflow-hidden">
                <div className="absolute top-0 left-0 w-full h-32 md:h-40 z-0 pointer-events-none">
                    <svg viewBox="0 0 1440 120" fill="none" className="w-full h-full" preserveAspectRatio="none">
                        <path d="M-10 20H140C148 20 152 24 150 30L95 100C93 106 87 110 80 110H-10V20Z" fill="#0f172a" />
                        <path d="M175 20H1450V110H125C117 110 113 106 115 100L170 30C172 24 178 20 185 20H175Z" fill="#0f172a" />
                    </svg>
                </div>

                <div className="relative z-10 px-20 md:px-25 max-w-8xl mx-auto mt-15">
                    <motion.div initial="hidden" whileInView="visible" viewport={{ once: true }} variants={staggerContainer} className="grid grid-cols-1 lg:grid-cols-[1.2fr_1fr_1fr] gap-12 items-center">
                        <motion.div variants={fadeInUp} className="lg:pr-10">
                            <h2 className="text-4xl md:text-5xl font-bold text-slate-900 leading-[1.1] tracking-tight">Shaping <span className="text-[#2598FE]">Purpose</span> and <span className="text-[#2598FE]">Direction</span></h2>
                            <p className="text-gray-500 mt-8 text-xl font-Regular leading-relaxed max-w-md">ATEC develops competent, values-driven, globally competitive individuals through quality education.</p>
                        </motion.div>

                        <motion.div variants={fadeInUp} whileHover={{ y: -10 }} className="bg-white px-10 py-16 md:py-24 rounded-[50px] shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-gray-100 flex flex-col items-center text-center h-full min-h-[550px] transition-all">
                            <div className="w-24 h-24 bg-[#E5E7EB] rounded-full flex items-center justify-center mb-10"><i className='bx bx-bulb text-5xl text-[#2598FE]'></i></div>
                            <h4 className="font-black text-2xl mb-8 tracking-widest text-black uppercase leading-none">MISSION</h4>
                            <p className="text-lg text-gray-500 leading-[1.8] font-medium max-w-[320px]">
                                ATEC Technological College aims to become one of the leading technological institutions offering 
                                industry-driven courses and producing highly skilled and morally upright individuals who create an 
                                impact to the society and contribute to the nation's progress.</p>
                        </motion.div>

                        <motion.div variants={fadeInUp} whileHover={{ y: -10 }} className="bg-white px-10 py-16 md:py-24 rounded-[50px] shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-gray-100 flex flex-col items-center text-center h-full min-h-[550px] transition-all">
                            <div className="w-24 h-24 bg-[#E5E7EB] rounded-full flex items-center justify-center mb-10"><i className='bx bx-show text-5xl text-[#2598FE]'></i></div>
                            <h4 className="font-black text-2xl mb-8 tracking-widest text-black uppercase leading-none">VISION</h4>
                            <p className="text-lg text-gray-500 leading-[1.8] font-medium max-w-[320px]">
                                ATEC Technological College aims to become one of the leading technological institutions 
                                offering industry-driven courses and producing highly skilled and morally upright individuals
                                 who create an impact to the society and contribute to the nation's progress.
                            </p>
                        </motion.div>
                    </motion.div>
                </div>
            </section>

            {/* --- ACADEMICS SECTION --- */}
            <section className="py-24 px-6 md:px-10 bg-white font-sora" id="academics">
                <div className="max-w-7xl mx-auto">
                    <motion.div initial="hidden" whileInView="visible" viewport={{ once: true }} variants={fadeInUp} className="text-center mb-20">
                        <span className="bg-[#1e293b] text-white text-xs px-6 py-2 rounded-full font-bold uppercase tracking-widest shadow-sm">Academics</span>
                        <h2 className="text-4xl md:text-6xl font-bold text-slate-950 mt-6 tracking-tight">Senior High School Program</h2>
                        <p className="text-gray-400 text-xl mt-4 font-regular leading-relaxed max-w-3xl mx-auto">Prepares students for college, work, and future success.</p>
                    </motion.div>

                    <div className="mb-24">
                        <h3 className="text-center font-extrabold text-3xl mb-12 text-slate-900 flex items-center justify-center gap-4">
                            <div className="h-[2px] w-12 bg-gray-200"></div>Academic Strand<div className="h-[2px] w-12 bg-gray-200"></div>
                        </h3>
                        <motion.div initial="hidden" whileInView="visible" viewport={{ once: true }} variants={staggerContainer} className="grid md:grid-cols-3 gap-8">
                            {[
                                { id: 'ABM', title: 'Accountancy, Business and Management', icon: 'bx bx-trending-up' },
                                { id: 'HUMSS', title: 'Humanities and Social Sciences', icon: 'bx bx-group' },
                                { id: 'GAS', title: 'General Academic Strand', icon: 'bx bx-cog' },
                            ].map((strand) => (
                                <motion.div key={strand.id} variants={fadeInUp} whileHover={{ scale: 1.05 }} className="bg-white rounded-[30px] shadow-xl border border-gray-100 flex items-center relative overflow-hidden group transition-all">
                                    <div className="w-4 h-full bg-[#0f172a] absolute left-0 top-0"></div>
                                    <div className="p-8 pl-12 flex items-center gap-6 w-full">
                                        <div className="w-20 h-20 bg-gray-100 rounded-full flex-shrink-0 flex items-center justify-center"><i className={`${strand.icon} text-4xl text-[#0f172a]`}></i></div>
                                        <div><h4 className="font-black text-3xl text-slate-900 leading-none">{strand.id}</h4><p className="text-sm text-gray-400 font-bold mt-2 leading-tight uppercase">{strand.title}</p></div>
                                    </div>
                                </motion.div>
                            ))}
                        </motion.div>
                    </div>

                    <div className="grid md:grid-cols-3 gap-10">
                        <TechVocCard id="HE" title="Home Economics" icon="bx bx-restaurant" items={['Dressmaking NC II',
    'Beauty & Nail Care NC II',
    'Housekeeping NC II',
    'Front Office Services NC II',
    'Hair Dressing NC II',
    'Wellness Massage NC II',
    'Bartending NC II',
    'Food and Beverage NC II']} />
                        <TechVocCard id="ICT" title="ICT" icon="bx bx-laptop" items={['Computer System Servicing NC II', 'Computer Programming']} />
                        <TechVocCard id="IA" title="Industrial Arts" icon="bx bx-wrench" items={['Shielded Metal Arc Welding NC II', 'EPAS NC II']} />
                    </div>
                </div>
            </section>

            {/* --- CONTACT SECTION --- */}
            <section className="relative py-28 px-6 overflow-hidden min-h-[800px] flex items-center" id="contact">
                <div className="absolute inset-0 z-0"><img src={Hero1} className="w-full h-full object-cover" alt="Contact Background" /></div>
                <div className="max-w-7xl mx-auto relative z-10 w-full">
                    <Row gutter={[64, 108]} align="middle">
                        <Col xs={24} lg={12}>
                            <motion.div initial={{ opacity: 0, x: -50 }} whileInView={{ opacity: 1, x: 0 }} transition={{ duration: 0.8 }} viewport={{ once: true }}>
                                <Title level={1} className="!text-white !text-7xl !font-extrabold !mb-0 !leading-[1.1] !tracking-tighter drop-shadow-2xl">We're Here to <br /> Help</Title>
                                <div className="w-48 h-[1.5px] bg-white my-10 shadow-lg"></div>
                                <Paragraph className="!text-white !text-xl !leading-relaxed !font-regular !mb-0 drop-shadow-md">Have questions? Our team is always ready to guide you every step of the way.</Paragraph>
                                <div className="flex items-center gap-5 group w-fit mt-10">
                                    <div className="w-16 h-16 bg-[#0f172a] rounded-full flex items-center justify-center transition-all shadow-xl"><i className='bx bx-envelope text-3xl text-white'></i></div>
                                    <div><Text className="block !text-white/70 !text-xs !font-bold !uppercase !tracking-widest">Email Us</Text><Text className="!text-white !text-xl !font-bold">atec_collegeapalit@yahoo.com</Text></div>
                                </div>
                            </motion.div>
                        </Col>
                        <Col xs={24} lg={12}>
                            <motion.div initial={{ opacity: 0, x: 50 }} whileInView={{ opacity: 1, x: 0 }} transition={{ duration: 0.8 }} viewport={{ once: true }} className="flex flex-col gap-8">
                                {['Apalit Branch', 'Bulacan Branch'].map((branch, i) => (
                                    <motion.div key={branch} whileHover={{ x: 10 }} className="bg-white p-8 rounded-[50px] flex items-center gap-8 shadow-[0_30px_60px_rgba(0,0,0,0.3)] border border-gray-100">
                                        <div className="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center flex-shrink-0"><i className='bx bxs-map text-5xl text-[#0277EA]'></i></div>
                                        <div><h4 className="text-4xl font-extrabold text-[#0f172a] mb-2">{branch}</h4><p className="text-lg font-semibold text-slate-500 m-0">{i === 0 ? 'San Vicente, Apalit Pampanga' : 'Sta. Rita, Guiguinto, Bulacan'}</p></div>
                                    </motion.div>
                                ))}
                            </motion.div>
                        </Col>
                    </Row>
                </div>
            </section>

            {/* --- FOOTER --- */}
            <footer className="bg-white relative z-10">
                <div className="w-full h-32 md:h-40 relative overflow-hidden">
                    <svg viewBox="0 0 1440 120" fill="none" className="w-full h-full" preserveAspectRatio="none">
                        <path d="M-10 20H140C148 20 152 24 150 30L95 100C93 106 87 110 80 110H-10V20Z" fill="#0f172a" />
                        <path d="M175 20H1450V110H125C117 110 113 106 115 100L170 30C172 24 178 20 185 20H175Z" fill="#0f172a" />
                    </svg>
                </div>

                <div className="max-w-7xl mx-auto pt-14 pb-12 px-8">
                    <Row gutter={[48, 48]}>
                        <Col xs={24} lg={10}>
                            <img src={ATEC} alt="ATEC Logo" className="h-20 w-auto object-contain mb-8" />
                            <p className="text-[13px] text-slate-700 leading-relaxed max-w-sm font-medium mb-10">ATEC Technological College provides quality and technology-driven education...</p>
                            <p className="text-[13px] text-slate-800 font-bold">@2023 ATEC Technological College</p>
                        </Col>
                        <Col xs={24} md={12} lg={7}>
                            <ul className="space-y-4">
                                {['Home', 'About', 'Academics', 'Contact Us'].map((item) => (
                                    <li key={item} className="flex items-center gap-3"><div className="w-1.5 h-1.5 bg-black rounded-full" /><a href={`#${item.toLowerCase().replace(' ', '')}`} onClick={(e) => scrollToSection(e, item.toLowerCase().replace(' ', ''))} className="text-[13px] font-semibold text-slate-800 hover:text-blue-600"> {item} </a></li>
                                ))}
                            </ul>
                        </Col>
                        <Col xs={24} md={12} lg={7}>
                            <div className="flex gap-3 mb-6">
                                <a href="#" className="w-10 h-10 bg-black rounded-full flex items-center justify-center text-white hover:bg-[#0277EA] transition-all"><i className='bx bxl-facebook text-2xl'></i></a>
                                <a href="#" className="w-10 h-10 bg-black rounded-full flex items-center justify-center text-white hover:bg-[#0277EA] transition-all"><i className='bx bx-envelope text-xl'></i></a>
                            </div>
                            <p className="text-[15px] font-medium text-slate-900 leading-tight uppercase">GMSAMS - Grade Management & Monitoring System</p>
                        </Col>
                    </Row>
                </div>
            </footer>
        </div>
    );
}