import React from 'react';
import {
    GraduationCap,
    Users,
    Lightbulb,
    BookOpen,
    Target,
    Eye,
    Calculator,
    Utensils,
    Monitor,
    Settings,
    Mail,
    MapPin,
    Mail as MailIcon
} from 'lucide-react';
import  Logo  from '../../public/logo.svg';
import { FaFacebook } from 'react-icons/fa';

export default function LandingPage() {
    return (
        <div className="font-sans text-slate-800 bg-white">
            {/* --- NAVIGATION --- */}
            {/* <nav className="flex items-center justify-between px-10 py-4 sticky top-0 bg-white z-50 shadow-sm">
                <div className="flex items-center gap-2">
                    <div className="w-10 h-10 bg-blue-900 rounded-full flex items-center justify-center text-white font-bold text-xs">ATEC</div>
                    <span className="font-bold text-xl tracking-tighter text-blue-900">ATEC</span>
                </div>
                <div className="hidden md:flex gap-8 text-sm font-medium text-slate-600">
                    <a href="#" className="hover:text-blue-600 transition">Home</a>
                    <a href="#" className="hover:text-blue-600 transition">About</a>
                    <a href="#" className="hover:text-blue-600 transition">Academics</a>
                    <a href="#" className="hover:text-blue-600 transition">Contact Us</a>
                </div>
                <button className="border-2 border-blue-900 text-blue-900 px-6 py-1 rounded-full text-sm font-bold hover:bg-blue-900 hover:text-white transition">
                    LOG IN
                </button>
            </nav> */}
            <header className="w-full px-6 py-8  flex justify-center items-start">
                {/* Main Capsule Container */}
                <nav className="w-full max-w-8xl h-20 bg-white rounded-full shadow-xl flex items-center justify-between px-6 md:px-10 border border-gray-100">

                    {/* Left Side: Logo and Brand */}
                    <div className="flex items-center gap-3">
                        <div className="w-12 h-12 flex items-center justify-center">
                            {/* Replace with your actual logo image */}
                            <img
                                src={Logo}
                                alt="ATEC Logo"
                                className="rounded-full object-contain"
                            />
                        </div>
                        <span className="text-2xl font-black tracking-tight text-[#0f172a]">
                            ATEC
                        </span>
                    </div>

                    {/* Middle: Navigation Links */}
                    <ul className="hidden lg:flex items-center gap-8 xl:gap-12">
                        <li>
                            <a href="#home" className="text-[#3b82f6] font-semibold text-sm hover:opacity-80 transition-opacity">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="#about" className="text-gray-400 font-medium text-sm hover:text-gray-600 transition-colors">
                                About
                            </a>
                        </li>
                        <li>
                            <a href="#academics" className="text-gray-400 font-medium text-sm hover:text-gray-600 transition-colors">
                                Academics
                            </a>
                        </li>
                        <li>
                            <a href="#contact" className="text-gray-400 font-medium text-sm hover:text-gray-600 transition-colors">
                                Contact Us
                            </a>
                        </li>
                    </ul>

                    {/* Right Side: Log In Button */}
                    <div>
                        <button className="border-[1.5px] border-[#063F5C] text-[#063F5C] px-8 py-2 rounded-full text-xs font-bold tracking-wider hover:bg-[#0f172a] hover:text-white transition-all duration-300 uppercase">
                            Log In
                        </button>
                    </div>
                </nav>
            </header>


            {/* --- HERO SECTION --- */}
            <section className="relative h-[600px] flex flex-col items-center justify-center overflow-hidden">
                {/* Background Mockup */}
                <div className="absolute inset-0 z-0">
                    <img
                        src="https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&q=80&w=1920"
                        alt="Campus Building"
                        className="w-full h-full object-cover brightness-50"
                    />
                    <div className="absolute inset-0 bg-gradient-to-b from-transparent to-white"></div>
                </div>

                <div className="relative z-10 text-center px-4 max-w-4xl">
                    <h1 className="text-5xl md:text-6xl font-extrabold text-slate-900 mb-2">
                        Creating Competitive
                    </h1>
                    <h1 className="text-5xl md:text-6xl font-extrabold text-blue-600 mb-6">
                        Students, Globally.
                    </h1>
                    <p className="text-slate-600 text-lg mb-8 max-w-2xl mx-auto leading-relaxed">
                        ATEC Technological College provides students with quality and technology-driven education that enhances knowledge, skills, and professional growth.
                    </p>
                    <button className="bg-slate-900 text-white px-10 py-3 rounded-full font-bold hover:bg-blue-800 transition shadow-lg">
                        Get Started
                    </button>
                </div>
            </section>

            {/* --- VALUES / PILLARS --- */}
            <section className="px-10 -mt-12 relative z-20">
                <div className="max-w-6xl mx-auto bg-white rounded-xl shadow-xl grid grid-cols-1 md:grid-cols-4 p-8 border border-slate-100">
                    {[
                        { icon: <GraduationCap />, title: "ATTITUDE", desc: "ATEC believes that the right attitude leads to righteousness, discipline, and personal growth." },
                        { icon: <Users />, title: "TEAMWORK", desc: "ATEC values teamwork as a foundation for achieving its mission, vision, and shared goals." },
                        { icon: <Lightbulb />, title: "EMPOWERMENT", desc: "ATEC empowers graduates through skills, knowledge, and passion for community and economic development." },
                        { icon: <BookOpen />, title: "CHRIST-CENTERED", desc: "ATEC upholds Christ-centered principles in all its actions, decisions, and institutional practices." },
                    ].map((item, idx) => (
                        <div key={idx} className={`flex flex-col items-center text-center px-4 ${idx !== 3 ? 'md:border-r border-slate-100' : ''}`}>
                            <div className="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-4 border border-blue-100">
                                {item.icon}
                            </div>
                            <h3 className="font-bold text-sm mb-2 text-slate-900">{item.title}</h3>
                            <p className="text-[11px] text-slate-500 leading-tight uppercase">{item.desc}</p>
                        </div>
                    ))}
                </div>
            </section>

            {/* --- ABOUT SECTION --- */}
            <section className="py-20 px-10 max-w-7xl mx-auto">
                <div className="text-center mb-16">
                    <span className="bg-slate-800 text-white text-[10px] px-4 py-1 rounded-full font-bold uppercase tracking-widest">About</span>
                    <h2 className="text-4xl font-black text-slate-900 mt-4">Institutional Profile</h2>
                    <p className="text-slate-400 text-sm italic">Building Skills, Shaping Futures</p>
                </div>

                <div className="grid md:grid-cols-2 gap-12 items-center">
                    <div className="bg-blue-50 p-12 rounded-[3rem] border border-blue-100 relative overflow-hidden">
                        <div className="absolute top-0 right-0 w-32 h-32 bg-blue-100 rounded-full -mr-16 -mt-16 opacity-50"></div>
                        <h3 className="text-5xl font-black text-blue-600 mb-6">ABOUT US</h3>
                        <p className="text-blue-900/80 leading-relaxed text-sm">
                            ATEC Technological College is a private educational institution located in Apalit, Pampanga, Philippines. It is committed to providing quality and accessible education through its academic and technical-vocational programs that focus on skills development, competency-based learning, and values formation. The institution aims to produce globally competitive graduates who are well-prepared for employment, entrepreneurship, and further studies, making it one of the promising schools in the region.
                        </p>
                    </div>
                    <div className="rounded-3xl overflow-hidden shadow-2xl h-[400px]">
                        <img src="https://images.unsplash.com/photo-1541339907198-e08756ebafe3?auto=format&fit=crop&q=80&w=800" alt="ATEC Building" className="w-full h-full object-cover" />
                    </div>
                </div>
            </section>

            {/* --- MISSION & VISION --- */}
            <section className="relative py-20 px-10">
                <div className="absolute top-0 left-0 w-full h-32 bg-slate-900 -z-10 skew-y-1"></div>
                <div className="max-w-6xl mx-auto grid md:grid-cols-3 gap-8">
                    <div className="pt-10">
                        <h2 className="text-4xl font-black text-slate-900">Shaping <span className="text-blue-600">Purpose</span> and <span className="text-blue-600">Direction</span></h2>
                        <p className="text-slate-500 mt-4 text-sm">ATEC develops competent, values-driven, globally competitive individuals through quality education.</p>
                    </div>
                    <div className="bg-white p-8 rounded-2xl shadow-xl border-t-4 border-blue-600 flex flex-col items-center text-center">
                        <div className="w-14 h-14 bg-blue-50 rounded-full flex items-center justify-center text-blue-600 mb-4">
                            <Target size={30} />
                        </div>
                        <h4 className="font-black text-xl mb-4">MISSION</h4>
                        <p className="text-xs text-slate-500 leading-relaxed">ATEC Technological College is a non-stock and non-profit institution that provides quality education producing globally competitive workers through competency-based training employing moral values for the holistic transformation of individuals enabling them to seize the opportunity to manage their own business.</p>
                    </div>
                    <div className="bg-white p-8 rounded-2xl shadow-xl border-t-4 border-blue-900 flex flex-col items-center text-center">
                        <div className="w-14 h-14 bg-slate-50 rounded-full flex items-center justify-center text-blue-900 mb-4">
                            <Eye size={30} />
                        </div>
                        <h4 className="font-black text-xl mb-4">VISION</h4>
                        <p className="text-xs text-slate-500 leading-relaxed">ATEC Technological College aims to become one of the leading technological institutions offering industry-driven courses and producing highly skilled and morally upright individuals who create an impact to the society and contribute to the nation's progress.</p>
                    </div>
                </div>
            </section>

            {/* --- ACADEMICS --- */}
            <section className="py-20 px-10 bg-slate-50/50">
                <div className="text-center mb-16">
                    <span className="bg-slate-800 text-white text-[10px] px-4 py-1 rounded-full font-bold uppercase tracking-widest">Academics</span>
                    <h2 className="text-4xl font-black text-slate-900 mt-4 leading-tight">Senior High School Program</h2>
                    <p className="text-slate-400 text-sm">Prepares students for college, work, and future success.</p>
                </div>

                {/* Academic Strand */}
                <div className="max-w-6xl mx-auto mb-20">
                    <h3 className="text-center font-black text-2xl mb-10 text-slate-800">Academic Strand</h3>
                    <div className="grid md:grid-cols-3 gap-6">
                        {[
                            { id: 'ABM', title: 'ABM', sub: 'Accountancy, Business and Management', icon: <Calculator size={24} /> },
                            { id: 'HUMSS', title: 'HUMSS', sub: 'Humanities and Social Sciences', icon: <Users size={24} /> },
                            { id: 'GAS', title: 'GAS', sub: 'General Academic Strand', icon: <Settings size={24} /> },
                        ].map((strand) => (
                            <div key={strand.id} className="bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex items-center gap-4 hover:border-blue-300 transition group cursor-default">
                                <div className="w-16 h-16 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition">
                                    {strand.icon}
                                </div>
                                <div>
                                    <h4 className="font-black text-lg">{strand.id}</h4>
                                    <p className="text-[10px] text-slate-400 uppercase font-bold tracking-tight">{strand.sub}</p>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Tech-Voc Track */}
                <div className="max-w-6xl mx-auto">
                    <h3 className="text-center font-black text-2xl mb-10 text-slate-800">Tech-Voc Track</h3>
                    <div className="grid md:grid-cols-3 gap-8">
                        {/* HE */}
                        <div className="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-lg">
                            <div className="bg-slate-900 p-6 flex items-center gap-4 text-white">
                                <div className="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center"><Utensils /></div>
                                <div>
                                    <h4 className="font-bold text-xl">HE</h4>
                                    <p className="text-[10px] opacity-70">Home Economics</p>
                                </div>
                            </div>
                            <div className="p-6 space-y-3">
                                {['Dressmaking NC II', 'Beauty & Nail Care NC II', 'Housekeeping NC II', 'Front Office Services NC II', 'Hair Dressing NC II', 'Wellness Massage NC II', 'Bartending NC II', 'Food and Beverage NC II'].map(item => (
                                    <div key={item} className="flex items-start gap-2 text-xs text-slate-600">
                                        <span className="text-blue-600 font-bold">✓</span> {item}
                                    </div>
                                ))}
                            </div>
                        </div>
                        {/* ICT */}
                        <div className="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-lg">
                            <div className="bg-blue-900 p-6 flex items-center gap-4 text-white">
                                <div className="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center"><Monitor /></div>
                                <div>
                                    <h4 className="font-bold text-xl">ICT</h4>
                                    <p className="text-[10px] opacity-70 uppercase">Information and Communication Technology</p>
                                </div>
                            </div>
                            <div className="p-6 space-y-3">
                                {['Computer System Servicing NC II', 'Computer Programming'].map(item => (
                                    <div key={item} className="flex items-start gap-2 text-xs text-slate-600">
                                        <span className="text-blue-600 font-bold">✓</span> {item}
                                    </div>
                                ))}
                            </div>
                        </div>
                        {/* IA */}
                        <div className="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-lg">
                            <div className="bg-slate-800 p-6 flex items-center gap-4 text-white">
                                <div className="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center"><Settings /></div>
                                <div>
                                    <h4 className="font-bold text-xl">IA</h4>
                                    <p className="text-[10px] opacity-70">Industrial Arts</p>
                                </div>
                            </div>
                            <div className="p-6 space-y-3">
                                {['Shielded Metal Arc Welding NC II', 'Electronic Products Assembly and Servicing NC II'].map(item => (
                                    <div key={item} className="flex items-start gap-2 text-xs text-slate-600">
                                        <span className="text-blue-600 font-bold">✓</span> {item}
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* --- CONTACT SECTION --- */}
            <section className="relative py-24 px-10 overflow-hidden">
                <div className="absolute inset-0 bg-blue-900 -z-10">
                    <img
                        src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=1920"
                        className="w-full h-full object-cover opacity-20"
                        alt="Office"
                    />
                </div>

                <div className="max-w-6xl mx-auto grid md:grid-cols-2 items-center gap-16">
                    <div className="text-white">
                        <span className="bg-white/20 px-4 py-1 rounded-full text-xs font-bold uppercase mb-4 inline-block">Contact Us</span>
                        <h2 className="text-5xl font-black mb-6">We're Here to Help</h2>
                        <p className="text-blue-100 mb-8 max-w-md">Have questions or need assistance? Our team is always ready to guide and support you every step of the way.</p>
                        <div className="flex items-center gap-4 bg-white/10 p-4 rounded-xl border border-white/10 backdrop-blur-md">
                            <div className="w-10 h-10 bg-white text-blue-900 rounded-lg flex items-center justify-center"><MailIcon size={20} /></div>
                            <div>
                                <p className="text-xs opacity-60">Email Us</p>
                                <p className="font-bold">atec_collegeapalit@yahoo.com</p>
                            </div>
                        </div>
                    </div>

                    <div className="space-y-4">
                        <div className="bg-white p-6 rounded-2xl flex items-center gap-6 shadow-2xl">
                            <div className="w-14 h-14 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center"><MapPin /></div>
                            <div>
                                <h4 className="font-black text-blue-900">Apalit Branch</h4>
                                <p className="text-xs text-slate-500">San Vicente, Apalit Pampanga</p>
                            </div>
                        </div>
                        <div className="bg-white p-6 rounded-2xl flex items-center gap-6 shadow-2xl">
                            <div className="w-14 h-14 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center"><MapPin /></div>
                            <div>
                                <h4 className="font-black text-blue-900">Bulacan Branch</h4>
                                <p className="text-xs text-slate-500">Sta. Rita, Guiguinto, Bulacan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* --- FOOTER --- */}
            <footer className="bg-white pt-20 pb-10 border-t border-slate-100">
                <div className="max-w-7xl mx-auto px-10 grid md:grid-cols-3 gap-12 mb-16">
                    <div className="col-span-1">
                        <div className="flex items-center gap-2 mb-6">
                            <div className="w-8 h-8 bg-blue-900 rounded-full flex items-center justify-center text-white font-bold text-[8px]">ATEC</div>
                            <span className="font-black text-2xl tracking-tighter text-slate-900">ATEC</span>
                        </div>
                        <p className="text-xs text-slate-500 leading-relaxed">
                            ATEC Technological College provides students with quality and technology-driven education that enhances knowledge, skills, and professional growth. The institution aims to develop globally competitive, innovative, and morally responsible individuals prepared for future success.
                        </p>
                    </div>

                    <div className="md:pl-20">
                        <h5 className="font-bold text-sm mb-6 uppercase tracking-widest text-slate-400">Quick Links</h5>
                        <ul className="text-xs text-slate-600 space-y-3 font-medium">
                            <li><a href="#" className="hover:text-blue-600 transition inline-block relative before:content-['•'] before:mr-2 before:text-blue-600">Home</a></li>
                            <li><a href="#" className="hover:text-blue-600 transition inline-block relative before:content-['•'] before:mr-2 before:text-blue-600">About</a></li>
                            <li><a href="#" className="hover:text-blue-600 transition inline-block relative before:content-['•'] before:mr-2 before:text-blue-600">Academics</a></li>
                            <li><a href="#" className="hover:text-blue-600 transition inline-block relative before:content-['•'] before:mr-2 before:text-blue-600">Contact Us</a></li>
                        </ul>
                    </div>

                    <div className="flex flex-col items-end justify-between h-full">
                        <div className="flex gap-4">
                            <div className="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-600 hover:bg-blue-600 hover:text-white transition cursor-pointer"><FaFacebook size={18} /></div>
                            <div className="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-600 hover:bg-blue-600 hover:text-white transition cursor-pointer"><Mail size={18} /></div>
                        </div>
                        <p className="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-8">GMSAMS - Grade Management & Monitoring System</p>
                    </div>
                </div>
                <div className="border-t border-slate-50 pt-8 px-10 max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    <p>© 2023 ATEC Technological College</p>
                    <div className="w-full h-1 bg-slate-900 mt-4 md:mt-0 max-w-[100px]"></div>
                </div>
            </footer>
        </div>
    );
};
