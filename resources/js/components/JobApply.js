import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { Modal, ModalHeader, ModalBody, ModalFooter, Button } from 'reactstrap';
import axios from 'axios';

export default class JobApply extends Component {

    constructor() {
        super();
        this.state = {
            job: null,
            isOpen: false,
            loading: false,
            files: [],
            defaultResume: null,
            applied: false
        };
        this.fileInputRef = React.createRef();
    }

    componentDidMount() {
        this.setState({
            job: window.jobData || null,
            applied: window.isApplied || false,
            defaultResume: window.defaultResume || null

        });
    }

    toggleModal = () => {
        this.setState(prev => ({
            isOpen: !prev.isOpen,
            files: prev.isOpen ? [] : prev.files,
            defaultResume: prev.isOpen ? window.defaultResume : prev.defaultResume

        }));
    };

    handleFileChange = (e) => {
        const pickedFiles = Array.from(e.target.files);

        this.setState((prev) => {
            const selectedCount = prev.files.length + (prev.defaultResume ? 1 : 0);
            const allowedSlots = 3 - selectedCount;

            if (allowedSlots <= 0) {
                alert("Maximum 3 documents allowed");
                return null;
            }

            const filesToAdd = pickedFiles.slice(0, allowedSlots);

            if (pickedFiles.length > allowedSlots) {
                alert(`You can only add ${allowedSlots} more file(s)`);
            }

            return {
                files: [...prev.files, ...filesToAdd]
            };
        });

        e.target.value = null;
    };

    submitApplication = () => {
        if (this.state.applied) {
            alert("You already applied for this job.");
            return;
        }

        if (
            !this.state.job ||
            (this.state.files.length === 0 && !this.state.defaultResume)
        ) {
            alert("Please provide at least one document");
            return;
        }

        const formData = new FormData();

        this.state.files.forEach((file) => {
            formData.append("documents[]", file);
        });

        if (!this.state.defaultResume) {
            formData.append("use_default_resume", false);
        } else {
            formData.append("use_default_resume", true);
        }

        this.setState({ loading: true });

        axios.post(`/jobs/${this.state.job.id}/apply`, formData, {
            headers: {
                "Content-Type": "multipart/form-data"
            }
        })
            .then(() => {
                alert("Application submitted successfully!");
                this.setState({
                    isOpen: false,
                    files: [],
                    defaultResume: window.defaultResume,
                    applied: true
                });
            })
            .catch(() => {
                alert("Failed to apply");
            })
            .finally(() => {
                this.setState({ loading: false });
            });
    }

    removeFile = (index) => {
        this.setState((prev) => ({
            files: prev.files.filter((_, i) => i !== index)
        }));
    };

    resetForm = () => {
        this.setState({
            files: [],
            loading: false,
            isOpen: false,
            defaultResume: window.defaultResume

        });

        if (this.fileInputRef.current) {
            this.fileInputRef.current.value = null;
        }
    };

    render() {
        const savedResumeUrl = this.state.defaultResume
            ? `/storage/${this.state.defaultResume}`
            : null;

        return (
            <div>

                {/* APPLY BUTTON */}
                <button
                    onClick={this.toggleModal}
                    disabled={this.state.applied}
                    style={
                        this.state.applied
                            ? {
                                background: "grey",
                                color: "#fff",
                                padding: "10px 18px",
                                borderRadius: "8px",
                                border: "none",
                                fontSize: "13px",
                                fontWeight: "600",
                                cursor: "not-allowed"
                            }
                            : {
                                background: "#3B4FD8",
                                color: "#fff",
                                padding: "10px 18px",
                                borderRadius: "8px",
                                border: "none",
                                fontSize: "13px",
                                fontWeight: "600",
                                cursor: "pointer"
                            }
                    }
                >
                    {this.state.applied ? "Applied" : "Apply Now"}
                </button>

                {/* MODAL */}
                <Modal
                    isOpen={this.state.isOpen}
                    toggle={this.toggleModal}                >

                    <ModalHeader toggle={this.toggleModal}>
                        Confirm Application
                    </ModalHeader>

                    <ModalBody>
                        <p>Are you sure you want to apply this job?</p>

                        <h5>{this.state.job?.title}</h5>
                        <p>{this.state.job?.location}</p>

                        <hr />

                        <p><b>Upload Resume / Supporting Documents (PDF)</b></p>

                        <input
                            type="file"
                            accept="application/pdf"
                            multiple
                            onChange={this.handleFileChange}
                            style={{ display: "none" }}
                            ref={this.fileInputRef}
                        />

                        <button
                            type="button"
                            onClick={() => this.fileInputRef.current.click()}
                            disabled={
                                this.state.loading ||
                                (this.state.files.length + (this.state.defaultResume ? 1 : 0)) >= 3
                            }
                            style={{
                                background:
                                    (this.state.files.length + (this.state.defaultResume ? 1 : 0)) >= 3
                                        ? "#d1d5db"
                                        : "#3B4FD8",
                                color: "#fff",
                                padding: "10px 16px",
                                borderRadius: "8px",
                                border: "none",
                                fontSize: "13px",
                                fontWeight: "600",
                                cursor:
                                    (this.state.files.length + (this.state.defaultResume ? 1 : 0)) >= 3
                                        ? "not-allowed"
                                        : "pointer",
                                opacity:
                                    (this.state.files.length + (this.state.defaultResume ? 1 : 0)) >= 3
                                        ? 0.6
                                        : 1
                            }}
                        >
                            Upload Document
                        </button>

                        <ul style={{ marginTop: "10px", fontSize: "13px" }}>
                            {this.state.defaultResume && (
                                <li>
                                    <a
                                        href={savedResumeUrl}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        style={{ color: "#3B4FD8", textDecoration: "underline" }}
                                    >
                                        Your Saved Resume
                                    </a>
                                    <button onClick={() => this.setState({ defaultResume: null })}
                                        style={{
                                            marginLeft: "10px",
                                            background: "transparent",
                                            border: "none",
                                            color: "#9ca3af",
                                            fontSize: "16px",
                                            cursor: "pointer",
                                            transition: "0.2s"
                                        }}
                                        onMouseOver={(e) => e.target.style.color = "#ef4444"}
                                        onMouseOut={(e) => e.target.style.color = "#9ca3af"}>
                                        ✕
                                    </button>
                                </li>
                            )}

                            {this.state.files.map((file, i) => (
                                <li key={i}>
                                    {file.name}
                                    <button onClick={() => this.removeFile(i)}
                                        style={{
                                            marginLeft: "10px",
                                            background: "transparent",
                                            border: "none",
                                            color: "#9ca3af",
                                            fontSize: "16px",
                                            cursor: "pointer",
                                            transition: "0.2s"
                                        }}
                                        onMouseOver={(e) => e.target.style.color = "#ef4444"}
                                        onMouseOut={(e) => e.target.style.color = "#9ca3af"}>
                                        ✕
                                    </button>
                                </li>
                            ))}
                        </ul>

                        <p style={{ fontSize: "12px", color: "#6b7280" }}>
                            {this.state.files.length + (this.state.defaultResume ? 1 : 0)} / 3 files selected
                        </p>
                    </ModalBody>

                    <ModalFooter>
                        <Button color="secondary" onClick={this.resetForm}>
                            Cancel
                        </Button>
                        <Button
                            color="primary"
                            onClick={this.submitApplication}
                            disabled={
                                this.state.loading ||
                                (this.state.files.length === 0 && !this.state.defaultResume)
                            }                        >
                            {this.state.loading ? "Applying..." : "Confirm"}
                        </Button>
                    </ModalFooter>

                </Modal>

            </div>
        );
    }
}

if (document.getElementById('job-apply')) {
    ReactDOM.render(<JobApply />, document.getElementById('job-apply'));
}