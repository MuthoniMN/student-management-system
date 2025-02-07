import { useState } from "react";
import { Link } from "@inertiajs/react";
import { FaAngleDown, FaAngleUp, FaAngleRight, FaAngleLeft } from "react-icons/fa6";
import SecondaryButton from "@/Components/SecondaryButton";
import { RiArchiveStackFill } from "react-icons/ri";

export default function Sidebar(){
    const [current, setCurrent] = useState(0);
    const [open, setOpen] = useState(false);

    const handleClick = (index: number) => {
        if(current == index){
            setCurrent(0);
            return;
        }

        setCurrent(index);
        return;
    }

    return (
        <div className="h-screen flex items-start">

            <div className={`fixed top-36 left-0 text-white bg-gray-800 p-4 min-w-[240px] text-gray-600 flex flex-col justify-between items-center shadow-ld transition-transform ease-in-out duration-300 ${open ? 'translate-x-0' : '-translate-x-full'} gap-8`}>
                <div className="w-full">
                    <div className="p-2 space-y-4">
                        <h3 className={`flex justify-between items-center mouse-pointer ${current === 1 ? 'text-white font-bold' : 'text-gray-200'}`} onClick={() => handleClick(1)} >
                            Students
                            {current === 1 ? (<FaAngleUp />) : (<FaAngleDown />)}
                        </h3>
                        <nav className={`${current === 1 ? 'block' : 'hidden'} space-y-2 transition-all ease-in-out duration-300`}>
                            <ul className="space-y-4">
                                <li>
                                    <Link href={route('students.index')} className={`${route().current() === 'students.index' && 'text-white underline'} hover:underline`}>All Students</Link>
                                </li>
                                <li>
                                    <Link href={route('students.create')} className={`${route().current() === 'students.create' && 'text-white underline'} hover:underline`}>Add a New Student</Link>
                                </li>
                             </ul>
                        </nav>
                    </div>
                    <div className="p-2 space-y-4">
                        <h3 className={`flex justify-between items-center ${current === 2 ? 'text-white font-bold' : 'text-gray-300'}`} onClick={() => handleClick(2)}>
                        Semesters
                        {current === 2 ? (<FaAngleUp />) : (<FaAngleDown />)}
                        </h3>
                        <nav className={`${current === 2 ? 'block' : 'hidden'}`}>
                            <ul className="space-y-4">
                               <li>
                                    <Link href={route('semesters.index')} className={`${route().current() === 'semesters.index' && 'text-black underline'} hover:underline`}>All semesters</Link>
                                </li>
                               <li>
                                    <Link href={route('years.index')} className={`${route().current() === 'years.index' && 'text-black underline'}`}>All Academic Years</Link>
                                </li>
                               <li>
                                    <Link href={route('years.create')} className={`${route().current() === 'years.create' && 'text-black underline'} hover:underline`}>Start An Academic Year</Link>
                                </li>
                               <li>
                                    <Link href={route('semesters.create')} className={`${route().current() === 'semesters.create' && 'text-black underline'} hover:underline`}>Create a New Semester</Link>
                                </li>

                            </ul>
                        </nav>
                    </div>
                    <div className="space-y-4 p-2">
                        <h3 className={`flex justify-between items-center ${current === 3 ? 'text-white font-bold' : 'text-gray-200'}`} onClick={() => handleClick(3)}>
                        Subjects
                        {current === 3 ? (<FaAngleUp />) : (<FaAngleDown />)}
                        </h3>
                        <nav className={`${current === 3 ? 'block' : 'hidden'}`}>
                            <ul className="space-y-4">
                               <li>
                                    <Link href={route('subjects.index')} className={`${route().current() == 'subjects.index' ? 'text-white underline' : 'no-underline'} hover:underline`}>All Subjects</Link>
                                </li>
                               <li>
                                    <Link href={route('grades.index')} className={`${route().current() == 'grades.index' && 'text-white underline'} hover:underline`}>All Grades</Link>
                                </li>
                               <li>
                                    <Link href={route('subjects.create')} className={`${route().current() === 'subjects.create' && 'text-black underline'} hover:underline`}>Create a New Subject</Link>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <SecondaryButton>
                    <Link href={route('archive')} className="flex gap-4">Archive <RiArchiveStackFill className="text-lg" /></Link>
                </SecondaryButton>
            </div>
            <button className={`fixed bg-gray-600 top-36 ${open ? 'left-60' : 'left-0'} text-white py-4 px-2 rounded-r-lg transition-left ease-in-out duration-300`} onClick={() => setOpen(open => !open)}>
                { open ? (<FaAngleLeft />) : (<FaAngleRight />) }
            </button>
        </div>
    );
}
